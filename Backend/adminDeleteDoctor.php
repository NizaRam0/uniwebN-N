<?php
session_start();
require "dbConx.php";

// Only admin can delete doctors
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../admin/adminLogin.php");
    exit();
}

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Error: No doctor ID provided.");
}

$doctor_id = (int) $_GET["id"];

$stmt = $pdo->prepare("DELETE FROM Doctors WHERE Doctor_id = ?");
$success = $stmt->execute([$doctor_id]);

if ($success) {
    header("Location: ../admin/adminDoctors.php?deleted=1");
    exit();
} else {
    die("Error deleting doctor.");
}
?>
