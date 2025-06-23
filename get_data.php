<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('config.php'); // No ../ needed now

$sql = "SELECT * FROM books"; // Replace with actual table name
$result = mysqli_query($con, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
