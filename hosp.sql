-- USERS TABLE (Doctors, Nurses, Receptionists, Admins)
CREATE TABLE users (
    user_id INT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_initial VARCHAR(10),
    last_name VARCHAR(50) NOT NULL,
    contact_number VARCHAR(30),
    email VARCHAR(100),
    specialty VARCHAR(100) -- Only for doctors, can be NULL for others
);

-- LOGIN TABLE
CREATE TABLE login (
    user_id INT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- JOB TABLE
CREATE TABLE job (
    user_id INT PRIMARY KEY,
    role ENUM('Doctor', 'Nurse', 'Receptionist', 'Admin') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- PATIENT TABLE
CREATE TABLE patient (
    patient_id INT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_initial VARCHAR(10),
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE,
    age INT,
    sex CHAR(1),
    contact_number VARCHAR(30),
    address VARCHAR(255),
    medical_history TEXT,
    date_admitted DATE,
    email_address VARCHAR(100),
    emergency_contact VARCHAR(100),
    marital_status VARCHAR(20),
    occupation VARCHAR(50),
    insurance_provider VARCHAR(100),
    allergies TEXT,
    medications TEXT,
    lifestyle TEXT,
    room_number VARCHAR(20) -- If you want to keep track of patient room
);

-- APPOINTMENT TABLE
CREATE TABLE appointment (
    appointment_id INT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    time TIME NOT NULL,
    status TINYINT DEFAULT 1,
    reason VARCHAR(255),
    room_number VARCHAR(20), -- If you want to keep the room at appointment time
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- BILLING TABLE
CREATE TABLE billing (
    billing_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    service VARCHAR(255) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status VARCHAR(20) NOT NULL,
    payment_date DATE NOT NULL,
    receptionist_id INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patient(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (receptionist_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Add indexes for performance (optional)
CREATE INDEX idx_patient_name ON patient(last_name, first_name);
CREATE INDEX idx_appointment_date ON appointment(appointment_date);
CREATE INDEX idx_billing_patient ON billing(patient_id);

-- Example ENUM for sex if you want to restrict values
-- ALTER TABLE patient MODIFY sex ENUM('M','F');