<?php
// submit_comment.php

if (!empty($_POST['website_hp'])) {
    die("Spam detected.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if (empty($username) || empty($comment)) {
        die("Please fill in all required fields.");
    }

    $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');
    $date = date('Y-m-d H:i');

    try {
        // Path to SQLite DB file
        $db = new PDO('sqlite:' . __DIR__ . '/comments.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec("CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT, 
            name TEXT, 
            comment TEXT, 
            created_at TEXT
        )");

        $stmt = $db->prepare("INSERT INTO comments (name, comment, created_at) VALUES (:name, :comment, :created_at)");
        $stmt->execute([
            ':name' => $username,
            ':comment' => $comment,
            ':created_at' => $date
        ]);

        header("Location: /server-guide#feedback-section");
        exit();
    } catch (PDOException $e) {
        // Displays exact database error if write fails
        die("Database error: " . $e->getMessage());
    }
}
?>
