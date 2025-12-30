<?php

class Analytics {
    private $manager;
    private $bulk;
    
    public function __construct() {
        try {
            $this->manager = new MongoDB\Driver\Manager("mongodb://mongo:27017");
        } catch (Exception $e) {
            error_log("MongoDB Analytics Error: " . $e->getMessage());
        }
    }
    
    // Logger une action
    public function logAction($action, $data = []) {
        try {
            $document = [
                'action' => $action,
                'user_id' => $_SESSION['user_id'] ?? null,
                'user_email' => $_SESSION['user_email'] ?? null,
                'page' => $_SERVER['REQUEST_URI'] ?? null,
                'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                'data' => $data,
                'timestamp' => new MongoDB\BSON\UTCDateTime()
            ];
            
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert($document);
            $this->manager->executeBulkWrite('vite_gourmand.analytics', $bulk);
        } catch (Exception $e) {
            error_log("Analytics Log Error: " . $e->getMessage());
        }
    }
    
    // Obtenir les pages les plus visitées
    public function getTopPages($limit = 10) {
        try {
            $pipeline = [
                ['$match' => ['action' => 'page_view']],
                ['$group' => [
                    '_id' => '$page',
                    'count' => ['$sum' => 1]
                ]],
                ['$sort' => ['count' => -1]],
                ['$limit' => $limit]
            ];
            
            $command = new MongoDB\Driver\Command([
                'aggregate' => 'analytics',
                'pipeline' => $pipeline,
                'cursor' => new stdClass,
            ]);
            
            $cursor = $this->manager->executeCommand('vite_gourmand', $command);
            return $cursor->toArray();
        } catch (Exception $e) {
            error_log("Analytics Error: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtenir les actions par jour (30 derniers jours)
    public function getActionsByDay($days = 30) {
        try {
            $startDate = new MongoDB\BSON\UTCDateTime(strtotime("-{$days} days") * 1000);
            
            $pipeline = [
                ['$match' => ['timestamp' => ['$gte' => $startDate]]],
                ['$group' => [
                    '_id' => [
                        'year' => ['$year' => '$timestamp'],
                        'month' => ['$month' => '$timestamp'],
                        'day' => ['$dayOfMonth' => '$timestamp']
                    ],
                    'count' => ['$sum' => 1]
                ]],
                ['$sort' => ['_id' => 1]]
            ];
            
            $command = new MongoDB\Driver\Command([
                'aggregate' => 'analytics',
                'pipeline' => $pipeline,
                'cursor' => new stdClass,
            ]);
            
            $cursor = $this->manager->executeCommand('vite_gourmand', $command);
            return $cursor->toArray();
        } catch (Exception $e) {
            error_log("Analytics Error: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtenir les actions par type
    public function getActionsByType() {
        try {
            $pipeline = [
                ['$group' => [
                    '_id' => '$action',
                    'count' => ['$sum' => 1]
                ]],
                ['$sort' => ['count' => -1]]
            ];
            
            $command = new MongoDB\Driver\Command([
                'aggregate' => 'analytics',
                'pipeline' => $pipeline,
                'cursor' => new stdClass,
            ]);
            
            $cursor = $this->manager->executeCommand('vite_gourmand', $command);
            return $cursor->toArray();
        } catch (Exception $e) {
            error_log("Analytics Error: " . $e->getMessage());
            return [];
        }
    }
    
    // Obtenir le nombre total d'actions
    public function getTotalActions() {
        try {
            $command = new MongoDB\Driver\Command(['count' => 'analytics']);
            $cursor = $this->manager->executeCommand('vite_gourmand', $command);
            $result = $cursor->toArray();
            return $result[0]->n ?? 0;
        } catch (Exception $e) {
            error_log("Analytics Error: " . $e->getMessage());
            return 0;
        }
    }
}