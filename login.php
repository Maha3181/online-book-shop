<?php
session_start();
include 'db.php'; // Database connection

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php"); 
            exit();
        } else {
            $error = "Incorrect Password! Please try again.";
        }
    } else {
        $error = "Email not found! Please create an account.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - My Online Bookshop</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 350px; }
        h2 { text-align: center; color: #2c3e50; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; background: #2ecc71; color: white; padding: 12px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; text-align: center; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Welcome Back! 🔐</h2>
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn">Login</button>
        </form>
        <div style="text-align: center; margin-top: 15px;">
            Don't have an account? <a href="register.php">Create one</a>
        </div>
    </div>
</body>
</html>