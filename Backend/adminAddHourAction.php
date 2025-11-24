<?php
session_start();
require "dbConx.php";

// Only admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../admin/adminLogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$doctor_id = (int) $_POST["doctor_id"];
$weekday   = (int) $_POST["weekday"];
$start     = $_POST["start"];
$end       = $_POST["end"];
$slot      = (int) $_POST["slot"];

$stmt = $pdo->prepare("
    INSERT INTO Doctor_Office_Hours (Doctor_id, Weekday, Start_time, End_time, Slot_length)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([$doctor_id, $weekday, $start, $end, $slot]);

header("Location: ../admin/adminDoctorProfile.php?id=$doctor_id&hours_added=1");
exit();
?>
