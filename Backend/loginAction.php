<?php
session_start();
require "dbconx.php";//connection to the db is required for login

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $pass = trim($_POST["pass"]);

    // Detect doctor login
    $isDoctor = false;
    if (str_contains($email, "doctor") || str_contains($email, "@doctor.")) {
        $isDoctor = true;
    }

    // Choose correct table
    if ($isDoctor) {
        $sql = "SELECT * FROM Doctors WHERE Email = :email LIMIT 1";
    } else {
        $sql = "SELECT * FROM Patients WHERE Email = :email LIMIT 1";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // USER NOT FOUND
    if (!$user) {
        $_SESSION["error"] = "Invalid email or password.";
        header("Location: ../front/login.php");
        exit();
    }

    // PASSWORD CHECK
    if (!password_verify($pass, $user["Password"])) {
        $_SESSION["error"] = "Incorrect password.";
        header("Location: ../front/login.php");
        exit();
    }

    // STORE COMMON SESSION DATA
    $_SESSION["loggedIn"] = true;
    $_SESSION["email"] = $user["Email"];
    $_SESSION["UserName"] = $user["First_name"] . " " . $user["Last_name"];

    // DOCTOR LOGIN
    if ($isDoctor) {
        $_SESSION["role"] = "doctor";
        $_SESSION["doctor_id"] = $user["Doctor_id"];
        $_SESSION["id"] = $user["Doctor_id"] ;

        header("Location: ../front/doctorDashboard.php");
        exit();
    }

    // PATIENT LOGIN
    $_SESSION["role"] = "patient";
    $_SESSION["patient_id"] = $user["Patient_id"];
    $_SESSION["id"] = $user["Patient_id"];

    header("Location: ../front/patientDashboard.php");
    exit();
}
?>
