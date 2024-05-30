<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'boss') {
  header("Location: index.php");
  exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "canteen";
$esp_ip = "192.168.17.172"; // Replace with your ESP32 IP address

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
  $order_id = $_POST['order_id'];
  $status = $_POST['status'];
  $estimated_time = $_POST['estimated_time'];

  $sql = "UPDATE orders SET status='$status', estimated_time='$estimated_time' WHERE id='$order_id'";
  if ($conn->query($sql) === TRUE) {
    echo "Order updated successfully";

    // Send order status to ESP32
    $order_status = "Order #".$order_id." - ".$status;
    $post_data = http_build_query(array('sentence' => $order_status));
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => $post_data,
      ),
    );
    $context  = stream_context_create($options);
    $response = file_get_contents("http://$esp_ip/update_status", false, $context);
    if ($response === FALSE) {
      echo "<p>Failed to send status to ESP32</p>";
      error_log("Failed to send status to ESP32");
    } else {
      echo "<p>ESP32 response: $response</p>";
      error_log("ESP32 response: $response");
    }
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

$sql_users = "SELECT COUNT(*) as user_count FROM users WHERE role='user'";
$result_users = $conn->query($sql_users);
$user_count = $result_users->fetch_assoc()['user_count'];

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Boss Dashboard</title>
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
    <img src="logos/yic.png" alt="Young Innovate Logo" style="width: 100px;">
    <img src="logos/chumbaka.png" alt="Chumbaka Logo" style="width: 100px;">
    <img src="logos/stemlab-logo-large.png" alt="STEM Lab Logo" style="width: 100px;">

    <h1>Boss Dashboard</h1>
    <p>Total Users: <?php echo $user_count; ?></p>

    <h2>Manage Orders</h2>
    <table>
      <tr>
        <th>Order ID</th>
        <th>Food Item</th>
        <th>Status</th>
        <th>Estimated Time</th>
        <th>Action</th>
      </tr>
      <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $row["id"]; ?></td>
          <td><?php echo $row["food_item"]; ?></td>
          <td><?php echo $row["status"]; ?></td>
          <td><?php echo $row["estimated_time"]; ?></td>
          <td>
            <form method="post">
              <input type="hidden" name="order_id" value="<?php echo $row["id"]; ?>">
              <select name="status">
                <option value="order received" <?php if ($row["status"] == 'order received') echo 'selected'; ?>>Order Received</option>
                <option value="preparing" <?php if ($row["status"] == 'preparing') echo 'selected'; ?>>Preparing</option>
                <option value="ready" <?php if ($row["status"] == 'ready') echo 'selected'; ?>>Ready</option>
              </select>
              <input type="number" name="estimated_time" value="<?php echo $row["estimated_time"]; ?>" min="1">
              <input type="submit" value="Update">
            </form>
          </td>
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
