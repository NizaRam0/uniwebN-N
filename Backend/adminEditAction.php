<?php
session_start();
require "dbconx.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../admin/adminLogin.php");
    exit();
}

if (!isset($_POST["patient_id"])) {
    die("No patient ID received.");
}

$id = (int) $_POST["patient_id"];

$fname = $_POST["fname"];
$lname = $_POST["lname"];
$dob = $_POST["dob"];
$gender = $_POST["gender"];
$email = $_POST["email"];
$condition = $_POST["condition"];
$blood = $_POST["blood"];

$stmt = $pdo->prepare("
    UPDATE Patients
    SET First_name=?, Last_name=?, Date_of_birth=?, Gender=?, Email=?, 
        Pre_existing_condition=?, Blood_type=?
    WHERE Patient_id=?
");

$stmt->execute([$fname, $lname, $dob, $gender, $email, $condition, $blood, $id]);

header("Location: ../admin/adminPatients.php?updated=1");
exit();
?>
