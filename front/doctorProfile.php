<?php
session_start();
require "../Backend/dbconx.php";

if (!isset($_GET["id"])) {
    die("Invalid doctor.");
}

$doctor_id = intval($_GET["id"]);

// Fetch doctor data
$stmt = $pdo->prepare("
    SELECT D.*, Dept.Department_name 
    FROM Doctors D 
    JOIN Department Dept ON D.Department_id = Dept.Department_id
    WHERE D.Doctor_id = ?
");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$doctor) {
    die("Doctor not found.");
}

// Fetch office hours
$stmtHours = $pdo->prepare("
    SELECT Weekday, Start_time, End_time 
    FROM Doctor_Office_Hours 
    WHERE Doctor_id = ?
    ORDER BY Weekday ASC
");
$stmtHours->execute([$doctor_id]);
$hours = $stmtHours->fetchAll(PDO::FETCH_ASSOC);

// Weekday names
$weekdayNames = ["0","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $doctor["First_name"]; ?> - Profile</title>
    <link rel="stylesheet" href="styling.css">
    <style>
        .profile-container {
            width: 85%;
            margin: 120px auto;
            display: flex;
            gap: 40px;
            align-items: flex-start;
            font-family: 'Poppins', sans-serif;
        }
        .profile-left img {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #0077ff;
        }
        .profile-right h2 { margin:0; font-size:32px; }
        .profile-right h3 { margin-top:8px; font-weight:500;color:#0077ff; }
        .office-hours-box {
            background:#f7f9ff;
            padding:15px;
            border-radius:10px;
            margin-top:20px;
        }
        .book-btn {
            display:inline-block;
            margin-top:25px;
            padding:12px 28px;
            background:#0077ff;
            color:white;
            border-radius:8px;
            text-decoration:none;
            font-weight:500;
        }
    </style>
</head>

<body>

<div class="profile-container">

    <div class="profile-left">
        <img src="../medias/drimages/<?php echo $doctor['Photo']; ?>" alt="">
    </div>

    <div class="profile-right">
        <h2><?php echo $doctor["First_name"] . " " . $doctor["Last_name"]; ?></h2>
        <h3><?php echo $doctor["Specialty"]; ?></h3>

        <p><strong>Department:</strong> <?php echo $doctor["Department_name"]; ?></p>
        <p><strong>Phone:</strong> <?php echo $doctor["Phone"]; ?></p>
        <p><strong>Email:</strong> <?php echo $doctor["Email"]; ?></p>

        <h3>About Doctor</h3>
        <p><?php echo $doctor["About"]; ?></p>

        <h3>Office Hours</h3>
        <div class="office-hours-box">
            <?php if (count($hours) > 0): ?>
                <?php foreach ($hours as $h): ?>
                    <p>
                        <strong><?php echo $weekdayNames[$h["Weekday"]]; ?>:</strong>
                        <?php echo substr($h["Start_time"],0,5) . " - " . substr($h["End_time"],0,5); ?>
                    </p>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No office hours available.</p>
            <?php endif; ?>
        </div>

        <a href="../booking/Booking-BookingPage.php?doctor_id=<?php echo $doctor_id; ?>" class="book-btn">Book Appointment</a>
    </div>

</div>

</body>
</html>
