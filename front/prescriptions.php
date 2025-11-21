<?php
session_start();
require "../Backend/dbconx.php";

if (!isset($_SESSION["id"])) {
    header("Location: ../front/login.php");
    exit();
}

$patient_id = $_SESSION["id"];

// Fetch prescriptions
$stmt = $pdo->prepare("
    SELECT P.*, D.First_name, D.Last_name, D.Specialty, D.Photo
    FROM Prescriptions P
    JOIN Doctors D ON P.Doctor_id = D.Doctor_id
    WHERE P.Patient_id = ?
    ORDER BY P.Date_prescribed DESC
");
$stmt->execute([$patient_id]);
$pres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Prescriptions</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="styling.css">
    <style>
        body { margin:0; font-family:'Poppins', sans-serif; background:#f7faff; }
        .navbar {
            display:flex; justify-content:space-between; padding:18px 60px;
            background:white; border-bottom:1px solid #e5e5e5;
        }
        .navbar img { width:15%; }

        h1 { text-align:center; margin-top:40px; color:#003e74; }

        .container { width:80%; margin:30px auto; }

        .card {
            background:white;
            padding:20px;
            border:1px solid #d7eaff;
            border-radius:10px;
            margin-bottom:20px;
            display:flex; gap:18px;
        }

        .card img {
            width:80px; height:80px; border-radius:50%;
            object-fit:cover; border:3px solid #005bbb55;
        }

        .info { flex:1; }

        .info h2 { margin:0; color:#003e74; }

        .info p { margin:5px 0; color:#444; }

        .end-btn {
            padding:8px 15px;
            background:#ff3b30;
            color:white;
            border-radius:8px;
            text-decoration:none;
            height:fit-content;
        }

        .empty {
            text-align:center; color:#666; margin-top:40px; font-size:20px;
        }
    </style>
</head>

<body>

<div class="navbar">
    <img src="../medias/logo.png">
    <a class="login-btn" href="patientDashboard.php">Dashboard</a>
</div>

<h1>Your Prescriptions</h1>

<div class="container">

<?php if (count($pres) == 0): ?>
    <p class="empty">No prescriptions available.</p>

<?php else: ?>

    <?php foreach ($pres as $p): ?>
        <div class="card">

            <img src="../medias/drimages/<?php echo $p["Photo"]; ?>">

            <div class="info">
                <h2><?php echo $p["Medication_name"]; ?></h2>
                <p><strong>Doctor:</strong> Dr. <?php echo $p["First_name"] . " " . $p["Last_name"]; ?></p>
                <p><strong>Specialty:</strong> <?php echo $p["Specialty"]; ?></p>
                <p><strong>Dosage:</strong> <?php echo $p["Dosage"]; ?></p>
                <p><strong>Instructions:</strong> <?php echo $p["Instructions"]; ?></p>
                <p><strong>Date:</strong> <?php echo $p["Date_prescribed"]; ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($p["Status"]); ?></p>
            </div>

            <?php if ($p["Status"] === "active"): ?>
            <a href="cancelPrescription.php?id=<?php echo $p['Prescription_id']; ?>"
               class="end-btn"
               onclick="return confirm('Mark this prescription as completed?');">
                Mark Completed
            </a>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>
