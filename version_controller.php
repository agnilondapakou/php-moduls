<?php

// CREATE TABLE document_versions (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     document_name VARCHAR(255) NOT NULL,
//     version VARCHAR(20) NOT NULL,
//     content TEXT,
//     date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
//   );

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=mabase', 'username', 'password');

// Fonction pour enregistrer une nouvelle version d'un document
function enregistrerVersion($documentName, $content) {
    global $pdo;

    // Récupérer la dernière version existante
    $stmt = $pdo->prepare("SELECT MAX(version) AS last_version FROM document_versions WHERE document_name = :documentName");
    $stmt->execute([':documentName' => $documentName]);
    $result = $stmt->fetch();

    // Calculer la nouvelle version
    $nouvelleVersion = ($result['last_version'] ?? '1.0') + 0.1;

    // Insérer la nouvelle version dans la base de données
    $stmt = $pdo->prepare("INSERT INTO document_versions (document_name, version, content) VALUES (:documentName, :version, :content)");
    $stmt->execute([
        ':documentName' => $documentName,
        ':version' => number_format($nouvelleVersion, 1, '.', ''),
        ':content' => $content
    ]);
}

// Exemple d'utilisation
$documentName = 'mon_document';
$nouveauContenu = 'Nouveau contenu pour la version 1.1';
enregistrerVersion($documentName, $nouveauContenu);