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
$fname     = trim($_POST["fname"]);
$lname     = trim($_POST["lname"]);
$specialty = trim($_POST["specialty"]);
$phone     = trim($_POST["phone"]);
$email     = trim($_POST["email"]);
$dept      = (int) $_POST["department"];
$about     = trim($_POST["about"]);




// Update doctor info
$stmt = $pdo->prepare("
    UPDATE Doctors
    SET First_name=?, Last_name=?, Specialty=?, Phone=?, Email=?, About=?, Department_id=?
    WHERE Doctor_id=?
");

$stmt->execute([
    $fname, $lname, $specialty, $phone, $email, $about, $dept, $doctor_id
]);

// Redirect back
header("Location: ../admin/adminDoctorProfile.php?id=$doctor_id&updated=1");
exit();
?>
