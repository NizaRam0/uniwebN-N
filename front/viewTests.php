<?php
session_start();
require "../Backend/dbconx.php";

if (!isset( $_SESSION["id"])  || $_SESSION["role"] !== "patient") {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION["id"];

// Fetch tests
$stmt = $pdo->prepare("
    SELECT T.*, D.First_name, D.Last_name, D.Specialty, D.Photo
    FROM Medical_Tests T
    JOIN Doctors D ON T.Doctor_id = D.Doctor_id
    WHERE T.Patient_id = ?
    ORDER BY T.Test_date DESC
");
$stmt->execute([$patient_id]);
$tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Test Results</title>

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
            font-size:32px; color:#003e74;
        }

        .container {
            width:80%; margin:30px auto;
        }

        .card {
            background:white;
            padding:20px;
            border-radius:12px;
            border:1px solid #d7eaff;
            margin-bottom:20px;
        }

        .card-header {
            display:flex; align-items:center; gap:18px;
            margin-bottom:15px;
        }

        .card-header img {
            width:80px; height:80px; border-radius:50%;
            object-fit:cover; border:3px solid #005bbb55;
        }

        .info h2 {
            margin:0; color:#003e74; font-size:22px; font-weight:600;
        }

        .info p { margin:2px 0; color:#444; }

        .result-box {
            background:#eef6ff; padding:15px; border-radius:10px;
            margin-top:12px; border-left:4px solid #005bbb;
        }

        .attachment-btn {
            margin-top:12px;
            padding:10px 18px;
            display:inline-block;
            background:#005bbb;
            color:white;
            border-radius:6px;
            text-decoration:none;
        }

        .empty {
            text-align:center; margin-top:40px; color:#666; font-size:20px;
        }
    </style>
</head>

<body>

<div class="navbar">
    <img src="assets/logo.png">
    <a class="login-btn" href="patientDashboard.php" style="background:#005bbb;color:white;padding:10px 20px;border-radius:8px;text-decoration:none;">Dashboard</a>
</div>

<h1>Your Test Reports</h1>

<div class="container">

<?php if (count($tests) == 0): ?>
    <p class="empty">No test reports available.</p>

<?php else: ?>

    <?php foreach ($tests as $t): ?>
        <div class="card">

            <!-- Doctor Info -->
            <div class="card-header">
                <img src="../uploads/doctors/<?php echo $t["Photo"]; ?>">
                <div class="info">
                    <h2><?php echo $t["Test_name"]; ?></h2>
                    <p><strong>Doctor:</strong> Dr. <?php echo $t["First_name"] . " " . $t["Last_name"]; ?></p>
                    <p><strong>Specialty:</strong> <?php echo $t["Specialty"]; ?></p>
                    <p><strong>Date:</strong> <?php echo $t["Test_date"]; ?></p>
                </div>
            </div>

            <!-- Test Result -->
            <div class="result-box">
                <p><strong>Result:</strong><br><?php echo nl2br($t["Result"]); ?></p>
            </div>

            <!-- Doctor Report -->
            <div class="result-box" style="margin-top:10px;">
                <p><strong>Doctor Report:</strong><br><?php echo nl2br($t["Doctor_Report"]); ?></p>
            </div>

            <!-- Attachment -->
            <?php if (!empty($t["Attachment"])): ?>
                <a href="../uploads/tests/<?php echo $t["Attachment"]; ?>" target="_blank" class="attachment-btn">
                    View Attachment
                </a>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>
