CREATE DATABASE schoolmanagementsys;

USE schoolmanagementsys; 


CREATE TABLE teacher(
    teacher_id VARCHAR(60) NOT NULL,
    F_name VARCHAR(60) NOT NULL,
    L_name VARCHAR(60) NOT NULL,
    email VARCHAR(60) NOT NULL,
    password VARCHAR(60) NOT NULL,
    download_id VARCHAR(60) not null,
    gender VARCHAR(60) NOT NULL,
    DOB DATE NOT NULL,
    phone_number int(13) NOT NULL,
    registration_start_date DATE NOT NULL,
    registration_end_date DATE NOT NULL,
    primary_subject VARCHAR(60) NOT NULL,
    secondary_subject VARCHAR(60) NOT NULL,
    PRIMARY KEY (teacher_id)
);

CREATE TABLE student(
    student_id VARCHAR(60) NOT NULL,
    F_name VARCHAR(60) NOT NULL,
    L_name VARCHAR(60) NOT NULL,
    email VARCHAR(60) NOT NULL,
    password VARCHAR(60) NOT NULL,
    phone_number INT(13) NOT null,
    DOB DATE NOT NULL,
    section_id VARCHAR(60) NOT NULL,
    class_current VARCHAR(60) NOT NULL,
    class_addmission VARCHAR(60) NOT NULL,
    gender VARCHAR(60) NOT NULL,
    PRIMARY KEY (student_id,section_id)
);

CREATE TABLE admin(
    admin_id VARCHAR(60) NOT NULL,
    F_name VARCHAR(60) NOT NULL,
    L_name VARCHAR(60) NOT NULL,
    email VARCHAR(60) NOT NULL,
    password VARCHAR(60) NOT NULL,
    phone_number INT(13) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    DOB DATE NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    PRIMARY KEY(admin_id)
);

CREATE TABLE attendance(
    student_id VARCHAR(60) NOT NULL,
    teacher_id VARCHAR(60) NOT NULL,
    class VARCHAR(60) NOT NULL,
    date DATE NOT NULL,
    status VARCHAR(200) NOT NULL,
    PRIMARY KEY(student_id, teacher_id, date),
    FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id),
    FOREIGN KEY (student_id) REFERENCES student(student_id)
);

CREATE TABLE assignment(
    assignment_id VARCHAR(60) NOT NULL,
    teacher_id VARCHAR(60) NOT NULL,
    class VARCHAR(60) NOT NULL,
    subject VARCHAR(60) NOT NULL,
    date DATE NOT NULL,
    section VARCHAR(60) NOT NULL,
    details VARCHAR(200) NOT NULL,
    docs VARCHAR(200) NOT NULL,
    submission_date DATE NOT NULL,
    PRIMARY KEY(assignment_id),
    FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id)
);

CREATE TABLE subject(
    subject_id VARCHAR(60) NOT NULL,
    subject_name VARCHAR(60) NOT NULL,
    teacher_id VARCHAR(60) NOT NULL,
    class VARCHAR(60) NOT NULL,
    year VARCHAR(60) NOT NULL,
    PRIMARY KEY(subject_id, teacher_id),
    FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id)
);

CREATE TABLE feedback(
    student_id VARCHAR(60) NOT NULL,
    teacher_id VARCHAR(60) NOT NULL,
    feedback VARCHAR(200) NOT NULL,
    feedback_form VARCHAR(200) NOT NULL,
    PRIMARY KEY(student_id, teacher_id),
    FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id),
    FOREIGN KEY (student_id) REFERENCES student(student_id)
);

CREATE TABLE teacher_calendar(
    year INT NOT NULL,
    event_name VARCHAR(60) NOT NULL,
    date DATE,
    description VARCHAR(200)
);

CREATE TABLE results(
    results_id VARCHAR(60) NOT NULL,
    student_id VARCHAR(60) NOT NULL,
    admin_id VARCHAR(60) NOT NULL,
    class VARCHAR(60) NOT NULL,
    section VARCHAR(60)NOT NULL,
    year int(5) NOT NULL,
    PRIMARY KEY(results_id),
    FOREIGN KEY (student_id) REFERENCES student(student_id),
    FOREIGN KEY(admin_id) REFERENCES teacher(teacher_id)
);

CREATE TABLE fee_payment_details(
    student_id VARCHAR(60) NOT NULL,
    payment_id VARCHAR(60) NOT NULL,
    month VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    mode_of_payment VARCHAR(200) NOT NULL,
    note VARCHAR(200)NOT NULL, 
    fee_amount INT NOT NULL,
    fee_status VARCHAR(60) NOT NULL,
    PRIMARY KEY(student_id,payment_id),
    FOREIGN KEY (student_id) REFERENCES student(student_id)
);

CREATE TABLE book_issue(
    book_id VARCHAR(60) NOT NULL,
    book_issue_id  VARCHAR(60) NOT NULL,
    student_id VARCHAR(60) NOT NULL,
    date_of_issue DATE NOT NULL,
    date_of_submission DATE NOT NULL,
    PRIMARY KEY(book_id,book_issue_id,student_id),
    FOREIGN KEY(student_id) REFERENCES student(student_id)
);

CREATE TABLE downloads(
    download_id VARCHAR(60) NOT NULL,
    student_id VARCHAR(60) NOT NULL,
    teacher_id VARCHAR(60) NOT NULL,
    class VARCHAR(60) NOT NULL,
    subject VARCHAR(60) NOT NULL,
    assignment_details VARCHAR(200) NOT NULL,
    docs VARCHAR(200),
    date DATE ,
    submission_date DATE ,
    PRIMARY KEY(download_id),
    FOREIGN KEY(student_id) REFERENCES student(student_id),
    FOREIGN KEY(teacher_id) REFERENCES teacher(teacher_id)
);

CREATE TABLE student_calender(
    year INT NOT NULL,
    date_of_event DATE ,
    class VARCHAR(60) NOT NULL,
    section VARCHAR(60) NOT NULL,
    event_name VARCHAR(200) NOT NULL,
    event_description VARCHAR(200) NOT NULL
);

CREATE TABLE class_fee_structure(
    admin_id VARCHAR(60)NOT NULL,
    fee INT(10) NOT NULL,
    date DATE,
    PRIMARY KEY(admin_id),
    FOREIGN KEY(admin_id) REFERENCES admin(admin_id)
);





