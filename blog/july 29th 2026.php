<?php
// 1. PATH TO YOUR SAVED COMMENTS FILE
$file = "comments.txt";

// 2. CHECK IF A NEW COMMENT WAS SUBMITTED
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic sanitization to prevent malicious HTML injection
    $name = htmlspecialchars(trim($_POST['name']));
    $comment = htmlspecialchars(trim($_POST['comment']));

    if (!empty($name) && !empty($comment)) {
        // Format the comment output
        $entry = "<strong>" . $name . "</strong><br>" . $comment . "<hr>\n";
        
        // Append the comment to comments.txt
        file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
    }
}
?>

<!-- 3. HTML COMMENT FORM -->
<div class="comment-section">
  <h3>Leave a Comment</h3>
  <form action="july 29th 2026.php" method="POST">
    <p>
      <label>Name:</label><br>
      <input type="text" name="name" required>
    </p>
    <p>
      <label>Comment:</label><br>
      <textarea name="comment" rows="4" required></textarea>
    </p>
    <button type="submit">Submit Comment</button>
  </form>

  <h3>Comments</h3>
  <div class="comments-list">
    <?php
    // 4. DISPLAY EXISTING COMMENTS
    if (file_exists($file)) {
        echo file_get_contents($file);
    } else {
        echo "<p>No comments yet. Be the first!</p>";
    }
    ?>
  </div>
</div>
