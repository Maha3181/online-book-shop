<?php
session_start();
include 'db.php'; // Database connection

// Redirect to home if user is not logged in or book ID is missing
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$book_id = mysqli_real_escape_string($conn, $_GET['id']);
$total_pages = 30; // Total pages per book
// Review Submit Logic
if (isset($_POST['submit_review'])) {
    $rating = (int)$_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    mysqli_query($conn, "INSERT INTO reviews (user_id, book_id, rating, comment) VALUES ('$user_id', '$book_id', '$rating', '$comment')");
    header("Location: reader.php?id=" . $book_id);
    exit();
}
// RESUME READING PROGRESS LOGIC START
$saved_page = 1;
$check_sql = "SELECT last_page FROM user_books WHERE user_id='$user_id' AND book_id='$book_id'";
$check_res = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_res) > 0) {
    $row = mysqli_fetch_assoc($check_res);
    $saved_page = $row['last_page'] > 0 ? $row['last_page'] : 1;
}

if (isset($_GET['page'])) {
    $current_page = (int)$_GET['page'];
} else {
    $current_page = $saved_page; 
}

if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;

if (mysqli_num_rows($check_res) == 0) {
    mysqli_query($conn, "INSERT INTO user_books (user_id, book_id, status, last_page) VALUES ('$user_id', '$book_id', 'reading', '$current_page')");
} else {
    mysqli_query($conn, "UPDATE user_books SET last_page='$current_page' WHERE user_id='$user_id' AND book_id='$book_id'");
}
// RESUME READING PROGRESS LOGIC END

$sql = "SELECT * FROM books WHERE id='$book_id'";
$result = mysqli_query($conn, $sql);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    header("Location: index.php");
    exit();
}

