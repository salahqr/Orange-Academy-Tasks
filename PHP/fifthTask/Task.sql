-- make table part

CREATE TABLE students (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    date_of_birth DATE,
    gender ENUM('male', 'female'),
    major VARCHAR(100),
    enrollment_year YEAR
);

CREATE TABLE courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(255) NOT NULL,
    course_code VARCHAR(20) UNIQUE,
    credits INT NOT NULL,
    department VARCHAR(100)
);

CREATE TABLE instructors (
    instructor_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    hire_date DATE,
    department VARCHAR(100)
);

CREATE TABLE enrollments (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    grade DECIMAL(5,2),

    FOREIGN KEY (student_id)
        REFERENCES students(student_id),

    FOREIGN KEY (course_id)
        REFERENCES courses(course_id),

    UNIQUE (student_id, course_id)
);

CREATE TABLE course_assignments (
    assignment_id INT PRIMARY KEY AUTO_INCREMENT,
    instructor_id INT NOT NULL,
    course_id INT NOT NULL,
    semester ENUM('Spring', 'Summer', 'Fall', 'Winter') NOT NULL,
    year YEAR NOT NULL,

    FOREIGN KEY (instructor_id)
        REFERENCES instructors(instructor_id),

    FOREIGN KEY (course_id)
        REFERENCES courses(course_id),

    UNIQUE (instructor_id, course_id, semester, year)
);

-- insert data

INSERT INTO students
(first_name, last_name, email, date_of_birth, gender, major, enrollment_year)
VALUES
('Ahmad', 'Ali', 'ahmad.ali@example.com', '2003-05-12', 'male', 'Computer Science', 2022),
('Sara', 'Khaled', 'sara.khaled@example.com', '2004-02-20', 'female', 'Information Technology', 2023),
('Omar', 'Hassan', 'omar.hassan@example.com', '2002-11-08', 'male', 'Software Engineering', 2021),
('Lina', 'Mahmoud', 'lina.mahmoud@example.com', '2003-07-15', 'female', 'Computer Science', 2022),
('Yousef', 'Saleh', 'yousef.saleh@example.com', '2004-09-03', 'male', 'Cybersecurity', 2023),
('Noor', 'Ibrahim', 'noor.ibrahim@example.com', '2003-04-25', 'female', 'Information Technology', 2022),
('Khaled', 'Nasser', 'khaled.nasser@example.com', '2002-12-17', 'male', 'Software Engineering', 2021),
('Maya', 'Samir', 'maya.samir@example.com', '2004-06-10', 'female', 'Computer Science', 2023),
('Zaid', 'Fares', 'zaid.fares@example.com', '2003-01-30', 'male', 'Cybersecurity', 2022),
('Dana', 'Tariq', 'dana.tariq@example.com', '2004-08-22', 'female', 'Information Technology', 2023);

INSERT INTO instructors
(first_name, last_name, email, hire_date, department)
VALUES
('Mohammad', 'Ahmad', 'mohammad.ahmad@example.com', '2018-09-01', 'Computer Science'),
('Rania', 'Omar', 'rania.omar@example.com', '2020-02-15', 'Information Technology'),
('Khalil', 'Hassan', 'khalil.hassan@example.com', '2017-06-10', 'Software Engineering'),
('Huda', 'Saleh', 'huda.saleh@example.com', '2021-01-20', 'Cybersecurity'),
('Samer', 'Nasser', 'samer.nasser@example.com', '2019-08-05', 'Computer Science');

INSERT INTO courses
(course_name, course_code, credits, department)
VALUES
('Database Systems', 'CS101', 3, 'Computer Science'),
('Web Development', 'IT201', 3, 'Information Technology'),
('Data Structures', 'CS202', 4, 'Computer Science'),
('Software Engineering', 'SE301', 3, 'Software Engineering'),
('Network Security', 'CY401', 3, 'Cybersecurity');

