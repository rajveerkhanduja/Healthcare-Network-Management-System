CREATE DATABASE hospital;

USE hospital;

CREATE TABLE Patient (
    PatientID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    DOB DATE,
    Gender CHAR(1),
    Address VARCHAR(255),
    Phone VARCHAR(20)
);

CREATE TABLE Doctor (
    DoctorID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Specialty VARCHAR(50),
    Phone VARCHAR(20),
    Email VARCHAR(100)
);

CREATE TABLE Appointment (
    AppointmentID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT,
    DoctorID INT,
    ApptDate DATE,
    ApptTime TIME,
    Purpose VARCHAR(255),
    FOREIGN KEY (PatientID) REFERENCES Patient(PatientID),
    FOREIGN KEY (DoctorID) REFERENCES Doctor(DoctorID)
);

CREATE TABLE Treatment (
    TreatmentID INT AUTO_INCREMENT PRIMARY KEY,
    AppointmentID INT,
    Description VARCHAR(255),
    Date DATE,
    FOREIGN KEY (AppointmentID) REFERENCES Appointment(AppointmentID)
);

CREATE TABLE Prescription (
    PrescriptionID INT AUTO_INCREMENT PRIMARY KEY,
    TreatmentID INT,
    Medication VARCHAR(100),
    Dosage VARCHAR(100),
    FOREIGN KEY (TreatmentID) REFERENCES Treatment(TreatmentID)
);

INSERT INTO Patient (Name, DOB, Gender, Address, Phone)
VALUES 
    ('Sanat Kulkarni', '1990-01-01', 'M', '123, ABC Colony, Pune', '9876543210'),
    ('Aditya Dutt', '1985-05-15', 'M', '456, XYZ Nagar, Mumbai', '9876543211'),
    ('Rachit Methwani', '1992-03-20', 'M', '789, PQR Street, Delhi', '9876543212'),
    ('Rajveer Singh Khanduja', '1988-11-10', 'M', '321, LMN Road, Kolkata', '9876543213');

INSERT INTO Doctor (Name, Specialty, Phone, Email)
VALUES 
    ('Dr. Sharma', 'Cardiology', '9876543214', 'sharma@hospital.com'),
    ('Dr. Kapoor', 'Orthopedics', '9876543215', 'kapoor@hospital.com'),
    ('Dr. Gupta', 'Pediatrics', '9876543216', 'gupta@hospital.com');

INSERT INTO Appointment (PatientID, DoctorID, ApptDate, ApptTime, Purpose)
VALUES 
    (1, 1, '2024-04-25', '14:00', 'Routine Checkup'),
    (2, 2, '2024-05-03', '10:00', 'Initial Consultation'),
    (1, 1, '2024-05-10', '15:30', 'Follow-up'),
    (3, 1, '2024-05-15', '09:00', 'Annual Physical'),
    (1, 3, '2024-05-20', '14:00', 'Pediatric Check-up');

INSERT INTO Treatment (AppointmentID, Description, Date)
VALUES 
    (1, 'Heart examination', '2024-04-25'),
    (2, 'Bone fracture examination', '2024-05-03'),
    (1, 'Blood pressure check', '2024-05-10');

INSERT INTO Prescription (TreatmentID, Medication, Dosage)
VALUES 
    (1, 'Atorvastatin', '20mg'),
    (2, 'Ibuprofen', '400mg'),
    (3, 'Paracetamol', '500mg');

CREATE VIEW View_AppointmentDetails AS
SELECT p.Name AS PatientName, d.Name AS DoctorName, a.ApptDate, a.ApptTime, a.Purpose
FROM Appointment a
JOIN Patient p ON a.PatientID = p.PatientID
JOIN Doctor d ON a.DoctorID = d.DoctorID;

CREATE VIEW View_PrescriptionDetails AS
SELECT p.Name AS PatientName, pr.Medication, pr.Dosage
FROM Prescription pr
JOIN Treatment t ON pr.TreatmentID = t.TreatmentID
JOIN Appointment a ON t.AppointmentID = a.AppointmentID
JOIN Patient p ON a.PatientID = p.PatientID;

CREATE VIEW View_DoctorAppointments AS
SELECT d.Name AS DoctorName, COUNT(*) AS NumAppointments
FROM Doctor d
JOIN Appointment a ON d.DoctorID = a.DoctorID
GROUP BY d.DoctorID;

CREATE VIEW View_PatientTreatments AS
SELECT p.Name AS PatientName, t.Description
FROM Treatment t
JOIN Appointment a ON t.AppointmentID = a.AppointmentID
JOIN Patient p ON a.PatientID = p.PatientID;

CREATE VIEW View_TreatmentPrescriptions AS
SELECT t.Description AS Treatment, pr.Medication, pr.Dosage
FROM Treatment t
JOIN Prescription pr ON t.TreatmentID = pr.TreatmentID;

UPDATE Patient SET Address = '321, Pine Street, Bangalore' WHERE PatientID = 1;
UPDATE Doctor SET Email = 'info@kapoorclinic.com' WHERE DoctorID = 2;
UPDATE Appointment SET ApptTime = '16:00' WHERE AppointmentID = 1;
UPDATE Treatment SET Description = 'Comprehensive Heart Checkup' WHERE TreatmentID = 1;

-- Update patient's address
UPDATE Patient SET Address = '321, Pine Street, Bangalore' WHERE PatientID = 1;

-- Final queries for data analysis

SELECT d.Name, COUNT(*) AS NumAppointments FROM Doctor d
JOIN Appointment a ON d.DoctorID = a.DoctorID
GROUP BY d.Name;

SELECT DISTINCT d.Name FROM Doctor d
JOIN Appointment a ON d.DoctorID = a.DoctorID
WHERE a.ApptDate = '2024-04-25';

SELECT d.Name, d.Specialty FROM Doctor d;

SELECT p.Name AS PatientName, a.ApptDate, a.ApptTime
FROM Patient p
JOIN Appointment a ON p.PatientID = a.PatientID;

SELECT a.ApptDate, a.ApptTime, p.Name AS PatientName, d.Name AS DoctorName
FROM Appointment a
JOIN Patient p ON a.PatientID = p.PatientID
JOIN Doctor d ON a.DoctorID = d.DoctorID;

SELECT p.Name AS PatientName, t.Description AS TreatmentDescription
FROM Patient p
JOIN Appointment a ON p.PatientID = a.PatientID
JOIN Treatment t ON a.AppointmentID = t.AppointmentID;

SELECT d.Name AS DoctorName, d.Email
FROM Doctor d;

SELECT a.ApptDate, a.ApptTime, t.Description AS TreatmentDescription, pr.Medication
FROM Appointment a
JOIN Treatment t ON a.AppointmentID = t.AppointmentID
JOIN Prescription pr ON t.TreatmentID = pr.TreatmentID;

SELECT p.Name AS PatientName, t.Description AS TreatmentDescription, d.Name AS DoctorName
FROM Patient p
JOIN Appointment a ON p.PatientID = a.PatientID
JOIN Treatment t ON a.AppointmentID = t.AppointmentID
JOIN Doctor d ON a.DoctorID = d.DoctorID;
