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
$esp_ip = "192.168.17.172"; // Replace with your ESP32 IP address

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_id = $_SESSION['user']['id'];
  $food_item = $_POST['food_item'];
  $status = 'order received';

  $sql = "INSERT INTO orders (user_id, food_item, status) VALUES ('$user_id', '$food_item', '$status')";
  if ($conn->query($sql) === TRUE) {
    echo "Order placed successfully";

    // Send order status to ESP32
    $order_id = $conn->insert_id;
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

$conn->close();

header("Location: user_dashboard.php");
exit();
?>
