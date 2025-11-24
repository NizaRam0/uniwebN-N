<?php
session_start();
require "../Backend/dbConx.php";

// Only allow admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: adminLogin.php");
    exit();
}

// Fetch all patients
$stmt = $pdo->prepare("SELECT * FROM Patients ORDER BY Patient_id DESC");
$stmt->execute();
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Patients - Admin</title>

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
            display: inline-block;
            padding: 10px 18px;
            background: #008000;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 15px;
            margin-bottom: 15px;
        }

        .btn-edit {
            background: #005bbb;
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

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">
        <img src="../medias/logo.png" alt="Logo">
        <span>MediCare Hub</span>
    </div>

    <div class="nav-links">
        <a href="adminDashboard.php">Dashboard</a>
        <a href="../Backend/logoutAction.php" class="login-btn">Logout</a>
    </div>
</div>

<div class="dashboard-container">

    <div class="top-header">
        <h1 class="dash-title" style="text-align:left;">Patients Management</h1>
        <a href="../front/register.php" class="btn-add">+ Add Patient</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Blood Type</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($patients as $p): ?>
        <tr>
            <td><?= $p["Patient_id"] ?></td>
            <td><?= $p["First_name"] . " " . $p["Last_name"] ?></td>
            <td><?= $p["Age"] ?></td>
            <td><?= $p["Blood_type"] ?></td>
            <td><?= $p["Email"] ?></td>
            <td>
                <a class="btn-edit" href="adminedit.php?id=<?= $p["Patient_id"] ?>">Edit</a>
                <a class="btn-delete" href="../Backend/adminDeletePatient.php?id=<?= $p["Patient_id"] ?>"
                   onclick="return confirm('Are you sure you want to delete this patient?');">
                   Delete
                </a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
