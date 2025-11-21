<?php
session_start();
require "../Backend/dbconx.php";

if (!isset( $_SESSION["id"]) || $_SESSION["role"] !== "patient") {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION["id"];

// Fetch doctor notes from tests
$stmtTests = $pdo->prepare("
    SELECT T.Doctor_Report AS Note, T.Test_date AS Date,
           D.First_name, D.Last_name, D.Photo,
           CONCAT('Test: ', T.Test_name) AS Title
    FROM Medical_Tests T
    JOIN Doctors D ON T.Doctor_id = D.Doctor_id
    WHERE T.Patient_id = ? AND T.Doctor_Report IS NOT NULL
    ORDER BY T.Test_date DESC
");
$stmtTests->execute([$patient_id]);
$testNotes = $stmtTests->fetchAll(PDO::FETCH_ASSOC);

// Fetch doctor notes from medical records
$stmtRecords = $pdo->prepare("
    SELECT R.Notes AS Note, R.Date AS Date,
           D.First_name, D.Last_name, D.Photo,
           CONCAT('Visit: ', R.Diagnosis) AS Title
    FROM Medical_Record R
    JOIN Doctors D ON D.Doctor_id = (
        SELECT Doctor_id FROM Appointment 
        WHERE Patient_id = ? ORDER BY Appointment_Date DESC LIMIT 1
    )
    WHERE R.Patient_id = ? AND R.Notes IS NOT NULL
    ORDER BY R.Date DESC
");
$stmtRecords->execute([$patient_id, $patient_id]);
$recordNotes = $stmtRecords->fetchAll(PDO::FETCH_ASSOC);

$messages = array_merge($testNotes, $recordNotes);
usort($messages, fn($a, $b) => strtotime($b["Date"]) - strtotime($a["Date"]));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Messages</title>

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
        }

        .card-header img {
            width:65px; height:65px; border-radius:50%;
            object-fit:cover; border:3px solid #005bbb55;
        }

        .title { font-size:20px; font-weight:600; color:#003e74; }
        .subtitle { color:#555; }

        .message-box {
            background:#eef6ff;
            padding:15px;
            border-radius:10px;
            margin-top:10px;
            border-left:4px solid #005bbb;
        }

        .empty { text-align:center; font-size:20px; color:#777; margin-top:50px; }
    </style>
</head>

<body>

<div class="navbar">
    <img src="assets/logo.png">
    <a href="patientDashboard.php" class="login-btn" style="background:#005bbb;color:white;padding:10px 22px;border-radius:8px;text-decoration:none;">Dashboard</a>
</div>

<h1>Your Messages</h1>

<div class="container">

<?php if (count($messages) == 0): ?>

    <p class="empty">You have no messages or notes from doctors.</p>

<?php else: ?>

    <?php foreach ($messages as $msg): ?>
        <div class="card">

            <div class="card-header">
                <img src="../uploads/doctors/<?php echo $msg['Photo']; ?>">
                <div>
                    <div class="title"><?php echo $msg['Title']; ?></div>
                    <div class="subtitle">
                        From Dr. <?php echo $msg['First_name']." ".$msg['Last_name']; ?>  
                        • <?php echo $msg['Date']; ?>
                    </div>
                </div>
            </div>

            <div class="message-box">
                <?php echo nl2br($msg["Note"]); ?>
            </div>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>
