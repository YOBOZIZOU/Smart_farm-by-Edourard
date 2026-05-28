<?php
header("Access-Control-Allow-Origin: *");

$host   = getenv("DB_HOST");
$user   = getenv("DB_USER");
$pass   = getenv("DB_PASS");
$dbname = getenv("DB_NAME");

$device   = isset($_GET['device'])   ? $_GET['device']   : 'UNKNOWN';
$lat      = isset($_GET['lat'])      ? $_GET['lat']      : 0;
$lon      = isset($_GET['lon'])      ? $_GET['lon']      : 0;
$temp     = isset($_GET['temp'])     ? $_GET['temp']     : 0;
$distance = isset($_GET['distance']) ? $_GET['distance'] : 0;
$status   = isset($_GET['status'])   ? $_GET['status']   : 'NO_GPS';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  echo "ERROR: " . $conn->connect_error;
  exit;
}

$conn->query("CREATE TABLE IF NOT EXISTS cow_data (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  device    VARCHAR(50),
  lat       DOUBLE,
  lon       DOUBLE,
  temp      FLOAT,
  distance  FLOAT,
  status    VARCHAR(20),
  recorded  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$sql  = "INSERT INTO cow_data (device, lat, lon, temp, distance, status)
         VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdddds", $device, $lat, $lon, $temp, $distance, $status);

if ($stmt->execute()) {
  echo "OK";
} else {
  echo "ERROR: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
