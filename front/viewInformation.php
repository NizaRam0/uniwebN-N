<?php
session_start();
require "../Backend/dbconx.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../front/login.php");
    exit();
}

$patient_id = $_SESSION["id"];

// Fetch patient information
$stmt = $pdo->prepare("
    SELECT First_name, Last_name, Date_of_birth, Gender, Email, 
           Pre_existing_condition, Blood_type, Age
    FROM Patients
    WHERE Patient_id = ?
");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    die("Patient information not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Information - MediCare Hub</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body { margin:0; font-family:'Poppins', sans-serif; background:#f7faff; }

        .navbar {
            display:flex; justify-content:space-between;
            padding:18px 60px; background:white; border-bottom:1px solid #e5e5e5;
        }
        .navbar img { width:44px; }

        h1 {
            text-align:center; margin-top:40px;
            color:#003e74; font-size:32px;
        }

        .container {
            width:60%; margin:30px auto;
            background:white; padding:30px;
            border-radius:12px; border:1px solid #d7eaff;
        }

        .info-row {
            margin-bottom:20px;
        }

        .label {
            font-weight:600; color:#003e74;
        }

        .value {
            margin-top:5px; font-size:18px; color:#444;
        }

        .edit-btn {
            display:block; width:200px;
            padding:12px; margin:25px auto 0;
            background:#005bbb; color:white;
            text-align:center;
            border-radius:8px; font-size:18px;
            text-decoration:none;
        }
    </style>
</head>

<body>

<div class="navbar">
    <img src="assets/logo.png">
    <a href="patientDashboard.php" class="login-btn" style="background:#005bbb;color:white;padding:10px 22px;border-radius:8px;text-decoration:none;">
        Dashboard
    </a>
</div>

<h1>Your Information</h1>

<div class="container">

    <div class="info-row">
        <div class="label">Full Name</div>
        <div class="value">
            <?php echo $patient["First_name"] . " " . $patient["Last_name"]; ?>
        </div>
    </div>

    <div class="info-row">
        <div class="label">Email</div>
        <div class="value"><?php echo $patient["Email"]; ?></div>
    </div>

    <div class="info-row">
        <div class="label">Date of Birth</div>
        <div class="value"><?php echo $patient["Date_of_birth"]; ?></div>
    </div>

    <div class="info-row">
        <div class="label">Age</div>
        <div class="value"><?php echo $patient["Age"]; ?></div>
    </div>

    <div class="info-row">
        <div class="label">Gender</div>
        <div class="value"><?php echo $patient["Gender"] === "M" ? "Male" : "Female"; ?></div>
    </div>

    <div class="info-row">
        <div class="label">Blood Type</div>
        <div class="value"><?php echo $patient["Blood_type"]; ?></div>
    </div>

    <div class="info-row">
        <div class="label">Pre-Existing Conditions</div>
        <div class="value"><?php echo $patient["Pre_existing_condition"]; ?></div>
    </div>

    <a href="#" class="edit-btn">Edit Information</a>

</div>

</body>
</html>