$chapter_number = ceil($current_page / 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading: <?php echo htmlspecialchars($book['title']); ?></title>
    <style>
        body { background-color: #e0e0e0; font-family: 'Georgia', serif; margin: 0; padding: 20px; display: flex; justify-content: center; }
        .reader-container { background-color: #fffdf7; max-width: 800px; width: 100%; min-height: 90vh; padding: 40px 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); box-sizing: border-box; border-radius: 4px; display: flex; flex-direction: column; }
        .nav-bar { text-align: left; margin-bottom: 30px; border-bottom: 1px solid #ddd; padding-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        .nav-bar a { text-decoration: none; color: #3498db; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; }
        .nav-bar a:hover { color: #2980b9; }
        .save-badge { background-color: #2ecc71; color: white; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-family: Arial, sans-serif; }
        h1 { text-align: center; color: #222; font-size: 28px; margin-bottom: 5px; }
        .author { text-align: center; font-style: italic; color: #666; margin-bottom: 30px; font-size: 16px; }
        .content { text-align: justify; line-height: 1.8; font-size: 20px; color: #333; flex-grow: 1; }
        .content h2 { margin-top: 10px; font-size: 24px; color: #2c3e50; border-bottom: 1px dashed #ccc; padding-bottom: 10px; }
        .page-indicator { text-align: right; font-size: 14px; color: #888; font-family: Arial, sans-serif; }
        .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 40px; border-top: 1px solid #ddd; padding-top: 20px; font-family: Arial, sans-serif; }
        .btn-page { background-color: #34495e; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; transition: background 0.3s; }
        .btn-page:hover { background-color: #2c3e50; }
        .btn-disabled { background-color: #bdc3c7; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; pointer-events: none; }

        /* ==========================================
           📱 MOBILE RESPONSIVE DESIGN FOR READER
           ========================================== */
        @media (max-width: 768px) {
            body { padding: 10px; }
            .reader-container { padding: 20px 15px; width: 100%; margin: 0 auto; }
            .nav-bar { flex-direction: column; gap: 15px; text-align: center; }
            h1 { font-size: 22px; }
            .content { font-size: 16px; line-height: 1.6; }
            .pagination { flex-direction: column; gap: 15px; }
            .btn-page, .btn-disabled { width: 100%; text-align: center; box-sizing: border-box; }
        }
    </style>
</head>
<body>

    <div class="reader-container">
        <div class="nav-bar">
            <a href="index.php">← Back to Library</a>
            <span class="save-badge">✔ Progress Auto-Saved</span>
        </div>

        <h1><?php echo htmlspecialchars($book['title']); ?></h1>
        <div class="author">By <?php echo htmlspecialchars($book['author']); ?></div>
        
        <div class="content">
            <div class="page-indicator">Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></div>
            <h2>Chapter <?php echo $chapter_number; ?></h2>
            
            <?php if ($current_page == 1): ?>
                <p><strong>Synopsis:</strong> <?php echo htmlspecialchars($book['description']); ?></p>
            <?php endif; ?>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
            
            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra, est eros bibendum elit, nec luctus magna felis sollicitudin mauris.</p>

            <p>Integer in mauris eu nibh euismod gravida. Duis ac tellus et risus vulputate vehicula. Donec lobortis risus a elit. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo, non adipiscing enim. Fusce vel elit felis.</p>
        </div>

        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="reader.php?id=<?php echo $book_id; ?>&page=<?php echo $current_page - 1; ?>" class="btn-page">← Previous Page</a>
            <?php else: ?>
                <span class="btn-disabled">← Previous Page</span>
            <?php endif; ?>

            <span style="font-weight: bold; color: #2c3e50;">Page <?php echo $current_page; ?></span>

            <?php if ($current_page < $total_pages): ?>
                <a href="reader.php?id=<?php echo $book_id; ?>&page=<?php echo $current_page + 1; ?>" class="btn-page">Next Page →</a>
            <?php else: ?>
                <span class="btn-disabled">Next Page →</span>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
<hr style="margin: 40px 0; border: 0; border-top: 1px solid #ddd;">

    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; font-family: Arial, sans-serif;">
        <h3>⭐ Book Reviews & Ratings</h3>
        
        <!-- Review Form -->
        <form method="POST" action="">
            <label style="font-weight: bold; font-size: 14px;">Select Rating:</label><br>
            <select name="rating" style="padding: 8px; margin: 8px 0; border-radius: 4px; width: 100%;">
                <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                <option value="4">⭐⭐⭐⭐ (4/5)</option>
                <option value="3">⭐⭐⭐ (3/5)</option>
                <option value="2">⭐⭐ (2/5)</option>
                <option value="1">⭐ (1/5)</option>
            </select><br>
            
            <textarea name="comment" placeholder="Write your review about this book..." required style="width:100%; height:80px; padding: 10px; border-radius: 4px; box-sizing: border-box; margin-bottom: 10px;"></textarea><br>
            
            <button type="submit" name="submit_review" style="background:#27ae60; color:white; padding: 10px 20px; border:none; border-radius:4px; cursor:pointer; font-weight: bold;">Submit Review</button>
        </form>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">

        <!-- Display Reviews -->
        <h4>Recent Reviews:</h4>
        <?php
        $rev_sql = "SELECT reviews.*, users.username FROM reviews JOIN users ON reviews.user_id = users.id WHERE book_id='$book_id' ORDER BY id DESC";
        $rev_res = mysqli_query($conn, $rev_sql);
        
        if (mysqli_num_rows($rev_res) > 0) {
            while($rev = mysqli_fetch_assoc($rev_res)) {
                echo "<div style='background: white; padding: 12px; border-radius: 6px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);'>";
                echo "<b>" . htmlspecialchars($rev['username']) . "</b> - ";
                echo "<span style='color: #f39c12;'>";
                for($i=0; $i < $rev['rating']; $i++) { echo "⭐"; }
                echo "</span>";
                echo "<p style='margin: 5px 0 0; color: #444; font-size: 14px;'>" . htmlspecialchars($rev['comment']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p style='color: #7f8c8d; font-size: 14px;'>No reviews yet. Be the first one to review!</p>";
        }
        ?>
    </div>