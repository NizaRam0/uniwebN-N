<?php
session_start();
require "../Backend/dbconx.php";

// Check login
$isLoggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;

// Fetch departments
$stmtDept = $pdo->prepare("SELECT * FROM Department ORDER BY Department_name ASC");
$stmtDept->execute();
$departments = $stmtDept->fetchAll(PDO::FETCH_ASSOC);

// Fetch doctors (limit 6)
$stmtDoc = $pdo->prepare("SELECT Doctor_id, First_name, Last_name, Specialty, Photo FROM Doctors LIMIT 6");
$stmtDoc->execute();
$doctors = $stmtDoc->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hub - Home</title>
    <link rel="stylesheet" href="styling.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">
        <img src="../medias/logo.png" alt="Logo">
        <span>MediCare Hub</span>
    </div>

    <div class="nav-links">
        <a href="#home" class="nav-link">Home</a>
        <a href="#about" class="nav-link">About</a>
        <a href="#departments" class="nav-link">Departments</a>
        <a href="#team" class="nav-link">Our Team</a>
    </div>

    <?php if ($isLoggedIn): ?>
        <a class="login-btn" href="patientDashboard.php">Dashboard</a>
    <?php else: ?>
        <a class="login-btn" href="login.php">Log In</a>
    <?php endif; ?>
</div>

<!-- HERO SECTION -->
<div class="hero" id="home">
    <div class="hero-text">
        <h1>Quality Healthcare Made Simple</h1>
        <p>Easily book appointments, view your medical records, and connect with doctors—online and fast.</p>
        <a href="../booking/Booking-Department.php">Book Appointment</a>
    </div>

    <div class="hero-image">
        <img src="../medias/dr.png" alt="Doctor Illustration">
    </div>
</div>

<!-- FEATURES SECTION (ICONS YOU WANTED BACK) -->
<div class="features">
    <div class="feature-box">
        <img src="../medias/calendar.png">
        <h3>Appointments</h3>
        <p>Schedule visits with your doctors with just a few clicks.</p>
    </div>

    <div class="feature-box">
        <img src="../medias/report.png">
        <h3>Medical Records</h3>
        <p>Securely access your medical history at any time.</p>
    </div>

    <div class="feature-box">
        <img src="../medias/drsicon.png">
        <h3>Our Doctors</h3>
        <p>Meet our team of experienced healthcare professionals.</p>
    </div>
</div>

<!-- ABOUT SECTION -->
<div class="about" id="about">
    <h2>About Us</h2>
    <p>
        MediCare Hub is a modern digital healthcare platform designed to simplify your medical experience.
        From booking appointments to accessing your medical history, we bring everything into a clean,
        easy-to-use interface that connects patients with doctors seamlessly.
    </p>
</div>

<!-- DEPARTMENTS (FROM DATABASE) -->
<div class="about" id="departments" style="background:#ffffff;">
    <h2 style="margin-bottom:20px;">Departments</h2>

    <div style="display:flex; flex-wrap:wrap; gap:25px; justify-content: space-around;">
        <?php foreach ($departments as $d): ?>
            <div class="dash-card" style="width:32%; text-align:center;">
                <h3><?php echo htmlspecialchars($d['Department_name']); ?></h3>
                <p><?php echo htmlspecialchars($d['Description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- OUR TEAM (TOP DOCTORS FROM DB) -->
<div class="about" id="team">
    <h2>Our Team</h2>

    <div style="display:flex; flex-wrap:wrap; gap:25px; justify-content: space-around;">
        <?php foreach ($doctors as $doc): ?>
            <div class="dash-card" >
                <img src="../medias/drimages/<?php echo $doc['Photo']; ?>" 
                     style="width:110px; height:110px; border-radius:50%; object-fit:cover; margin-bottom:12px;">
                <h3><?php echo $doc['First_name'] . " " . $doc['Last_name']; ?></h3>
                <p ><?php echo $doc['Specialty']; ?></p>
                <a href="doctorProfile.php?id=<?php echo $doc['Doctor_id']; ?>" class="btn-small">View Profile</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2025 MediCare Hub — All Rights Reserved
</div>

<!-- SMOOTH SCROLL + ACTIVE LINK JS -->
<script>
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        const target = document.querySelector(link.getAttribute('href'));
        target.scrollIntoView({ behavior: "smooth" });
    });
});

const sections = document.querySelectorAll('#home, #about, #departments, #team');
const navLinks = document.querySelectorAll('.nav-link');

window.addEventListener('scroll', () => {
    let current = "";
    sections.forEach(sec => {
        const top = sec.offsetTop - 120;
        const height = sec.clientHeight;
        if (scrollY >= top && scrollY < top + height) {
            current = sec.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
            link.classList.add('active');
        }
    });
});
</script>

</body>
</html>
