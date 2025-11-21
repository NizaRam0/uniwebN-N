INSERT INTO Department (Department_name, Department_description, Chief_of_Department)
VALUES
('Cardiology', 'Heart and cardiovascular diseases.', 'Dr. Sarah Nadim'),
('Neurology', 'Nervous system & brain related issues.', 'Dr. Ahmed Tarek'),
('Pediatrics', 'Child healthcare & development.', 'Dr. Jana Hatem'),
('Orthopedics', 'Bones, muscles, and joints.', 'Dr. Ali Mansour');

INSERT INTO Doctors 
(First_name, Last_name, Specialty, Phone, Email, Photo, About, Department_id)
VALUES
('Rami', 'Khoury', 'Cardiologist', '70123456', 'rami.khoury@example.com', 'rami_khoury.png', 'Expert in heart disease and hypertension.', 1),
('Sara', 'Bazzi', 'Neurologist', '70765432', 'sara.bazzi@example.com', 'sara_bazzi.png', 'Specialist in brain and nerve disorders.', 2),
('Nada', 'Hassan', 'Pediatrician', '70987654', 'nada.hassan@example.com', 'nada_hassan.png', 'Caring pediatric doctor for children.', 3),
('Karim', 'Mansour', 'Orthopedic Surgeon', '71321555', 'karim.mansour@example.com', 'karim_mansour.png', 'Expert in bone fractures and mobility.', 4);

INSERT INTO Patients 
(First_name, Last_name, Date_of_birth, Gender, Email, Pre-existing_condition, Blood_type, Age)
VALUES
('Nizar', 'Ramadan', '2006-02-15', 'M', 'nizar@example.com', 'None', 'O+', 18),
('Lea', 'Haddad', '1998-11-20', 'F', 'lea.haddad@example.com', 'Asthma', 'A-', 26);

INSERT INTO Doctor_Office_Hours 
(Doctor_id, Weekday, Start_time, End_time, Slot_length)
VALUES
(1, 1, '09:00:00', '12:00:00', 30),
(1, 3, '13:00:00', '16:00:00', 30),
(2, 2, '10:00:00', '14:00:00', 30),
(3, 4, '08:30:00', '12:30:00', 20),
(4, 5, '11:00:00', '15:00:00', 30);

INSERT INTO Appointment
(Appointment_Date, Reason, Status, Patient_id, Doctor_id, Appointment_time)
VALUES
('2025-06-10', 'Chest Pain Checkup', 'Scheduled', 1, 1, '09:30:00'),
('2025-06-11', 'Migraine Follow-up', 'Completed', 2, 2, '11:00:00');
