<?php
session_start();
require "../Backend/dbconx.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front/login.php");
    exit();
}

$patient_id = $_SESSION["id"];

// Fetch all appointments
$stmt = $pdo->prepare("
    SELECT A.*, D.First_name, D.Last_name, D.Specialty, D.Photo
    FROM Appointment A
    JOIN Doctors D ON A.Doctor_id = D.Doctor_id
    WHERE A.Patient_id = ?
    ORDER BY A.Appointment_Date ASC, A.Appointment_time ASC
");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body { margin:0; font-family:'Poppins', sans-serif; background:#f7faff; }

        .navbar {
            display:flex; justify-content:space-between; align-items:center;
            padding:18px 60px; background:white; border-bottom:1px solid #e5e5e5;
        }
        .navbar img { width:44px; }
        .login-btn {
            background:#005bbb; color:white; padding:10px 22px; border-radius:6px; text-decoration:none;
        }

        h1 {
            text-align:center; margin-top:40px; color:#003e74; font-size:32px;
        }

        .appointments-container {
            width:80%; margin:30px auto;
        }

        .appointment-card {
            background:white;
            padding:20px;
            border:1px solid #d7eaff;
            border-radius:12px;
            margin-bottom:18px;
            display:flex;
            align-items:center;
            gap:20px;
        }

        .appointment-card img {
            width:90px; height:90px;
            border-radius:50%; object-fit:cover;
            border:3px solid #005bbb55;
        }

        .info { flex:1; }

        .info h2 {
            margin:0; color:#003e74; font-size:22px; font-weight:600;
        }

        .info p { margin:3px 0; color:#444; }

        .cancel-btn {
            background:#ff3b30;
            padding:10px 20px;
            color:white;
            border-radius:8px;
            text-decoration:none;
            font-weight:500;
        }

        .no-app {
            text-align:center;
            font-size:20px;
            color:#555;
            margin-top:50px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <img src="assets/logo.png">
<div >
    <a class="login-btn" href="../front/patientDashboard.php">Dashboard</a>
    <a class="login-btn" href="../booking/Booking-Department.php">Book Appointment</a>
    </div>
</div>

<h1>Your Appointments</h1>

<div class="appointments-container">

<?php if (count($appointments) == 0): ?>
    <p class="no-app">You have no appointments.</p>
<?php else: ?>

    <?php foreach ($appointments as $app): ?>
        <div class="appointment-card">
            <img src="../uploads/doctors/<?php echo $app['Photo']; ?>">

            <div class="info">
                <h2>Dr. <?php echo $app["First_name"] . " " . $app["Last_name"]; ?></h2>
                <p><?php echo $app["Specialty"]; ?></p>
                <p><strong>Date:</strong> <?php echo $app["Appointment_Date"]; ?></p>
                <p><strong>Time:</strong> <?php echo $app["Appointment_time"]; ?></p>
            </div>

            <a class="cancel-btn"
               href="../Backend/CancelAppointment.php?id=<?php echo $app['Appointment_id']; ?>"
               onclick="return confirm('Are you sure you want to cancel this appointment?');">
               Cancel
            </a>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>
