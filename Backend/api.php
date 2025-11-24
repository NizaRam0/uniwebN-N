<?php
header("Content-Type: application/json"); //make sure to set content type to json so it this page only returns json data
require "dbconx.php"; //doesnot run if dbconx.php(cinnection to the db) fails

if (!isset($_GET["type"])) {
    echo json_encode(["error" => "Missing type"]);//if the type of the request is not set, return an error
    exit();
}

$type = $_GET["type"];
$id = isset($_GET["id"]) ? intval($_GET["id"]) : null;


switch ($type) {
// ===========================
// doctors time slots
// ===========================
    case "taken_slots":
        if (!$id) { echo json_encode(["error" => "Missing doctor id"]); exit(); }
        // check if doctor id is provided 

    
        $date = isset($_GET["date"]) ? $_GET["date"] : null;
    
        if (!$date) { echo json_encode(["error" => "Missing date"]); exit(); }
        // check if appointment id is provided 
    
        $sql = "SELECT Appointment_time FROM Appointment
                WHERE Doctor_id = ? AND Appointment_Date = ?";
        $stmt = $pdo->prepare($sql);  
        /*It takes your SQL and prepares it so PHP can bind values safely it prevents:
        SQL injection
        invalid SQL
        unsafe string concatenation*/
        $stmt->execute([$id, $date]); 
        /*It sends the final SQL to MySQL with the values inserted:
        The first ? becomes $id
        The second ? becomes $date*/

        echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
        break;
// Fetch all results from the query as a simple array containing only the Appointment_time column
// PDO::FETCH_COLUMN ensures we only retrieve the first column from each row
// Convert the PHP array into JSON format so it can be used by JavaScript on the frontend
// Echo sends the JSON response back to the client
    

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
    $sql = "SELECT Department_id, Department_name, Photo FROM Department";
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

        $sql = "SELECT T.*, D.First_name AS DoctorFirst, D.Last_name AS DoctorLast /*get doctor name for each test */
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
                JOIN Department Dep ON Dep.Department_id = D.Department_id /*we use join to get department name from department table*/
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
