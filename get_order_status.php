<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "canteen";
$esp_ip = "192.168.17.172"; // Replace with your ESP32 IP address

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM orders WHERE status != 'ready' ORDER BY created_at ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Status</title>
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
    .status-container {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 80%;
      max-width: 800px;
      box-sizing: border-box;
      text-align: center;
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
    img {
      width: 100px;
      height: auto;
      border-radius: 10px;
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
  <div class="status-container">
    <img src="logos/sime-darby.png" alt="Sime Darby and Young Innovate Logo" style="width: 100px;">
    <img src="logos/chumbaka.png" alt="Chumbaka Logo" style="width: 100px;">
    <img src="logos/stemlab-logo-large.png" alt="STEM Lab Logo" style="width: 100px;">
    <h1>Order Status</h1>
    <?php
    if ($result->num_rows > 0) {
      echo "<table border='1'>";
      echo "<tr><th>Order ID</th><th>Food Item</th><th>Status</th><th>Estimated Time</th><th>Created At</th><th>Image</th></tr>";
      while($row = $result->fetch_assoc()) {
        $food_image = "";
        switch($row["food_item"]) {
          case "Spaghetti":
            $food_image = "https://dummyimage.com/100x100/000/fff&text=Spaghetti";
            break;
          case "Chicken Chop":
            $food_image = "https://dummyimage.com/100x100/000/fff&text=Chicken+Chop";
            break;
          case "Chicken Popcorn":
            $food_image = "https://dummyimage.com/100x100/000/fff&text=Chicken+Popcorn";
            break;
          case "Teh Tarik":
            $food_image = "https://dummyimage.com/100x100/000/fff&text=Teh+Tarik";
            break;
          case "Milo Ais":
            $food_image = "https://dummyimage.com/100x100/000/fff&text=Milo+Ais";
            break;
        }
        echo "<tr>";
        echo "<td>".$row["id"]."</td>";
        echo "<td>".$row["food_item"]."</td>";
        echo "<td>".$row["status"]."</td>";
        echo "<td>".$row["estimated_time"]."</td>";
        echo "<td>".$row["created_at"]."</td>";
        echo "<td><img src='$food_image' alt='".$row["food_item"]."'></td>";
        echo "</tr>";

     
      }
      echo "</table>";
    } else {
      echo "No orders";
    }

    $conn->close();
    ?>
    <footer>
      <p>Created by <a href="https://hovahyii.vercel.app" target="_blank">Hovah Yii</a></p>
    </footer>
  </div>
</body>
</html>
