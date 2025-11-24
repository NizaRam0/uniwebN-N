<?php
session_start();
require "dbconx.php";

if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "patient") {
    header("Location: ../front/login.php");
    exit();
}

$patientId = $_SESSION["id"];

$fname = $_POST["fname"];
$lname = $_POST["lname"];
$dob = $_POST["dob"];
$gender = $_POST["gender"];
$email = $_POST["email"];
$condition = $_POST["condition"];
$blood = $_POST["blood"];

$sql = "UPDATE Patients 
        SET First_name=?, Last_name=?, Date_of_birth=?, Gender=?, Email=?, Pre_existing_condition=?, Blood_type=?
        WHERE Patient_id=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$fname, $lname, $dob, $gender, $email, $condition, $blood, $patientId]);

$_SESSION["success"] = "Information updated successfully!";

header("Location: ../front/viewInformation.php");
exit();
?>
