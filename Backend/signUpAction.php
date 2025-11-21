<?php
session_start();
require "dbconx.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fname = trim($_POST["fname"]);
    $lname = trim($_POST["lname"]);
    $dob   = trim($_POST["dob"]);
    $gender = trim($_POST["gender"]);
    $email  = trim($_POST["email"]);
    $pass   = trim($_POST["pass"]);
    $cond   = trim($_POST["cond"]);
    $blood  = trim($_POST["blood"]);

    // Basic validation
    if (empty($fname) || empty($lname) || empty($dob) || empty($gender) || empty($email) || empty($pass) || empty($blood)) {
        $_SESSION["error"] = "Please fill in all required fields.";
        header("Location: ../front/register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error"] = "Invalid email format.";
        header("Location: ../front/register.php");
        exit();
    }

    // Calculate age automatically
    $today = new DateTime();
    $birthdate = new DateTime($dob);
    $age = $today->diff($birthdate)->y;

    // Hash password
    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

    try {

        // Insert into Patients
        $sql = "INSERT INTO Patients (First_name, Last_name, Date_of_birth, Gender, Email, Pre_existing_condition, Blood_type, Age, Password)
                VALUES (:fname, :lname, :dob, :gender, :email, :cond, :blood, :age, :pass)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":fname", $fname);
        $stmt->bindParam(":lname", $lname);
        $stmt->bindParam(":dob", $dob);
        $stmt->bindParam(":gender", $gender);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":cond", $cond);
        $stmt->bindParam(":blood", $blood);
        $stmt->bindParam(":age", $age);
        $stmt->bindParam(":pass", $hashedPass);

        $stmt->execute();

        // Redirect to login
        $_SESSION["success"] = "Account created successfully. Please login.";
        header("Location: ../front/login.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION["error"] = "Error creating account: " . $e->getMessage();
        header("Location: ../front/register.php");
        exit();
    }
}
?>
