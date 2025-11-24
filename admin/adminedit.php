<?php
session_start();
require "../Backend/dbConx.php";

// Only admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: adminLogin.php");
    exit();
}

// Get patient id from URL
if (!isset($_GET['id'])) {
    die("No patient id provided.");
}
$patient_id = (int) $_GET['id'];

// Fetch patient information
$stmt = $pdo->prepare("
    SELECT Patient_id, First_name, Last_name, Date_of_birth, Gender, Email, 
           Pre_existing_condition, Blood_type, Age
    FROM Patients
    WHERE Patient_id = ?
");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    die("Patient information not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Information - MediCare Hub</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body { margin:0; font-family:'Poppins', sans-serif; background:#f7faff; }

        .navbar {
            display:flex; justify-content:space-between;
            padding:18px 60px; background:white; border-bottom:1px solid #e5e5e5;
        }
        .navbar img { width:15%; }

        h1 {
            text-align:center; margin-top:40px;
            color:#003e74; font-size:32px;
        }

        .container {
            width:60%; margin:30px auto;
            background:white; padding:30px;
            border-radius:12px; border:1px solid #d7eaff;
        }

        .form-group {
            margin-bottom:20px;
        }

        label {
            font-weight:600; color:#003e74;
            display:block;
        }

        input, select {
            width:100%;
            padding:12px;
            margin-top:6px;
            border-radius:8px;
            border:1px solid #cfdfff;
            font-size:16px;
            box-sizing:border-box;
        }

        .save-btn {
            display:block; width:200px;
            padding:12px; margin:25px auto 0;
            background:#005bbb; color:white;
            text-align:center;
            border-radius:8px; font-size:18px;
            text-decoration:none;
            border:none; cursor:pointer;
        }
    </style>
</head>

<body>

<div class="navbar">
    <img src="../medias/logo.png">
    <a href="adminPatients.php" class="login-btn" style="background:#005bbb;color:white;padding:10px 22px;border-radius:8px;text-decoration:none;">
        Back
    </a>
</div>

<h1>Edit Patient Information</h1>

<div class="container">

    <form action="../Backend/adminEditAction.php" method="POST">

        <!-- 🔥 MUST-HAVE: send patient ID to backend -->
        <input type="hidden" name="patient_id" value="<?php echo $patient['Patient_id']; ?>">

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="fname" value="<?php echo $patient['First_name']; ?>" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lname" value="<?php echo $patient['Last_name']; ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $patient['Email']; ?>" required>
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?php echo $patient['Date_of_birth']; ?>" required>
        </div>

        <div class="form-group">
            <label>Age</label>
            <input type="number" value="<?php echo $patient['Age']; ?>" disabled>
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender">
                <option value="M" <?php echo $patient['Gender']=='M'?'selected':''; ?>>Male</option>
                <option value="F" <?php echo $patient['Gender']=='F'?'selected':''; ?>>Female</option>
            </select>
        </div>

        <div class="form-group">
            <label>Blood Type</label>
            <input type="text" name="blood" value="<?php echo $patient['Blood_type']; ?>">
        </div>

        <div class="form-group">
            <label>Pre-Existing Condition</label>
            <input type="text" name="condition" value="<?php echo $patient['Pre_existing_condition']; ?>">
        </div>

        <button type="submit" class="save-btn">Save Changes</button>

    </form>

</div>

</body>
</html>