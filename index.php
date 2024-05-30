<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "canteen";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user'] = $user;
    if ($user['role'] == 'boss') {
      header("Location: boss_dashboard.php");
    } else {
      header("Location: user_dashboard.php");
    }
    exit();
  } else {
    $error = "Invalid username or password";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      width: 100%;
      max-width: 400px;
      box-sizing: border-box;
    }
    .login-container img {
      width: 150px;
      margin-bottom: 20px;
    }
    .login-container form {
      display: flex;
      flex-direction: column;
    }
    .login-container input[type="text"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .login-container input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-top: 20px;
    }
    .login-container input[type="submit"]:hover {
      background-color: #45a049;
    }
    .login-container footer {
      margin-top: 20px;
    }
    .login-container footer a {
      color: #000;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <img src="logos/yic.png" alt="Young Innovate Logo">
    <img src="logos/chumbaka.png" alt="Chumbaka Logo">
    <img src="logos/stemlab-logo-large.png" alt="STEM Lab Logo">
    <form method="post">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
      <input type="submit" value="Log In">
    </form>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <footer>
      <p>Created by <a href="https://hovahyii.vercel.app" target="_blank">Hovah Yii</a></p>
    </footer>
  </div>
</body>
</html>
