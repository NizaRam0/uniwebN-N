<?php
require "dbconx.php";

if (!isset($_GET['dept'])) {
    die("No department selected.");
}

$dept_id = intval($_GET['dept']);

// fetch department info
$stmt = $pdo->prepare("SELECT * FROM departments WHERE id = ?");
$stmt->execute([$dept_id]);
$department = $stmt->fetch(PDO::FETCH_ASSOC);

// fetch doctors of this department
$stmt2 = $pdo->prepare("SELECT * FROM doctors WHERE department_id = ?");
$stmt2->execute([$dept_id]);
$doctors = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title><?= $department['name'] ?> Department</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1 class="page-title"><?= $department['name'] ?> Department</h1>

<div class="doctors-container">

<?php foreach ($doctors as $doc): ?>
    <div class="doctor-card">
        <img src="img/<?= $doc['photo'] ?>" alt="">
        <h2><?= $doc['name'] ?></h2>
        <h4><?= $department['name'] ?></h4>

        <p class="about-doc"><?= $doc['about'] ?></p>

        <div class="contact-box">
            <p><strong>Email:</strong> <?= $doc['email'] ?></p>
            <p><strong>Phone:</strong> <?= $doc['phone'] ?></p>
            <p><strong>Office Hours:</strong> <?= $doc['office_hours'] ?></p>
        </div>

        <a class="book-btn" href="booking.php?doctor=<?= $doc['id'] ?>">Book Appointment</a>
    </div>
<?php endforeach; ?>

</div>

</body>
</html>
