<?php
session_start();
include 'db.php'; // Database connection

// Security Check: Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['action']) && isset($_GET['book_id'])) {
    $action = $_GET['action'];
    $book_id = mysqli_real_escape_string($conn, $_GET['book_id']);
    $user_id = $_SESSION['user_id'];

    if ($action == 'later') {
        // Check if the book is already in the user's read later list
        $check_sql = "SELECT * FROM user_books WHERE user_id='$user_id' AND book_id='$book_id'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) == 0) {
            // If it does not exist, insert a new record
            mysqli_query($conn, "INSERT INTO user_books (user_id, book_id, status) VALUES ('$user_id', '$book_id', 'read_later')");
        } else {
            // If it exists, update the status
            mysqli_query($conn, "UPDATE user_books SET status='read_later' WHERE user_id='$user_id' AND book_id='$book_id'");
        }
        
        // Redirect to the Read Later page after adding
        header("Location: read_later.php");
        exit();
        
    } elseif ($action == 'read') {
        // Redirect to the reader page
        header("Location: reader.php?id=$book_id");
        exit();
    }
}

// Redirect to home page as a fallback
header("Location: index.php");
exit();
?>