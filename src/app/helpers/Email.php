<?php

class Email {
    private $host = 'mailhog';
    private $port = 1025;
    private $from = 'noreply@viteetgourmand.fr';
    private $fromName = 'Vite & Gourmand';
    
    public function send($to, $subject, $htmlBody) {
        try {
            // Headers
            $headers = [
                'MIME-Version: 1.0',
                'Content-Type: text/html; charset=UTF-8',
                'From: ' . $this->fromName . ' <' . $this->from . '>',
                'Reply-To: ' . $this->from,
                'X-Mailer: PHP/' . phpversion()
            ];
            
            // Configuration SMTP pour MailHog
            ini_set('SMTP', $this->host);
            ini_set('smtp_port', $this->port);
            
            // Envoi
            $result = mail($to, $subject, $htmlBody, implode("\r\n", $headers));
            
            if ($result) {
                error_log("Email envoyé à : $to - Sujet : $subject");
                return true;
            } else {
                error_log("Erreur envoi email à : $to");
                return false;
            }
        } catch (Exception $e) {
            error_log("Exception email : " . $e->getMessage());
            return false;
        }
    }
    
    // Template HTML de base
    public function getTemplate($content) {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; color: #333; line-height: 1.6; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 12px; }
        .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .info-box { background: #f8f9fa; border-left: 4px solid #667eea; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍽️ Vite & Gourmand</h1>
        </div>
        <div class="content">
            ' . $content . '
        </div>
        <div class="footer">
            <p><strong>Vite & Gourmand</strong> - Traiteur événementiel à Bordeaux</p>
            <p>📞 05 56 00 00 00 | 📧 contact@viteetgourmand.fr</p>
            <p>123 Avenue de Bordeaux, 33000 Bordeaux</p>
        </div>
    </div>
</body>
</html>';
    }
}