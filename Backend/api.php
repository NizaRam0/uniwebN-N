<?php
header("Content-Type: application/json");
require "dbconx.php";

if (!isset($_GET["type"])) {
    echo json_encode(["error" => "Missing type"]);
    exit();
}

$type = $_GET["type"];
$id = isset($_GET["id"]) ? intval($_GET["id"]) : null;

switch ($type) {
    case "taken_slots":
        if (!$id) { echo json_encode(["error" => "Missing doctor id"]); exit(); }
    
        $date = isset($_GET["date"]) ? $_GET["date"] : null;
    
        if (!$date) { echo json_encode(["error" => "Missing date"]); exit(); }
    
        $sql = "SELECT Appointment_time FROM Appointment
                WHERE Doctor_id = ? AND Appointment_Date = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $date]);
    
        echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
        break;
    

        // ===========================
// PRESCRIPTIONS FOR PATIENT
// ===========================
case "prescriptions":
    if (!$id) {
        echo json_encode(["error" => "Missing patient id"]);
        exit();
    }

    $sql = "SELECT P.*, D.First_name, D.Last_name, D.Specialty, D.Photo
            FROM Prescriptions P
            JOIN Doctors D ON P.Doctor_id = D.Doctor_id
            WHERE P.Patient_id = ?
            ORDER BY P.Date_prescribed DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    break;

  // ===========================
 // DOCTORS BY DEPARTMENT
// ===========================
case "doctors_by_department":
    if (!$id) {
        echo json_encode(["error" => "Missing department id"]);
        exit();
    }

    $sql = "SELECT Doctor_id, First_name, Last_name, Specialty, Photo 
            FROM Doctors 
            WHERE Department_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    break;

    // ===========================
// ALL DEPARTMENTS
// ===========================
case "departments":
    $sql = "SELECT Department_id, Department_name FROM Department";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    break;


    // ===========================
    // PATIENT INFO
    // ===========================
    case "patient":
        if (!$id) { echo json_encode(["error" => "Missing patient id"]); exit(); }

        $sql = "SELECT Patient_id, First_name, Last_name, Date_of_birth, Gender, Email,
                       Pre_existing_condition, Blood_type, Age
                FROM Patients WHERE Patient_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
        break;

    // ===========================
    // PATIENT APPOINTMENTS
    // ===========================
    case "appointments":
        if (!$id) { echo json_encode(["error" => "Missing patient id"]); exit(); }

        $sql = "SELECT A.*, D.First_name AS DoctorFirst, D.Last_name AS DoctorLast
                FROM Appointment A
                JOIN Doctors D ON A.Doctor_id = D.Doctor_id
                WHERE A.Patient_id = ?
                ORDER BY Appointment_Date DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode($stmt->fetchAll());
        break;

    // ===========================
    // MEDICAL TESTS
    // ===========================
    case "tests":
        if (!$id) { echo json_encode(["error" => "Missing patient id"]); exit(); }

        $sql = "SELECT T.*, D.First_name AS DoctorFirst, D.Last_name AS DoctorLast
                FROM Medical_Tests T
                JOIN Doctors D ON T.Doctor_id = D.Doctor_id
                WHERE T.Patient_id = ?
                ORDER BY Test_date DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode($stmt->fetchAll());
        break;

    // ===========================
    // MEDICAL RECORDS
    // ===========================
    case "records":
        if (!$id) { echo json_encode(["error" => "Missing patient id"]); exit(); }

        $sql = "SELECT * FROM Medical_Record
                WHERE Patient_id = ?
                ORDER BY Date DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode($stmt->fetchAll());
        break;

    // ===========================
    // DOCTOR INFO
    // ===========================
    case "doctor":
        if (!$id) { echo json_encode(["error" => "Missing doctor id"]); exit(); }

        $sql = "SELECT D.*, Dep.Department_name
                FROM Doctors D
                JOIN Department Dep ON Dep.Department_id = D.Department_id
                WHERE Doctor_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
        break;

    // ===========================
    // DOCTOR OFFICE HOURS
    // ===========================
    case "office_hours":
        if (!$id) { echo json_encode(["error" => "Missing doctor id"]); exit(); }

        $sql = "SELECT * FROM Doctor_Office_Hours
                WHERE Doctor_id = ?
                ORDER BY Weekday ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode($stmt->fetchAll());
        break;

    // ===========================
    // DEPARTMENT INFO
    // ===========================
    case "department":
        if (!$id) { echo json_encode(["error" => "Missing department id"]); exit(); }

        $sql = "SELECT * FROM Department WHERE Department_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode($stmt->fetch());
        break;

    default:
        echo json_encode(["error" => "Unknown type"]);
        break;
}
?>
