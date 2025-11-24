<?php
session_start();
require "../Backend/dbConx.php";

// Only allow admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: adminLogin.php");
    exit();
}

// Fetch all doctors
$stmt = $pdo->prepare("
    SELECT d.Doctor_id, d.First_name, d.Last_name, d.Specialty, d.Email, d.Phone, 
           Department.Department_name AS Dept
    FROM Doctors d
    JOIN Department ON d.Department_id = Department.Department_id
    ORDER BY d.Doctor_id DESC
");
$stmt->execute();
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Doctors - Admin</title>

    <link rel="stylesheet" href="../front/styling.css">
    <link rel="stylesheet" href="../front/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 35px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e6e6e6;
        }
        th {
            background: #005bbb;
            color: white;
        }
        .btn-add {
            padding: 10px 18px;
            background: #008000;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 15px;
        }
        .btn-edit {
            background: #005bbb;
            padding: 7px 12px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
        }
        .btn-hours {
            background: #ffaa00;
            padding: 7px 12px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
        }
        .btn-delete {
            background: #b30000;
            padding: 7px 12px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
        }
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>

<div class="navbar">
    <div class="logo">
        <img src="../medias/logo.png">
        <span>MediCare Hub</span>
    </div>
    <div class="nav-links">
        <a href="adminDashboard.php">Dashboard</a>
        <a href="../Backend/logoutAction.php" class="login-btn">Logout</a>
    </div>
</div>

<div class="dashboard-container">

    <div class="top-header">
        <h1 class="dash-title" style="text-align:left;">Doctors Management</h1>
        <a href="adminAddDoctor.php" class="btn-add">+ Add Doctor</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Specialty</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($doctors as $d): ?>
        <tr>
            <td><?= $d["Doctor_id"] ?></td>
            <td><?= $d["First_name"] . " " . $d["Last_name"] ?></td>
            <td><?= $d["Specialty"] ?></td>
            <td><?= $d["Email"] ?></td>
            <td><?= $d["Phone"] ?></td>
            <td><?= $d["Dept"] ?></td>
            <td>
                <a class="btn-edit" href="adminDoctorProfile.php?id=<?= $d["Doctor_id"] ?>">Edit</a>
                <a class="btn-delete" 
   href="../Backend/adminDeleteDoctor.php?id=<?= $d["Doctor_id"] ?>"
   onclick="return confirm('Delete this doctor?');">
   Delete
</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
