<?php

class EmailTemplates {
    
    // Email de confirmation de commande
    public static function confirmationCommande($commande, $menu, $utilisateur) {
        $date_livraison = date('d/m/Y', strtotime($commande['date_livraison']));
        $prix_total = number_format($commande['prix_total'], 2);
        
        $content = "
            <h2>✅ Votre commande a été enregistrée !</h2>
            <p>Bonjour <strong>{$utilisateur['prenom']}</strong>,</p>
            <p>Nous avons bien reçu votre commande. Voici les détails :</p>
            
            <div class='info-box'>
                <h3>📋 Détails de la commande #{$commande['id_commande']}</h3>
                <p><strong>Menu :</strong> {$menu['titre']}</p>
                <p><strong>Nombre de personnes :</strong> {$commande['nb_personnes']}</p>
                <p><strong>Date de livraison :</strong> {$date_livraison}</p>
                <p><strong>Adresse de livraison :</strong> {$commande['adresse_livraison']}</p>
                <p><strong>Prix total :</strong> {$prix_total} €</p>
            </div>
            
            <p>Nous allons traiter votre commande dans les plus brefs délais. Vous recevrez un email de confirmation dès que votre commande sera acceptée.</p>
            
            <p>Pour toute question, n'hésitez pas à nous contacter.</p>
            
            <p>Merci de votre confiance ! 🎉</p>
            <p><strong>L'équipe Vite & Gourmand</strong></p>
        ";
        
        return $content;
    }
    
    // Email de changement de statut
    public static function changementStatut($commande, $menu, $utilisateur, $nouveau_statut) {
        $date_livraison = date('d/m/Y', strtotime($commande['date_livraison']));
        $prix_total = number_format($commande['prix_total'], 2);
        
        $statuts = [
            'accepte' => ['titre' => '✅ Votre commande a été acceptée !', 'message' => 'Bonne nouvelle ! Nous avons accepté votre commande et nous commençons sa préparation.'],
            'en_preparation' => ['titre' => '👨‍🍳 Votre commande est en préparation', 'message' => 'Nos chefs sont à l\'œuvre ! Votre menu est actuellement en cours de préparation.'],
            'terminee' => ['titre' => '🎉 Votre commande a été livrée !', 'message' => 'Votre commande a été livrée avec succès. Nous espérons que vous avez apprécié nos services !'],
            'annulee' => ['titre' => '❌ Votre commande a été annulée', 'message' => 'Nous sommes désolés, votre commande a été annulée. Pour plus d\'informations, contactez-nous.']
        ];
        
        $info = $statuts[$nouveau_statut] ?? $statuts['accepte'];
        
        $content = "
            <h2>{$info['titre']}</h2>
            <p>Bonjour <strong>{$utilisateur['prenom']}</strong>,</p>
            <p>{$info['message']}</p>
            
            <div class='info-box'>
                <h3>📋 Commande #{$commande['id_commande']}</h3>
                <p><strong>Menu :</strong> {$menu['titre']}</p>
                <p><strong>Nombre de personnes :</strong> {$commande['nb_personnes']}</p>
                <p><strong>Date de livraison :</strong> {$date_livraison}</p>
                <p><strong>Prix total :</strong> {$prix_total} €</p>
                <p><strong>Nouveau statut :</strong> <span style='color: #667eea; font-weight: bold;'>" . strtoupper(str_replace('_', ' ', $nouveau_statut)) . "</span></p>
            </div>
        ";
        
        if ($nouveau_statut === 'terminee') {
            $content .= "
                <p>Vous pouvez maintenant laisser un avis sur votre commande !</p>
                <a href='http://localhost:8080/avis/create/{$menu['id_menu']}' class='button'>⭐ Laisser un avis</a>
            ";
        }
        
        $content .= "
            <p>Pour toute question, n'hésitez pas à nous contacter.</p>
            <p><strong>L'équipe Vite & Gourmand</strong></p>
        ";
        
        return $content;
    }
    
    // Email de validation d'avis
    public static function validationAvis($utilisateur, $menu, $avis) {
        $content = "
            <h2>✅ Votre avis a été validé !</h2>
            <p>Bonjour <strong>{$utilisateur['prenom']}</strong>,</p>
            <p>Merci d'avoir pris le temps de laisser un avis sur notre menu <strong>{$menu['titre']}</strong> !</p>
            
            <div class='info-box'>
                <h3>⭐ Votre avis</h3>
                <p><strong>Note :</strong> {$avis['note']}/5 ⭐</p>
                <p><strong>Commentaire :</strong> {$avis['commentaire']}</p>
            </div>
            
            <p>Votre avis est maintenant visible sur notre site et aidera d'autres clients à faire leur choix.</p>
            <p>Nous vous remercions pour votre confiance et espérons vous revoir bientôt ! 🎉</p>
            
            <p><strong>L'équipe Vite & Gourmand</strong></p>
        ";
        
        return $content;
    }
}