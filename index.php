<?php
session_start();

// Check if the user is logged in. If not, redirect to login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Database connection

// SEARCH LOGIC: Check if user searched for anything
$search = "";
if (isset($_GET['query'])) {
    $search = mysqli_real_escape_string($conn, $_GET['query']);
    $sql = "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM books";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Online Book Shop</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; }
        .header { background: #2c3e50; color: white; padding: 15px; text-align: center; position: relative; }
        .nav { text-align: center; padding: 10px; background: #34495e; color: white; display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 15px; }
        .nav a { color: white; text-decoration: none; font-weight: bold; }
        .nav a:hover { color: #f39c12; }
        
        /* Search Bar Styling inside Nav */
        .search-box { display: flex; gap: 5px; }
        .search-input { padding: 6px 10px; border-radius: 4px; border: none; width: 200px; font-size: 14px; }
        .search-btn { background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .search-btn:hover { background: #c0392b; }

        /* Container styling */
        .container { display: flex; flex-wrap: wrap; justify-content: center; padding: 20px; gap: 20px; }
        
        /* Book Card Style with Animation */
        .book-card { 
            background: white; padding: 20px; border-radius: 8px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 320px; 
            display: flex; flex-direction: column; align-items: center; 
            animation: fadeIn 0.8s ease-out forwards;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
        }

        .book-card:hover { transform: translateY(-15px); box-shadow: 0 12px 20px rgba(0,0,0,0.3); }

        @keyframes fadeIn { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }

        .book-card img { width: 150px; height: 200px; object-fit: cover; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .book-title { font-size: 18px; font-weight: bold; margin: 15px 0 5px; color: #2c3e50; text-align: center; }
        .book-author { color: #7f8c8d; font-size: 14px; margin-bottom: 10px; }
        .book-desc { font-size: 14px; color: #555; text-align: justify; height: 60px; overflow: hidden; margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; }
        
        .btn-group { display: flex; justify-content: space-between; gap: 10px; width: 100%; }
        .btn { flex: 1; padding: 10px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; text-decoration: none; font-size: 14px; text-align: center; }
        .btn-read { background: #27ae60; }
        .btn-read:hover { background: #2ecc71; }
        .btn-later { background: #f39c12; }
        .btn-later:hover { background: #e67e22; }
        
        .user-info { position: absolute; right: 20px; top: 20px; font-size: 14px; }
        .user-info a { color: #ffcccc; text-decoration: none; font-weight: bold; margin-left: 10px; }
        .user-info a:hover { color: white; }

        /* ==========================================
           📱 MOBILE RESPONSIVE DESIGN
           ========================================== */
        @media (max-width: 768px) {
            .header h1 { font-size: 22px; }
            .user-info { position: static; text-align: center; margin-top: 15px; display: block; }
            .nav { flex-direction: column; gap: 10px; padding: 15px; }
            .search-box { width: 100%; justify-content: center; }
            .search-input { width: 70%; }
            .book-card { width: 100%; max-width: 320px; }
            .container { padding: 10px; }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Online Book Shop</h1>
        <div class="user-info">
            Hello, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>! 
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="nav">
        <a href="index.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="read_later.php">Read Later List 🔖</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        
        <!-- Search Form -->
        <form action="index.php" method="GET" class="search-box">
            <input type="text" name="query" class="search-input" placeholder="Search books or author..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>
<!-- Hero Banner Section -->
    <div style="background: linear-gradient(rgba(44, 62, 80, 0.85), rgba(44, 62, 80, 0.85)), #2c3e50; color: white; padding: 35px 20px; text-align: center; margin-bottom: 20px;">
        <h2 style="font-size: 28px; margin: 0 0 10px 0;">LOTS OF EBOOKS. 100% FREE FOR STUDENTS</h2>
        <p style="font-size: 15px; color: #ecf0f1; margin: 0;">Explore your college technical books, stories, and self-development guides instantly.</p>
    </div>
    <h2 style="text-align: center; margin-top: 20px; color: #2c3e50;">
        <?php echo !empty($search) ? "Search Results for: '" . htmlspecialchars($search) . "'" : "Available E-Books"; ?>
    </h2>

    <div class="container">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='book-card'>"; 
                
                // Wrap book cover image and title in a hyperlink for direct navigation to the reader page
                echo "<a href='reader.php?id=" . $row['id'] . "' style='text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; width: 100%;'>";
                echo "<img src='images/" . htmlspecialchars($row['image']) . "' alt='Book Cover'>";
                echo "<div class='book-title'>" . htmlspecialchars($row['title']) . "</div>";
                echo "</a>";

                echo "<div class='book-author'>Author: " . htmlspecialchars($row['author']) . "</div>";
                echo "<div class='book-desc'>" . htmlspecialchars($row['description']) . "</div>";
                
                // Action buttons group for starting to read and saving to read-later list
                echo "<div class='btn-group'>";
                echo "<a href='reader.php?id=" . $row['id'] . "' class='btn btn-read'>📖 Start Reading</a>";
                echo "<a href='action.php?action=later&book_id=" . $row['id'] . "' class='btn btn-later'>🔖 Read Later</a>";
                echo "</div>";
                echo "</div>"; 
            }
        } else {
            echo "<p style='font-size: 18px; color: #e74c3c;'>No books found matching your search!</p>";
        }
        ?>
    </div>
<!-- ================= CUSTOMER REVIEWS SECTION ================= -->
    <div style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">
        <h3 style="text-align: center; margin-bottom: 20px; color: #2c3e50;">Customer Reviews & Comments</h3>
        <div style="display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;">
            
            <!-- Review 1 -->
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 320px; box-sizing: border-box;">
                <h5 style="margin: 0 0 5px 0; color: #2c3e50; font-size: 16px;">Rahul Kumar</h5>
                <h6 style="margin: 0 0 10px 0; font-size: 13px; color: #f39c12;">⭐ 5/5 Stars</h6>
                <p style="margin: 0; font-size: 14px; color: #555;">"Great collection of books! Delivery was fast and the website is very easy to use."</p>
            </div>

            <!-- Review 2 -->
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 320px; box-sizing: border-box;">
                <h5 style="margin: 0 0 5px 0; color: #2c3e50; font-size: 16px;">Priya S</h5>
                <h6 style="margin: 0 0 10px 0; font-size: 13px; color: #f39c12;">⭐ 4/5 Stars</h6>
                <p style="margin: 0; font-size: 14px; color: #555;">"Found all my semester textbooks in one place. Highly recommended for students!"</p>
            </div>

            <!-- Review 3 -->
            <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 320px; box-sizing: border-box;">
                <h5 style="margin: 0 0 5px 0; color: #2c3e50; font-size: 16px;">Arun V</h5>
                <h6 style="margin: 0 0 10px 0; font-size: 13px; color: #f39c12;">⭐ 5/5 Stars</h6>
                <p style="margin: 0; font-size: 14px; color: #555;">"Smooth checkout process and amazing user interface. Love buying books here."</p>
            </div>

        </div>
    </div>
</body>
</html>