INSERT INTO course_assignments
(instructor_id, course_id, semester, year)
VALUES
(1, 1, 'Fall', 2026),
(2, 2, 'Fall', 2026),
(3, 3, 'Fall', 2026),
(4, 5, 'Fall', 2026),
(5, 4, 'Fall', 2026);

INSERT INTO enrollments
(student_id, course_id, grade)
VALUES
(1, 1, 88.00),
(1, 2, 91.00),

(2, 1, 95.00),
(2, 3, 89.00),

(3, 2, 78.00),
(3, 4, 85.00),

(4, 1, 92.00),
(4, 4, 90.00),

(5, 3, 81.00),
(5, 5, 87.00),

(6, 2, 94.00),
(6, 5, 88.00),

(7, 3, 76.00),
(7, 4, 82.00),

(8, 1, 90.00),
(8, 5, 93.00),

(9, 2, 84.00),
(9, 3, 79.00),

(10, 4, 96.00),
(10, 5, 91.00);



-- Queries:

select * from students;
select COUNT(*) as number_of_cuorsess from courses;
SELECT s.first_name, s.last_name
FROM students s
JOIN enrollments e ON s.student_id = e.student_id JOIN courses ON e.course_id = c.course_id
WHERE c.course_name = 'Database Systems';select email from instructors WHERE department = 'Computer Science';


-- Intermediate Queries:
SELECT c.course_name, COUNT(e.student_id) AS number_of_students
FROM courses AS c
LEFT JOIN enrollments AS e ON c.course_id = e.course_id
GROUP BY c.course_id, c.course_name;

SELECT DISTINCT s.student_id, s.first_name, s.last_name
FROM students AS s
JOIN enrollments AS e ON s.student_id = e.student_id
WHERE e.grade >= 90;

SELECT AVG(e.grade) AS average_grade
FROM enrollments AS e
JOIN courses AS c ON c.course_id = e.course_id
WHERE c.course_code = 'CS101';

SELECT c.course_name, i.first_name, i.last_name
FROM course_assignments AS ca
JOIN courses AS c ON c.course_id = ca.course_id
JOIN instructors AS i ON i.instructor_id = ca.instructor_id
WHERE ca.semester = 'Fall' AND ca.year = 2026;


-- ===== Advanced Queries =====
SELECT s.student_id, s.first_name, s.last_name, COUNT(*) AS course_count
FROM students AS s
JOIN enrollments AS e ON e.student_id = s.student_id
WHERE e.semester = 'Fall' AND e.year = YEAR(CURDATE())
GROUP BY s.student_id, s.first_name, s.last_name
HAVING COUNT(*) > 3;

SELECT s.student_id, s.first_name, s.last_name, c.course_name
FROM students AS s
JOIN enrollments AS e ON e.student_id = s.student_id
JOIN courses AS c ON c.course_id = e.course_id
WHERE e.grade IS NULL;

SELECT s.student_id, s.first_name, s.last_name, AVG(e.grade) AS avg_grade
FROM students AS s
JOIN enrollments AS e ON e.student_id = s.student_id
WHERE e.grade IS NOT NULL
GROUP BY s.student_id, s.first_name, s.last_name
ORDER BY avg_grade DESC
LIMIT 1;

SELECT c.department, COUNT(DISTINCT c.course_id) AS courses_taught
FROM course_assignments AS ca
JOIN courses AS c ON c.course_id = ca.course_id
WHERE ca.year = YEAR(CURDATE())
GROUP BY c.department
ORDER BY courses_taught DESC
LIMIT 1;

SELECT c.course_id, c.course_name
FROM courses AS c
LEFT JOIN enrollments AS e ON e.course_id = c.course_id
WHERE e.enrollment_id IS NULL;


-- ===== Step 4: Functions and Aggregates =====
DELIMITER //
CREATE FUNCTION get_student_age(p_dob DATE)
RETURNS INT
NOT DETERMINISTIC
NO SQL
BEGIN
    RETURN TIMESTAMPDIFF(YEAR, p_dob, CURDATE());
END //
DELIMITER ;

