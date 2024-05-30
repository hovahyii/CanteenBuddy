<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
  header("Location: index.php");
  exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "canteen";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders WHERE user_id=" . $_SESSION['user']['id'];
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      margin: 0;
    }
    .dashboard-container {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 80%;
      max-width: 800px;
      box-sizing: border-box;
      text-align: center;
    }
    .menu {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 20px;
    }
    .menu-item {
      margin: 10px;
      text-align: center;
    }
    .menu-item img {
      width: 150px;
      height: 150px;
      border-radius: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
    }
    th {
      background-color: #f4f4f4;
    }
    .logout-btn {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .logout-btn:hover {
      background-color: #45a049;
    }
    footer {
      margin-top: 20px;
    }
    footer a {
      color: #000;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <img src="logos/sime-darby.png" alt="Sime Darby and Young Innovate Logo" style="width: 100px;">
    <img src="logos/chumbaka.png" alt="Chumbaka Logo" style="width: 100px;">
    <img src="logos/stemlab-logo-large.png" alt="STEM Lab Logo" style="width: 100px;">
    <h1>User Dashboard</h1>
    <div class="menu">
      <div class="menu-item">
        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Spaghetti_al_Pomodoro.JPG" alt="Spaghetti">
        <p>Spaghetti</p>
      </div>
      <div class="menu-item">
        <img src="https://simplefoodvegetarian.com/wp-content/uploads/2018/06/Chicken-Chop-768x432.jpg" alt="Chicken Chop">
        <p>Chicken Chop</p>
      </div>
      <div class="menu-item">
        <img src="https://i.pinimg.com/originals/8a/5f/a1/8a5fa10bd4e45092f6c9c3245ca4db61.jpg" alt="Chicken Popcorn">
        <p>Chicken Popcorn</p>
      </div>
      <div class="menu-item">
        <img src="https://th.bing.com/th/id/R.9f55540583e89c4f900fb65691134206?rik=kROs6szR3uWe6Q&riu=http%3a%2f%2fmasses.com.my%2fwp-content%2fuploads%2f2019%2f12%2fteh-tarik.jpg&ehk=VWfYOvX1Msvn6YO%2fWHjd48iXKpNpgRwU3n6pXxFqSlA%3d&risl=&pid=ImgRaw&r=0" alt="Teh Tarik">
        <p>Teh Tarik</p>
      </div>
      <div class="menu-item">
        <img src="https://2.bp.blogspot.com/-78uVKXXWpps/T0HrYqnuy0I/AAAAAAAAFR8/q0YVbN4X6HU/s1600/milo+ais+kaw.JPG" alt="Milo Ais">
        <p>Milo Ais</p>
      </div>
    </div>
    <form method="post" action="place_order.php">
      <label for="food_item">Select Food Item:</label>
      <select id="food_item" name="food_item">
        <option value="Spaghetti">Spaghetti</option>
        <option value="Chicken Chop">Chicken Chop</option>
        <option value="Chicken Popcorn">Chicken Popcorn</option>
        <option value="Teh Tarik">Teh Tarik</option>
        <option value="Milo Ais">Milo Ais</option>
      </select>
      <input type="submit" value="Order">
    </form>
    <h2>Your Orders</h2>
    <table>
      <tr>
        <th>Order ID</th>
        <th>Food Item</th>
        <th>Status</th>
        <th>Estimated Time (min)</th>
      </tr>
      <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $row["id"]; ?></td>
          <td><?php echo $row["food_item"]; ?></td>
          <td><?php echo $row["status"]; ?></td>
          <td><?php echo $row["estimated_time"]; ?> </td>
        </tr>
      <?php } ?>
    </table>
    <form method="post" action="logout.php">
      <button class="logout-btn" type="submit">Logout</button>
    </form>
    <footer>
      <p>Created by <a href="https://hovahyii.vercel.app" target="_blank">Hovah Yii</a></p>
    </footer>
  </div>
</body>
</html>
