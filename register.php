<?php
session_start();
include 'db.php'; // Database connection

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
        $msg = "<div class='error'>Email already exists! Try logging in.</div>";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            $msg = "<div class='success'>Registration Successful! 🎉 Please login.</div>";
        } else {
            $msg = "<div class='error'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - My Online Bookshop</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 350px; }
        h2 { text-align: center; color: #2c3e50; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; background: #3498db; color: white; padding: 12px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; text-align: center; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; text-align: center; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create Account 📝</h2>
        <?php if(isset($msg)) echo $msg; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register" class="btn">Register</button>
        </form>
        <div style="text-align: center; margin-top: 15px;">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>