SELECT first_name, last_name, get_student_age(date_of_birth) AS age FROM students;

-- 2. Procedure: enroll a student in a course
DELIMITER //
CREATE PROCEDURE enroll_student(
    IN p_student_id INT,
    IN p_course_id INT,
    IN p_semester VARCHAR(10),
    IN p_year YEAR
)
BEGIN
    INSERT INTO enrollments (student_id, course_id, semester, year)
    VALUES (p_student_id, p_course_id, p_semester, p_year);
END //
DELIMITER ;

CALL enroll_student(1, 2, 'Fall', 2026);

-- 3. Average grade by department
SELECT c.department, AVG(e.grade) AS avg_grade
FROM courses AS c
JOIN enrollments AS e ON e.course_id = c.course_id
WHERE e.grade IS NOT NULL
GROUP BY c.department;


-- ===== Step 5: Constraints and Transactions =====
 
ALTER TABLE students ADD CONSTRAINT uq_students_email UNIQUE (email);

-- 2. Enroll only if the course is not full
DELIMITER //
CREATE PROCEDURE enroll_if_space(
    IN p_student_id INT,
    IN p_course_id INT,
    IN p_semester VARCHAR(10),
    IN p_year YEAR
)
BEGIN
    DECLARE v_capacity INT;
    DECLARE v_current INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    -- lock the course row so two people can't take the last seat at the same time
    SELECT capacity INTO v_capacity
    FROM courses WHERE course_id = p_course_id FOR UPDATE;

    SELECT COUNT(*) INTO v_current
    FROM enrollments
    WHERE course_id = p_course_id AND semester = p_semester AND year = p_year;

    IF v_current < v_capacity THEN
        INSERT INTO enrollments (student_id, course_id, semester, year)
        VALUES (p_student_id, p_course_id, p_semester, p_year);
        COMMIT;
    ELSE
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Course is full';
    END IF;
END //
DELIMITER ;

CALL enroll_if_space(1, 2, 'Fall', 2026);


-- ===== Step 6: Optimization and Indexes =====
CREATE INDEX idx_courses_course_code ON courses (course_code);

-- 2. EXPLAIN a query that fetches students enrolled in a course
EXPLAIN
SELECT s.student_id, s.first_name, s.last_name
FROM courses AS c
JOIN enrollments AS e ON e.course_id = c.course_id
JOIN students AS s ON s.student_id = e.student_id
WHERE c.course_code = 'CS101';
-- ===== Step 7: Joins =====

-- 1. Inner join: students and their courses
SELECT s.first_name, s.last_name, c.course_name
FROM students AS s
INNER JOIN enrollments AS e ON e.student_id = s.student_id
INNER JOIN courses AS c ON c.course_id = e.course_id;

-- 2. Left join: instructors and the courses they teach
SELECT i.first_name, i.last_name, c.course_name
FROM instructors AS i
LEFT JOIN course_assignments AS ca ON ca.instructor_id = i.instructor_id
LEFT JOIN courses AS c ON c.course_id = ca.course_id;

-- 3. Union: all students and instructors
SELECT first_name, last_name, email, 'student' AS role FROM students
UNION
SELECT first_name, last_name, email, 'instructor' AS role FROM instructors;


-- ===== Step 8: Final Challenge =====
SELECT
    s.first_name,
    s.last_name,
    s.email,
    s.major,
    c.course_name,
    i.first_name AS instructor_first_name,
    i.last_name  AS instructor_last_name,
    e.grade,
    SUM(c.credits) OVER (PARTITION BY s.student_id) AS total_credits
FROM students AS s
JOIN enrollments AS e ON e.student_id = s.student_id
JOIN courses AS c ON c.course_id = e.course_id
LEFT JOIN course_assignments AS ca
       ON ca.course_id = e.course_id
      AND ca.semester = e.semester
      AND ca.year = e.year
LEFT JOIN instructors AS i ON i.instructor_id = ca.instructor_id
ORDER BY s.student_id, c.course_name;