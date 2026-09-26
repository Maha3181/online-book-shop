<?php
session_start();
include 'db.php'; // Database connection

// Check if the user is logged in before accessing this page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch books that the current user has marked as 'read_later'
$sql = "SELECT books.* FROM books 
        INNER JOIN user_books ON books.id = user_books.book_id 
        WHERE user_books.user_id = '$user_id' AND user_books.status = 'read_later'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Later - My Online Book Shop</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; }
        .header { background: #2c3e50; color: white; padding: 15px; text-align: center; position: relative; }
        .nav { text-align: center; padding: 10px; background: #34495e; color: white; }
        .nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .nav a:hover { color: #f39c12; }
        .container { display: flex; flex-wrap: wrap; justify-content: center; padding: 20px; }
        
        /* Book Card Style with Animation */
        .book-card { 
            background: white; 
            padding: 20px; 
            margin: 15px; 
            border-radius: 8px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            width: 320px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            animation: fadeIn 0.8s ease-out forwards;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Hover effect */
        .book-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.2);
        }

        /* Fade-in Animation keyframes */
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .book-card img { width: 150px; height: 200px; object-fit: cover; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .book-title { font-size: 18px; font-weight: bold; margin: 15px 0 5px; color: #2c3e50; text-align: center; }
        .book-author { color: #7f8c8d; font-size: 14px; margin-bottom: 15px; }
        .btn-read { background: #27ae60; padding: 10px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; text-decoration: none; font-size: 14px; text-align: center; width: 100%; box-sizing: border-box; }
        .btn-read:hover { background: #2ecc71; }
        .user-info { position: absolute; right: 20px; top: 20px; font-size: 14px; }
        .user-info a { color: #ffcccc; text-decoration: none; font-weight: bold; margin-left: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>📚 My Online Book Shop</h1>
        <div class="user-info">
            Hello, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>! 
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="nav">
        <a href="index.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="read_later.php" style="color: #f39c12;">Read Later List 🔖</a>
    </div>

    <h2 style="text-align: center; margin-top: 20px; color: #e67e22;">My Read Later List 🔖</h2>

    <div class="container">
        <?php
        // Check if there are any books in the user's list
        if (mysqli_num_rows($result) > 0) {
            // Loop through and display each book
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='book-card'>";
                echo "<img src='images/" . htmlspecialchars($row['image']) . "' alt='Book Cover'>";
                echo "<div class='book-title'>" . htmlspecialchars($row['title']) . "</div>";
                echo "<div class='book-author'>Author: " . htmlspecialchars($row['author']) . "</div>";
                echo "<a href='action.php?action=read&book_id=" . $row['id'] . "' class='btn-read'>📖 Start Reading</a>";
                echo "</div>";
            }
        } else {
            echo "<p style='color: #7f8c8d; font-size: 18px;'>You haven't added any books to your Read Later list yet.</p>";
        }
        ?>
    </div>

</body>
</html>