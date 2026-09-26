<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - My Online Book Shop</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; }
        .nav { background: #2c3e50; color: white; padding: 15px; text-align: center; }
        .nav a { color: white; text-decoration: none; font-weight: bold; margin: 0 15px; }
        .nav a:hover { color: #f39c12; }
        .container { max-width: 800px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; border-bottom: 2px solid #f39c12; padding-bottom: 10px; }
        p { line-height: 1.6; color: #555; font-size: 16px; }
        ul { color: #555; line-height: 1.6; font-size: 16px; }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="nav">
        <a href="index.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="read_later.php">Read Later List ⭐</a>
        <a href="about.php">About Us</a>
        <a href="logout.php" style="color: #e74c3c;">Logout</a>
    </div>

    <!-- About Content Container -->
    <div class="container">
        <h2>About Our Online Book Shop</h2>
        <p>Welcome to <strong>My Online Book Shop</strong>, a comprehensive digital library and e-book reading platform designed specifically for students, developers, and avid readers.</p>
        
        <p>Our primary objective is to bridge the gap between readers and knowledge by offering a centralized platform where users can explore, read books page-by-page, track their reading progress, and share their valuable feedback through reviews and ratings.</p>

        <h3>Core Technologies Used:</h3>
        <ul>
            <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript</li>
            <li><strong>Backend:</strong> PHP (Hypertext Preprocessor)</li>
            <li><strong>Database:</strong> MySQL (Managed via phpMyAdmin)</li>
            <li><strong>Server Environment:</strong> XAMPP (Apache Server)</li>
            <li><strong>Development Tool:</strong> Visual Studio Code</li>
        </ul>

        <p>This project is developed as part of our college academic curriculum to demonstrate full-stack web development capabilities, database management, and user session handling.</p>
    </div>

</body>
</html>
