<?php

require_once __DIR__ . '/../../public/vendor/autoload.php';

class PdfGenerator {
    
    public static function generateInvoice($order, $menu, $user) {
        // Créer un nouveau PDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Supprimer header/footer par défaut
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Définir les infos du document
        $pdf->SetCreator('Vite & Gourmand');
        $pdf->SetAuthor('Vite & Gourmand');
        $pdf->SetTitle('Facture #' . $order['id_commande']);
        
        // Définir les marges
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        
        // Ajouter une page
        $pdf->AddPage();
        
        // Définir la police
        $pdf->SetFont('helvetica', '', 10);
        
        // En-tête entreprise
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->SetTextColor(102, 51, 153); // Violet
        $pdf->Cell(0, 10, 'VITE & GOURMAND', 0, 1, 'L');
        
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 5, 'Traiteur événementiel à Bordeaux', 0, 1, 'L');
        $pdf->Cell(0, 5, '10 Rue du Traiteur, 33000 Bordeaux', 0, 1, 'L');
        $pdf->Cell(0, 5, 'Tél : 05 56 00 00 00 - Email : contact@viteetgourmand.fr', 0, 1, 'L');
        
        $pdf->Ln(10);
        
        // Titre facture
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, 'FACTURE N° ' . $order['id_commande'], 0, 1, 'C');
        
        $pdf->Ln(5);
        
        // Informations client et commande
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(95, 6, 'CLIENT', 1, 0, 'L', true);
        $pdf->Cell(95, 6, 'COMMANDE', 1, 1, 'L', true);
        
        $pdf->SetFont('helvetica', '', 10);
        
        // Client (colonne gauche)
        $y_start = $pdf->GetY();
        $pdf->MultiCell(95, 5, 
            $user['prenom'] . ' ' . $user['nom'] . "\n" .
            $user['email'] . "\n" .
            $user['gsm'], 
            'LRB', 'L', false, 0);
        
        // Commande (colonne droite)
        $pdf->SetXY(110, $y_start);
        $pdf->MultiCell(95, 5,
            'Date : ' . date('d/m/Y', strtotime($order['date_creation'])) . "\n" .
            'Statut : ' . ucfirst(str_replace('_', ' ', $order['statut'])) . "\n" .
            'Livraison : ' . date('d/m/Y à H:i', strtotime($order['date_livraison'] . ' ' . $order['heure_livraison'])),
            'LRB', 'L', false, 1);
        
        $pdf->Ln(10);
        
        // Tableau des produits
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetFillColor(102, 51, 153);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(100, 8, 'DESCRIPTION', 1, 0, 'L', true);
        $pdf->Cell(30, 8, 'QTÉ', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'P.U.', 1, 0, 'R', true);
        $pdf->Cell(30, 8, 'TOTAL', 1, 1, 'R', true);
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        
        // Ligne menu
        $pdf->Cell(100, 8, $menu['titre'], 1, 0, 'L');
        $pdf->Cell(30, 8, $order['nb_personnes'] . ' pers.', 1, 0, 'C');
        $pdf->Cell(30, 8, number_format($order['prix_menu'] / $order['nb_personnes'], 2) . ' €', 1, 0, 'R');
        $pdf->Cell(30, 8, number_format($order['prix_menu'], 2) . ' €', 1, 1, 'R');
        
        // Ligne livraison
        $pdf->Cell(100, 8, 'Frais de livraison', 1, 0, 'L');
        $pdf->Cell(30, 8, '1', 1, 0, 'C');
        $pdf->Cell(30, 8, number_format($order['prix_livraison'], 2) . ' €', 1, 0, 'R');
        $pdf->Cell(30, 8, number_format($order['prix_livraison'], 2) . ' €', 1, 1, 'R');
        
        $pdf->Ln(2);
        
        // Totaux
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(160, 6, 'Sous-total HT', 0, 0, 'R');
        $pdf->Cell(30, 6, number_format($order['prix_menu'] + $order['prix_livraison'], 2) . ' €', 0, 1, 'R');
        
        // Réduction si applicable
        if (isset($order['montant_reduction']) && $order['montant_reduction'] > 0) {
            $pdf->SetTextColor(0, 150, 0);
            $pdf->Cell(160, 6, 'Réduction code promo', 0, 0, 'R');
            $pdf->Cell(30, 6, '- ' . number_format($order['montant_reduction'], 2) . ' €', 0, 1, 'R');
            $pdf->SetTextColor(0, 0, 0);
        }
        
        $pdf->Cell(160, 6, 'TVA (20%)', 0, 0, 'R');
        $tva = $order['prix_total'] * 0.20;
        $pdf->Cell(30, 6, number_format($tva, 2) . ' €', 0, 1, 'R');
        
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(160, 8, 'TOTAL TTC', 1, 0, 'R', true);
        $pdf->Cell(30, 8, number_format($order['prix_total'], 2) . ' €', 1, 1, 'R', true);
        
        $pdf->Ln(10);
        
        // Adresse de livraison
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(0, 6, 'ADRESSE DE LIVRAISON', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell(0, 5, $order['adresse_livraison'], 0, 'L');
        
        $pdf->Ln(10);
        
        // Mentions légales
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->MultiCell(0, 4, 
            "Merci pour votre confiance !\n" .
            "Vite & Gourmand - SARL au capital de 10 000 € - RCS Bordeaux 123 456 789\n" .
            "N° TVA Intracommunautaire : FR12345678901\n" .
            "Conditions de paiement : Paiement à réception de facture",
            0, 'C');
        
        // Retourner le PDF
        return $pdf->Output('facture_' . $order['id_commande'] . '.pdf', 'S');
    }
}