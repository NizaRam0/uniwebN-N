/<*?php
require "dbconx.php";

$stmt = $pdo->query("SELECT * FROM departments");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Departments</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1 class="page-title">Our Medical Departments</h1>

<div class="departments-container">

<?php foreach ($departments as $dept): ?>
    <a class="dept-card" href="doctors.php?dept=<?= $dept['id'] ?>">
        <img src="img/<?= strtolower($dept['name']) ?>.png" alt="">
        <h3><?= $dept['name'] ?></h3>
        <p><?= $dept['description'] ?></p>
    </a>
<?php endforeach; ?>

</div>

</body>
</html>
