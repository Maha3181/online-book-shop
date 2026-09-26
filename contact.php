<?php
session_start();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - My Online Book Shop</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; }
        .nav { background: #2c3e50; color: white; padding: 15px; text-align: center; }
        .nav a { color: white; text-decoration: none; font-weight: bold; margin: 0 15px; }
        .nav a:hover { color: #f39c12; }
        .container { max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; border-bottom: 2px solid #f39c12; padding-bottom: 10px; }
        p { color: #555; line-height: 1.6; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #333; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #2c3e50; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #34495e; }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="nav">
        <a href="index.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="read_later.php">Read Later List ⭐</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <a href="logout.php" style="color: #e74c3c;">Logout</a>
    </div>

    <!-- Contact Content Container -->
    <div class="container">
        <h2>Contact Us</h2>
        <p>Have questions, feedback, or suggestions regarding our Online Book Shop? Reach out to us using the form below!</p>
        
        <form action="#" method="POST">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter your name">
            </div>
            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required placeholder="Type your message here..."></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>

</body>
</html>