<?php
include('config.php');

$status = $_POST['status'];
$schedule_date = $_POST['schedule_date'] ?? null;

// Validate input
if (!in_array($status, ['disabled', 'active_now', 'scheduled'])) {
    die("Invalid status selected.");
}

if ($status === 'scheduled' && empty($schedule_date)) {
    die("Schedule date required for scheduled status.");
}

$query = "UPDATE settings SET button_status='$status', schedule_date=";
$query .= ($schedule_date ? "'$schedule_date'" : "NULL");

mysqli_query($con, $query);
header("Location: manage_landing.php");
?>