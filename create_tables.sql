CREATE DATABASE schoolmanagementsys;

USE schoolmanagementsys; 

-- Users Table (Common for all roles)
CREATE TABLE users (
    user_id INT  AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- For secure password storage
    role ENUM('admin', 'teacher', 'student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    student_email VARCHAR(100) UNIQUE NOT NULL,
    enrolment_year TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    guardian_email VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE teachers (
    lecturer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    lecturer_email VARCHAR(100) UNIQUE NOT NULL,
     specialization VARCHAR(100),
     FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY ,
    subject_name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id VARCHAR(10) NOT NULL,
    lecturer_id INT NOT NULL,
    term ENUM('Term 1', 'Term 2', 'Term 3'),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (lecturer_id) REFERENCES teachers(lecturer_id)
);

CREATE TABLE timetable (
    schedule_id INT  AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    day_of_week ENUM('Mon', 'Tue', 'Wed', 'Thu', 'Fri'),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    venue VARCHAR(50),
    FOREIGN KEY (class_id) REFERENCES classes(class_id)
);

-- Enrollments of Students in Classes)
CREATE TABLE enrollments (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    enrollment_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (class_id) REFERENCES classes(class_id)
);

CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent', 'Late'),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (class_id) REFERENCES classes(class_id)
);


CREATE TABLE grades (
    grade_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    grade DECIMAL(5,2),
    term ENUM('Term 1', 'Term 2', 'Term 3'),
    academic_year YEAR,
    assignement_date DATE NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (class_id) REFERENCES classes(class_id)
);

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY ,
    student_id INT NOT NULL,
    message TEXT NOT NULL,
    sent_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Sent', 'Failed'),
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);


