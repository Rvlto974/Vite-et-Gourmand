<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand - Test Docker</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
        }
        h1 { color: #2E5090; margin-bottom: 20px; }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2E5090;
            color: white;
        }
        .check { color: #28a745; font-weight: bold; }
        .cross { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Vite & Gourmand</h1>
        <div class="success">
            ✅ Docker fonctionne !
        </div>

        <h2>Informations PHP</h2>
        <p><strong>Version PHP :</strong> <?php echo phpversion(); ?></p>
        <p><strong>Serveur :</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>

        <h2>Extensions PHP</h2>
        <table>
            <thead>
                <tr>
                    <th>Extension</th>
                    <th>Statut</th>
                </tr>
            </thea