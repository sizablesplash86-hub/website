<?php
// load_comments.php
$db = new PDO('sqlite:comments.db');
$db->exec("CREATE TABLE IF NOT EXISTS comments (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, comment TEXT, created_at TEXT)");

$stmt = $db->query("SELECT name, comment, created_at FROM comments ORDER BY id DESC");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($comments)) {
    echo "<p>No feedback yet. Be the first to leave a comment!</p>";
} else {
    foreach ($comments as $c) {
        echo "<div class='comment-card'>";
        echo "<strong>" . $c['name'] . "</strong> <small>(" . $c['created_at'] . ")</small>";
        echo "<p>" . nl2br($c['comment']) . "</p>";
        echo "</div>";
    }
}
?>
