<?php
session_start();
require "../Backend/dbConx.php";

// Only admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: adminLogin.php");
    exit();
}

// Get doctor id
if (!isset($_GET['id'])) {
    die("No doctor id provided.");
}
$doctor_id = (int) $_GET['id'];

// Fetch doctor info
$stmt = $pdo->prepare("
    SELECT Doctor_id, First_name, Last_name, Specialty, Phone, Email, Photo, About, Department_id
    FROM Doctors
    WHERE Doctor_id = ?
");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    die("Doctor not found.");
}

// Fetch all departments
$dept_stmt = $pdo->prepare("SELECT Department_id, Department_name FROM Department ORDER BY Department_name");
$dept_stmt->execute();
$departments = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch doctor's office hours
$hours_stmt = $pdo->prepare("
    SELECT id, Weekday, Start_time, End_time, Slot_length
    FROM Doctor_Office_Hours
    WHERE Doctor_id = ?
    ORDER BY Weekday
");
$hours_stmt->execute([$doctor_id]);
$hours = $hours_stmt->fetchAll(PDO::FETCH_ASSOC);

// Weekday names
$weekdays = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Doctor Profile - MediCare Hub</title>

    <link rel="stylesheet" href="../front/styling.css">
    <link rel="stylesheet" href="../front/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        .section {
            width: 70%;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #d7eaff;
        }

        .section h2 {
            font-size: 24px;
            color: #003e74;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-weight: 600;
            color: #003e74;
            margin-bottom: 6px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #cfdfff;
            font-size: 16px;
        }

        textarea { height: 120px; }

        .btn-save {
            display: block;
            width: 220px;
            padding: 12px;
            margin: 25px auto 0;
            background: #005bbb;
            color: white;
            border-radius: 8px;
            font-size: 17px;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e6e6e6;
            text-align: left;
        }

        th {
            background: #005bbb;
            color: white;
        }

        .btn-delete {
            background: #c70000;
            padding: 6px 12px;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-add {
            background: #008000;
            padding: 10px 16px;
            color: white;
            border-radius: 8px;
            font-size: 15px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
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

<h1 class="dash-title">Doctor Profile Management</h1>

<!-- ============================================================
     DOCTOR INFORMATION
=============================================================== -->
<div class="section">
    <h2>Doctor Information</h2>

    <form action="../Backend/adminEditDoctorAction.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="doctor_id" value="<?= $doctor['Doctor_id'] ?>">

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="fname" value="<?= $doctor['First_name'] ?>" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lname" value="<?= $doctor['Last_name'] ?>" required>
        </div>

        <div class="form-group">
            <label>Specialty</label>
            <input type="text" name="specialty" value="<?= $doctor['Specialty'] ?>" required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= $doctor['Phone'] ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= $doctor['Email'] ?>" required>
        </div>

        <div class="form-group">
            <label>Department</label>
            <select name="department" required>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept['Department_id'] ?>" 
                        <?= $dept['Department_id'] == $doctor['Department_id'] ? 'selected' : '' ?>>
                        <?= $dept['Department_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>About</label>
            <textarea name="about"><?= $doctor['About'] ?></textarea>
        </div>

        
        
        <button type="submit" class="btn-save">Save Changes</button>
    </form>
</div>


<!-- ============================================================
     OFFICE HOURS
=============================================================== -->
<div class="section">
    <h2>Office Hours</h2>

    <!-- Table of existing hours -->
    <table>
        <tr>
            <th>Weekday</th>
            <th>Start</th>
            <th>End</th>
            <th>Slot</th>
            <th>Action</th>
        </tr>

        <?php foreach ($hours as $h): ?>
        <tr>
            <td><?= $weekdays[$h['Weekday']] ?></td>
            <td><?= substr($h['Start_time'],0,5) ?></td>
            <td><?= substr($h['End_time'],0,5) ?></td>
            <td><?= $h['Slot_length'] ?> min</td>
            <td>
                <a href="../Backend/adminDeleteHour.php?id=<?= $h['id'] ?>&doctor_id=<?= $doctor_id ?>" 
                   class="btn-delete"
                   onclick="return confirm('Delete this office hour?');">
                    Delete
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Add new hours form -->
    <form action="../Backend/adminAddHourAction.php" method="POST" style="margin-top:20px;">
        <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">

        <label>Weekday</label>
        <select name="weekday" required>
            <?php foreach ($weekdays as $i => $day): ?>
                <option value="<?= $i ?>"><?= $day ?></option>
            <?php endforeach; ?>
        </select>

        <label>Start Time</label>
        <input type="time" name="start" required>

        <label>End Time</label>
        <input type="time" name="end" required>

        <label>Slot Length (minutes)</label>
        <input type="number" name="slot" value="30" required>

        <button type="submit" class="btn-add">Add Hours</button>
    </form>

</div>

</body>
</html>
