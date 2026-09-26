<?php
session_start();
include 'db.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check which category is clicked. If nothing is clicked, it will be empty.
$category_selected = isset($_GET['cat']) ? $_GET['cat'] : '';

if ($category_selected) {
    $category_safe = mysqli_real_escape_string($conn, $category_selected);
    $sql = "SELECT * FROM books WHERE category='$category_safe'";
} else {
    $sql = ""; // If no category is selected, we don't fetch books (we show the category boxes instead)
}

$result = $category_selected ? mysqli_query($conn, $sql) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - My Online Book Shop</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; }
        .header { background: #2c3e50; color: white; padding: 15px; text-align: center; position: relative; }
        .nav { text-align: center; padding: 10px; background: #34495e; color: white; }
        .nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .nav a:hover { color: #f39c12; }
        .container { display: flex; flex-wrap: wrap; justify-content: center; padding: 20px; gap: 20px; }
        
        /* Category Card Style with Hover Animation */
        .category-card {
            background: #3498db;
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
            width: 250px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            animation: fadeIn 0.8s ease-out forwards;
            transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.3);
            background: #2980b9;
        }

        /* Book Card Style */
        .book-card { 
            background: white; padding: 20px; border-radius: 8px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 320px; 
            display: flex; flex-direction: column; align-items: center; 
            animation: fadeIn 0.8s ease-out forwards;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .book-card img { width: 150px; height: 200px; object-fit: cover; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .book-title { font-size: 18px; font-weight: bold; margin: 15px 0 5px; color: #2c3e50; text-align: center; }
        .book-author { color: #7f8c8d; font-size: 14px; margin-bottom: 10px; }
        .book-desc { font-size: 14px; color: #555; text-align: justify; height: 60px; overflow: hidden; margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; }
        .btn-group { display: flex; justify-content: space-between; gap: 10px; width: 100%; }
        .btn { flex: 1; padding: 10px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; text-decoration: none; font-size: 14px; text-align: center; }
        .btn-read { background: #27ae60; } .btn-read:hover { background: #2ecc71; }
        .btn-later { background: #f39c12; } .btn-later:hover { background: #e67e22; }
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
        <a href="categories.php" style="color: #f39c12;">Categories</a>
        <a href="read_later.php">Read Later List 🔖</a>
    </div>

    <?php if (!$category_selected): ?>
        <!-- Display Category Boxes -->
        <h2 style="text-align: center; margin-top: 20px; color: #2c3e50;">Browse by Categories</h2>
        <div class="container">
           <a href="categories.php?cat=Story" class="category-card">📚 Story Books</a>
           <a href="categories.php?cat=Self Development" class="category-card">💡 Self Development</a>
           <a href="categories.php?cat=Historical" class="category-card">🏛️ Historical</a>
           <a href="categories.php?cat=Technical / Programming" class="category-card">💻 Technical / Programming</a>
           <a href="categories.php?cat=Tamil Literature" class="category-card">📜 Tamil Literature</a>
        </div>
    <?php else: ?>
        <!-- Display Books based on selected category -->
        <h2 style="text-align: center; margin-top: 20px; color: #2c3e50;"><?php echo htmlspecialchars($category_selected); ?> Books</h2>
        
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="categories.php" style="text-decoration: none; color: #3498db; font-weight: bold;">← Back to All Categories</a>
        </div>

        <div class="container">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='book-card'>";
                    echo "<img src='images/" . htmlspecialchars($row['image']) . "' alt='Book Cover'>";
                    echo "<div class='book-title'>" . htmlspecialchars($row['title']) . "</div>";
                    echo "<div class='book-author'>Author: " . htmlspecialchars($row['author']) . "</div>";
                    echo "<div class='book-desc'>" . htmlspecialchars($row['description']) . "</div>";
                    
                    echo "<div class='btn-group'>";
                    echo "<a href='action.php?action=read&book_id=" . $row['id'] . "' class='btn btn-read'>📖 Start Reading</a>";
                    echo "<a href='action.php?action=later&book_id=" . $row['id'] . "' class='btn btn-later'>🔖 Read Later</a>";
                    echo "</div>";
                    
                    echo "</div>";
                }
            } else {
                echo "<p style='color: #7f8c8d; font-size: 18px;'>No books available in this category yet.</p>";
            }
            ?>
        </div>
    <?php endif; ?>

</body>
</html>