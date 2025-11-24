<?php
session_start();
require "dbConx.php";

// Only admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../admin/adminLogin.php");
    exit();
}

if (!isset($_GET["id"]) || !isset($_GET["doctor_id"])) {
    die("Invalid parameters.");
}

$hour_id = (int) $_GET["id"];
$doctor_id = (int) $_GET["doctor_id"];

// Delete hour
$stmt = $pdo->prepare("DELETE FROM Doctor_Office_Hours WHERE id=?");
$stmt->execute([$hour_id]);

header("Location: ../admin/adminDoctorProfile.php?id=$doctor_id&hour_deleted=1");
exit();
?>
