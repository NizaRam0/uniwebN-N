<?php
session_start();
require "dbConx.php";

// Only admin can add doctors
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../admin/adminLogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin/adminAddDoctor.php");
    exit();
}

// Collect form data
$fname      = trim($_POST["fname"]);
$lname      = trim($_POST["lname"]);
$specialty  = trim($_POST["specialty"]);
$phone      = trim($_POST["phone"]);
$email      = trim($_POST["email"]);
$department = (int) $_POST["department"];
$about      = trim($_POST["about"]);
$password   = password_hash($_POST["password"], PASSWORD_BCRYPT);

// Default photo (if no photo uploaded)
$photo_name = null;

// Handle photo upload
if (!empty($_FILES["photo"]["name"])) {

    $target_dir = "../medias/doctors/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $extension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
    $photo_name = "doctor_" . time() . "_" . rand(1000,9999) . "." . $extension;

    $target_file = $target_dir . $photo_name;

    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
}

// Insert into database
$stmt = $pdo->prepare("
    INSERT INTO Doctors 
    (First_name, Last_name, Specialty, Phone, Email, Photo, About, Department_id, Password)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $fname,
    $lname,
    $specialty,
    $phone,
    $email,
    $photo_name,  // can be NULL
    $about,
    $department,
    $password
]);

// Redirect back
header("Location: ../admin/adminDoctors.php?added=1");
exit();
?>
