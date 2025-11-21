<?php
session_start();
header("Content-Type: application/json");
require "dbconx.php";

// MUST be logged in as doctor
if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "doctor") {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$doctor_id = $_SESSION["id"]; // logged-in doctor
$type = $_GET["type"] ?? null;

switch ($type) {

    /* ---------------------------------------------------------
       1. Doctor Info
    ---------------------------------------------------------- */
    case "doctor_info":
        $stmt = $pdo->prepare("SELECT * FROM Doctors WHERE Doctor_id = ?");
        $stmt->execute([$doctor_id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        break;


    /* ---------------------------------------------------------
       2. Today's Appointments
    ---------------------------------------------------------- */
    case "today_appointments":
        $stmt = $pdo->prepare("
            SELECT A.*, P.First_name, P.Last_name, P.Date_of_birth
            FROM Appointment A
            JOIN Patients P ON A.Patient_id = P.Patient_id
            WHERE A.Doctor_id = ? AND A.Appointment_Date = CURDATE()
            ORDER BY A.Appointment_time ASC
        ");
        $stmt->execute([$doctor_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;


    /* ---------------------------------------------------------
       3. Upcoming Appointments (Next 7 days)
    ---------------------------------------------------------- */
    case "upcoming_appointments":
        $stmt = $pdo->prepare("
            SELECT A.*, P.First_name, P.Last_name
            FROM Appointment A
            JOIN Patients P ON A.Patient_id = P.Patient_id
            WHERE A.Doctor_id = ?
              AND A.Appointment_Date >= CURDATE()
            ORDER BY A.Appointment_Date, A.Appointment_time
        ");
        $stmt->execute([$doctor_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;


    /* ---------------------------------------------------------
       3b. Update Appointment Status (Completed / Cancelled)
    ---------------------------------------------------------- */
    case "update_appointment_status":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["error" => "POST required"]);
            exit();
        }

        $appointment_id = $_POST["appointment_id"] ?? null;
        $status = $_POST["status"] ?? null;

        if (!$appointment_id || !$status) {
            echo json_encode(["error" => "Missing fields"]);
            exit();
        }

        $allowed = ["Scheduled", "Completed", "Cancelled"];
        if (!in_array($status, $allowed)) {
            echo json_encode(["error" => "Invalid status"]);
            exit();
        }

        $stmt = $pdo->prepare("
            UPDATE Appointment
            SET Status = ?
            WHERE Appointment_id = ? AND Doctor_id = ?
        ");
        $stmt->execute([$status, $appointment_id, $doctor_id]);

        echo json_encode(["success" => true]);
        break;


    /* ---------------------------------------------------------
       4. Search Patients (name / DOB)
    ---------------------------------------------------------- */
    case "search_patient":
        $query = "%" . ($_GET["q"] ?? "") . "%";

        $stmt = $pdo->prepare("
            SELECT *
            FROM Patients
            WHERE First_name LIKE ?
               OR Last_name LIKE ?
               OR Date_of_birth LIKE ?
        ");
        $stmt->execute([$query, $query, $query]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;


    /* ---------------------------------------------------------
       4b. Patients for this doctor
    ---------------------------------------------------------- */
    case "doctor_patients":
        $stmt = $pdo->prepare("
            SELECT DISTINCT P.*
            FROM Appointment A
            JOIN Patients P ON A.Patient_id = P.Patient_id
            WHERE A.Doctor_id = ?
            ORDER BY P.First_name, P.Last_name
        ");
        $stmt->execute([$doctor_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;


    /* ---------------------------------------------------------
       4c. Single Patient Profile
    ---------------------------------------------------------- */
    case "patient_profile":
        $patient_id = $_GET["patient_id"] ?? null;
        if (!$patient_id) {
            echo json_encode(["error" => "Missing patient_id"]);
            exit();
        }

        $stmt = $pdo->prepare("SELECT * FROM Patients WHERE Patient_id = ?");
        $stmt->execute([$patient_id]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        break;


    /* ---------------------------------------------------------
       5. Add Medical Note (stored in Medical_Record)
    ---------------------------------------------------------- */
    case "add_note":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["error" => "POST required"]);
            exit();
        }

        $patient_id = $_POST["patient_id"] ?? null;
        $note = trim($_POST["note"] ?? "");

        if (!$patient_id || $note === "") {
            echo json_encode(["error" => "Missing fields"]);
            exit();
        }

        // Minimal record, diagnosis/treatment left empty
        $stmt = $pdo->prepare("
            INSERT INTO Medical_Record (Date, Diagnosis, Treatement, Notes, Patient_id)
            VALUES (CURDATE(), '', '', ?, ?)
        ");
        $stmt->execute([$note, $patient_id]);

        echo json_encode(["success" => true]);
        break;


    /* ---------------------------------------------------------
       5b. Get Medical Records of Patient
    ---------------------------------------------------------- */
    case "patient_records":
        $patient_id = $_GET["patient_id"] ?? null;

        if (!$patient_id) {
            echo json_encode(["error" => "Missing patient_id"]);
            exit();
        }

        $stmt = $pdo->prepare("
            SELECT * FROM Medical_Record
            WHERE Patient_id = ?
            ORDER BY Date DESC
        ");
        $stmt->execute([$patient_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;



    /* ---------------------------------------------------------
       6. Add Medication (Prescriptions table)
    ---------------------------------------------------------- */
    case "add_medication":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["error" => "POST required"]);
            exit();
        }

        $patient_id = $_POST["patient_id"] ?? null;
        $name = trim($_POST["name"] ?? "");
        $dose = trim($_POST["dose"] ?? "");
        $instructions = trim($_POST["instructions"] ?? "");

        if (!$patient_id || $name === "" || $dose === "") {
            echo json_encode(["error" => "Missing fields"]);
            exit();
        }

        $stmt = $pdo->prepare("
            INSERT INTO Prescriptions 
            (Patient_id, Doctor_id, Medication_name, Dosage, Instructions, Date_prescribed, Status)
            VALUES (?, ?, ?, ?, ?, CURDATE(), 'active')
        ");
        $stmt->execute([$patient_id, $doctor_id, $name, $dose, $instructions]);

        echo json_encode(["success" => true]);
        break;


    /* ---------------------------------------------------------
       6b. Get Patient Medications
    ---------------------------------------------------------- */
    case "patient_medications":
        $patient_id = $_GET["patient_id"] ?? null;

        if (!$patient_id) {
            echo json_encode(["error" => "Missing patient_id"]);
            exit();
        }

        $stmt = $pdo->prepare("
            SELECT * FROM Prescriptions
            WHERE Patient_id = ?
            ORDER BY Date_prescribed DESC
        ");
        $stmt->execute([$patient_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;


    /* ---------------------------------------------------------
       7. Add Test Result (with optional file upload)
    ---------------------------------------------------------- */
    case "add_test":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["error" => "POST required"]);
            exit();
        }

        $patient_id = $_POST["patient_id"] ?? null;
        $test_name = trim($_POST["test_name"] ?? "");
        $result = trim($_POST["result"] ?? "");
        $doctor_report = trim($_POST["doctor_report"] ?? "");

        if (!$patient_id || $test_name === "") {
            echo json_encode(["error" => "Missing fields"]);
            exit();
        }

        $attachmentPath = null;

        if (!empty($_FILES["attachment"]["name"])) {
            $uploadDir = __DIR__ . "/../uploads/tests/";
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . "_" . basename($_FILES["attachment"]["name"]);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetPath)) {
                // Path to store in DB (relative)
                $attachmentPath = "uploads/tests/" . $fileName;
            }
        }

        $stmt = $pdo->prepare("
            INSERT INTO Medical_Tests 
            (Patient_id, Doctor_id, Test_name, Test_date, Result, Doctor_Report, Attachment)
            VALUES (?, ?, ?, CURDATE(), ?, ?, ?)
        ");
        $stmt->execute([$patient_id, $doctor_id, $test_name, $result, $doctor_report, $attachmentPath]);

        echo json_encode(["success" => true]);
        break;


    /* ---------------------------------------------------------
       8. Get Patient Tests
    ---------------------------------------------------------- */
    case "patient_tests":
        $patient_id = $_GET["patient_id"] ?? null;

        if (!$patient_id) {
            echo json_encode(["error" => "Missing patient_id"]);
            exit();
        }

        $stmt = $pdo->prepare("
            SELECT * FROM Medical_Tests
            WHERE Patient_id = ?
            ORDER BY Test_date DESC
        ");
        $stmt->execute([$patient_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;


    /* ---------------------------------------------------------
       9. Tests for this doctor (pending)
    ---------------------------------------------------------- */
    case "pending_tests":
        $stmt = $pdo->prepare("
            SELECT MT.*, P.First_name, P.Last_name
            FROM Medical_Tests MT
            JOIN Patients P ON MT.Patient_id = P.Patient_id
            WHERE MT.Doctor_id = ?
              AND (MT.Doctor_Report IS NULL OR MT.Doctor_Report = '')
            ORDER BY MT.Test_date DESC
        ");
        $stmt->execute([$doctor_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;


    /* ---------------------------------------------------------
       9b. All tests for this doctor
    ---------------------------------------------------------- */
    case "all_tests":
        $stmt = $pdo->prepare("
            SELECT MT.*, P.First_name, P.Last_name
            FROM Medical_Tests MT
            JOIN Patients P ON MT.Patient_id = P.Patient_id
            WHERE MT.Doctor_id = ?
            ORDER BY MT.Test_date DESC
        ");
        $stmt->execute([$doctor_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;


    /* ---------------------------------------------------------
       9c. Update Doctor Report for a test
    ---------------------------------------------------------- */
    case "update_test_report":
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["error" => "POST required"]);
            exit();
        }

        $test_id = $_POST["test_id"] ?? null;
        $report = trim($_POST["doctor_report"] ?? "");

        if (!$test_id) {
            echo json_encode(["error" => "Missing test_id"]);
            exit();
        }

        $stmt = $pdo->prepare("
            UPDATE Medical_Tests
            SET Doctor_Report = ?
            WHERE Test_id = ? AND Doctor_id = ?
        ");
        $stmt->execute([$report, $test_id, $doctor_id]);

        echo json_encode(["success" => true]);
        break;


    /* ---------------------------------------------------------
       Invalid Request
    ---------------------------------------------------------- */
    default:
        echo json_encode(["error" => "Invalid API request"]);
        break;
}
