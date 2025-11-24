-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 22, 2025 at 01:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medical_Hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `Appointment`
--

CREATE TABLE `Appointment` (
  `Appointment_id` int(11) NOT NULL,
  `Appointment_Date` date NOT NULL,
  `Reason` varchar(500) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `Patient_id` int(11) NOT NULL,
  `Doctor_id` int(11) NOT NULL,
  `Appointment_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Appointment`
--

INSERT INTO `Appointment` (`Appointment_id`, `Appointment_Date`, `Reason`, `Status`, `Patient_id`, `Doctor_id`, `Appointment_time`) VALUES
(3, '2025-11-25', '', 'Scheduled', 4, 2, '13:00:00'),
(4, '2025-11-27', '', 'Scheduled', 1, 2, '15:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `Department`
--

CREATE TABLE `Department` (
  `Department_id` int(11) NOT NULL,
  `Department_name` varchar(100) NOT NULL,
  `Department_description` text DEFAULT NULL,
  `Chief_of_Department` varchar(100) NOT NULL,
  `Photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Department`
--

INSERT INTO `Department` (`Department_id`, `Department_name`, `Department_description`, `Chief_of_Department`, `Photo`) VALUES
(1, 'Cardiology', 'Heart and cardiovascular diseases.', 'Dr. Sarah Nadim', 'cardiology.png'),
(2, 'Neurology', 'Nervous system & brain related issues.', 'Dr. Ahmed Tarek', 'neurology.png'),
(3, 'Pediatrics', 'Child healthcare & development.', 'Dr. Jana Hatem', 'pediatrics.png'),
(4, 'Orthopedics', 'Bones, muscles, and joints.', 'Dr. Ali Mansour', 'orthopedics.png');

-- --------------------------------------------------------

--
-- Table structure for table `Doctors`
--

CREATE TABLE `Doctors` (
  `Doctor_id` int(11) NOT NULL,
  `First_name` varchar(100) NOT NULL,
  `Last_name` varchar(100) NOT NULL,
  `Specialty` varchar(100) NOT NULL,
  `Phone` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Photo` varchar(255) DEFAULT NULL,
  `About` text DEFAULT NULL,
  `Department_id` int(11) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Doctors`
--

INSERT INTO `Doctors` (`Doctor_id`, `First_name`, `Last_name`, `Specialty`, `Phone`, `Email`, `Photo`, `About`, `Department_id`, `Password`) VALUES
(1, 'Rami', 'Khoury', 'Cardiologist', '70123456', 'rami.khoury@doctor.com', 'rami_khoury.png', 'Expert in heart disease and hypertension.', 1, '$2y$10$uve3VAXyjT2dW7o5osOKHeGZq7kWPuARb5bO4XzKkF4OxPS/2pPCG'),
(2, 'Sara', 'Bazzi', 'Neurologist', '70765432', 'sara.bazzi@doctor.com', 'sara_bazzi.png', 'Specialist in brain and nerve disorders.', 2, '$2y$10$uve3VAXyjT2dW7o5osOKHeGZq7kWPuARb5bO4XzKkF4OxPS/2pPCG'),
(3, 'Nada', 'Hassan', 'Pediatrician', '70987654', 'nada.hassan@doctor.com', 'nada_hassan.png', 'Caring pediatric doctor for children.', 3, '$2y$10$uve3VAXyjT2dW7o5osOKHeGZq7kWPuARb5bO4XzKkF4OxPS/2pPCG'),
(4, 'Karim', 'Mansour', 'Orthopedic Surgeon', '71321555', 'karim.mansour@doctor.com', 'karim_mansour.png', 'Expert in bone fractures and mobility.', 4, '$2y$10$uve3VAXyjT2dW7o5osOKHeGZq7kWPuARb5bO4XzKkF4OxPS/2pPCG');

-- --------------------------------------------------------

--
-- Table structure for table `Doctor_Office_Hours`
--

CREATE TABLE `Doctor_Office_Hours` (
  `id` int(11) NOT NULL,
  `Doctor_id` int(11) NOT NULL,
  `Weekday` tinyint(4) NOT NULL,
  `Start_time` time NOT NULL,
  `End_time` time NOT NULL,
  `Slot_length` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Doctor_Office_Hours`
--

INSERT INTO `Doctor_Office_Hours` (`id`, `Doctor_id`, `Weekday`, `Start_time`, `End_time`, `Slot_length`) VALUES
(1, 1, 1, '09:00:00', '12:00:00', 30),
(2, 1, 3, '10:00:00', '14:00:00', 30),
(3, 1, 5, '09:00:00', '13:00:00', 30),
(4, 2, 2, '08:30:00', '12:30:00', 30),
(5, 2, 4, '09:00:00', '15:00:00', 30),
(6, 3, 1, '08:00:00', '13:00:00', 30),
(7, 3, 3, '10:00:00', '17:00:00', 30),
(8, 4, 2, '11:00:00', '16:00:00', 30),
(9, 4, 4, '09:00:00', '12:00:00', 30),
(10, 4, 6, '10:00:00', '14:00:00', 30);

-- --------------------------------------------------------

--
-- Table structure for table `Medical_Record`
--

CREATE TABLE `Medical_Record` (
  `Record_id` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Diagnosis` varchar(500) NOT NULL,
  `Treatement` varchar(500) NOT NULL,
  `Notes` varchar(500) NOT NULL,
  `Patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Medical_Record`
--

INSERT INTO `Medical_Record` (`Record_id`, `Date`, `Diagnosis`, `Treatement`, `Notes`, `Patient_id`) VALUES
(1, '2025-01-03', 'Seasonal Asthma Attack', 'Inhaler + Nebulizer', 'Patient responded well, follow-up in 2 weeks.', 1),
(2, '2025-02-10', 'Flu Infection', 'Antibiotics + Rest', 'Fever decreased after medication.', 2),
(3, '2025-03-15', 'Chest Pain Evaluation', 'ECG + Blood test', 'ECG normal, symptoms mild.', 1),
(4, '2025-04-12', 'Sprained Ankle', 'Ice + Support Bandage', 'Patient advised to avoid sports for 7 days.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `Medical_Tests`
--

CREATE TABLE `Medical_Tests` (
  `Test_id` int(11) NOT NULL,
  `Patient_id` int(11) NOT NULL,
  `Doctor_id` int(11) NOT NULL,
  `Test_name` varchar(200) NOT NULL,
  `Test_date` date NOT NULL,
  `Result` text DEFAULT NULL,
  `Doctor_Report` text DEFAULT NULL,
  `Attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Medical_Tests`
--

INSERT INTO `Medical_Tests` (`Test_id`, `Patient_id`, `Doctor_id`, `Test_name`, `Test_date`, `Result`, `Doctor_Report`, `Attachment`) VALUES
(1, 1, 1, 'ECG Test', '2025-03-15', 'Normal sinus rhythm', 'No abnormalities.', 'ecg_nizar.pdf'),
(2, 2, 3, 'Blood Test - CBC', '2025-01-20', 'All values normal', 'Good health indicators overall.', 'cbc_noor.pdf'),
(3, 1, 2, 'MRI Brain Scan', '2025-02-01', 'No lesions observed', 'Neuro exam recommended annually.', 'mri_brain_nizar.pdf'),
(4, 2, 4, 'X-Ray Ankle', '2025-04-12', 'Mild swelling, no fracture', 'Continue resting for 1 week.', 'xray_noor.png');

-- --------------------------------------------------------

--
-- Table structure for table `Nurse`
--

CREATE TABLE `Nurse` (
  `Nurse_id` int(11) NOT NULL COMMENT 'foreign',
  `First_name` varchar(100) NOT NULL,
  `Last_name` varchar(100) NOT NULL,
  `Phone` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Nurse`
--

INSERT INTO `Nurse` (`Nurse_id`, `First_name`, `Last_name`, `Phone`, `Email`, `Department_id`) VALUES
(1, 'Maya', 'Tannous', '71011223', 'maya.tannous@example.com', 1),
(2, 'Lana', 'Sayegh', '76789432', 'lana.sayegh@example.com', 2),
(3, 'Hadi', 'Iskandar', '78933221', 'hadi.iskandar@example.com', 3),
(4, 'Rita', 'Nasr', '71556677', 'rita.nasr@example.com', 4);

-- --------------------------------------------------------

--
-- Table structure for table `Patients`
--

CREATE TABLE `Patients` (
  `Patient_id` int(11) NOT NULL,
  `First_name` varchar(100) NOT NULL,
  `Last_name` varchar(100) NOT NULL,
  `Date_of_birth` date NOT NULL,
  `Gender` varchar(1) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Pre_existing_condition` varchar(500) NOT NULL,
  `Blood_type` varchar(3) NOT NULL,
  `Age` int(3) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Patients`
--

INSERT INTO `Patients` (`Patient_id`, `First_name`, `Last_name`, `Date_of_birth`, `Gender`, `Email`, `Pre_existing_condition`, `Blood_type`, `Age`, `Password`) VALUES
(1, 'Nizar', 'Ramadan', '2007-01-25', 'M', 'nizar@example.com', 'Asthma', 'A+', 18, '$2y$10$uve3VAXyjT2dW7o5osOKHeGZq7kWPuARb5bO4XzKkF4OxPS/2pPCG'),
(2, 'Noor', 'Hamadi', '2007-01-25', 'F', 'noorHamadi@example.com', 'None', 'A+', 18, ''),
(4, 'N', 'H', '2025-11-14', 'F', 'elnizarran@gmail.com', 'None', 'A+', 0, '$2y$10$uve3VAXyjT2dW7o5osOKHeGZq7kWPuARb5bO4XzKkF4OxPS/2pPCG');

-- --------------------------------------------------------

--
-- Table structure for table `Prescriptions`
--

CREATE TABLE `Prescriptions` (
  `Prescription_id` int(11) NOT NULL,
  `Patient_id` int(11) NOT NULL,
  `Doctor_id` int(11) NOT NULL,
  `Medication_name` varchar(200) NOT NULL,
  `Dosage` varchar(100) NOT NULL,
  `Instructions` text NOT NULL,
  `Date_prescribed` date NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Prescriptions`
--

INSERT INTO `Prescriptions` (`Prescription_id`, `Patient_id`, `Doctor_id`, `Medication_name`, `Dosage`, `Instructions`, `Date_prescribed`, `Status`) VALUES
(1, 1, 1, 'NNN', '2mm', '2times per day', '2025-11-21', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD PRIMARY KEY (`Appointment_id`),
  ADD UNIQUE KEY `unique_slot` (`Doctor_id`,`Appointment_Date`,`Appointment_time`),
  ADD KEY `fk_appointment_patient` (`Patient_id`);

--
-- Indexes for table `Department`
--
ALTER TABLE `Department`
  ADD PRIMARY KEY (`Department_id`);

--
-- Indexes for table `Doctors`
--
ALTER TABLE `Doctors`
  ADD PRIMARY KEY (`Doctor_id`),
  ADD KEY `fk_department_id` (`Department_id`);

--
-- Indexes for table `Doctor_Office_Hours`
--
ALTER TABLE `Doctor_Office_Hours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Doctor_id` (`Doctor_id`);

--
-- Indexes for table `Medical_Record`
--
ALTER TABLE `Medical_Record`
  ADD PRIMARY KEY (`Record_id`),
  ADD KEY `fk_Patient_id` (`Patient_id`);

--
-- Indexes for table `Medical_Tests`
--
ALTER TABLE `Medical_Tests`
  ADD PRIMARY KEY (`Test_id`),
  ADD KEY `Patient_id` (`Patient_id`),
  ADD KEY `Doctor_id` (`Doctor_id`);

--
-- Indexes for table `Nurse`
--
ALTER TABLE `Nurse`
  ADD PRIMARY KEY (`Nurse_id`);

--
-- Indexes for table `Patients`
--
ALTER TABLE `Patients`
  ADD PRIMARY KEY (`Patient_id`);

--
-- Indexes for table `Prescriptions`
--
ALTER TABLE `Prescriptions`
  ADD PRIMARY KEY (`Prescription_id`),
  ADD KEY `Patient_id` (`Patient_id`),
  ADD KEY `Doctor_id` (`Doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Appointment`
--
ALTER TABLE `Appointment`
  MODIFY `Appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Department`
--
ALTER TABLE `Department`
  MODIFY `Department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `Doctors`
--
ALTER TABLE `Doctors`
  MODIFY `Doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `Doctor_Office_Hours`
--
ALTER TABLE `Doctor_Office_Hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Medical_Record`
--
ALTER TABLE `Medical_Record`
  MODIFY `Record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Medical_Tests`
--
ALTER TABLE `Medical_Tests`
  MODIFY `Test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Nurse`
--
ALTER TABLE `Nurse`
  MODIFY `Nurse_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'foreign', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Patients`
--
ALTER TABLE `Patients`
  MODIFY `Patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Prescriptions`
--
ALTER TABLE `Prescriptions`
  MODIFY `Prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Appointment`
--
ALTER TABLE `Appointment`
  ADD CONSTRAINT `fk_appointment_doctor` FOREIGN KEY (`Doctor_id`) REFERENCES `Doctors` (`Doctor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_appointment_patient` FOREIGN KEY (`Patient_id`) REFERENCES `Patients` (`Patient_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Doctors`
--
ALTER TABLE `Doctors`
  ADD CONSTRAINT `fk_department_id` FOREIGN KEY (`Department_id`) REFERENCES `Department` (`Department_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Doctor_Office_Hours`
--
ALTER TABLE `Doctor_Office_Hours`
  ADD CONSTRAINT `doctor_office_hours_ibfk_1` FOREIGN KEY (`Doctor_id`) REFERENCES `Doctors` (`Doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `Medical_Record`
--
ALTER TABLE `Medical_Record`
  ADD CONSTRAINT `fk_Patient_id` FOREIGN KEY (`Patient_id`) REFERENCES `Patients` (`Patient_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Medical_Tests`
--
ALTER TABLE `Medical_Tests`
  ADD CONSTRAINT `medical_tests_ibfk_1` FOREIGN KEY (`Patient_id`) REFERENCES `Patients` (`Patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medical_tests_ibfk_2` FOREIGN KEY (`Doctor_id`) REFERENCES `Doctors` (`Doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `Prescriptions`
--
ALTER TABLE `Prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`Patient_id`) REFERENCES `Patients` (`Patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`Doctor_id`) REFERENCES `Doctors` (`Doctor_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
