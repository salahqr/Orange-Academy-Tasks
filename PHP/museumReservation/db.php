<?php
declare(strict_types=1);

try {
    $dbhost = 'localhost';
    $dbname = 'dash';
    $dbuser = 'root';
    $dbpass = '';

    $conn = new PDO(
        "mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4",
        $dbuser,
        $dbpass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $users = "
        CREATE TABLE IF NOT EXISTS users (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL UNIQUE,
            email VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('visitor', 'admin') NOT NULL DEFAULT 'visitor',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";

    $tours = "
        CREATE TABLE IF NOT EXISTS tours (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            museum_name VARCHAR(30) NOT NULL,
            description VARCHAR(255),
            price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            image VARCHAR(255)
        )
    ";

    $tour_sessions = "
        CREATE TABLE IF NOT EXISTS tour_sessions (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            tour_id INT UNSIGNED NOT NULL,
            session_date DATE NOT NULL,
            start_time TIME NOT NULL,
            total_seats INT UNSIGNED NOT NULL,
            FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
        )
    ";

    $reservations = "
        CREATE TABLE IF NOT EXISTS reservations (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            session_id INT UNSIGNED NOT NULL,
            number_of_seats INT UNSIGNED NOT NULL,
            status ENUM('confirmed', 'cancelled') NOT NULL DEFAULT 'confirmed',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (session_id) REFERENCES tour_sessions(id) ON DELETE CASCADE
        )
    ";


    $conn->exec($users);
    $conn->exec($tours);
    $conn->exec($tour_sessions);
    $conn->exec($reservations);

    //     // Insert users
    // $conn->exec("
    //     INSERT IGNORE INTO users (username, email, password, role) VALUES
    //     ('admin', 'admin@example.com', '" . password_hash('Admin123!', PASSWORD_DEFAULT) . "', 'admin'),
    //     ('salah', 'salah@example.com', '" . password_hash('User123!', PASSWORD_DEFAULT) . "', 'visitor'),
    //     ('ahmad', 'ahmad@example.com', '" . password_hash('User456!', PASSWORD_DEFAULT) . "', 'visitor')
    // ");

    // // Insert tours
    // $conn->exec("
    //     INSERT INTO tours (name, museum_name, description, price, image) VALUES
    //     ('Ancient History', 'Jordan Museum', 'Explore the history of Jordan and its ancient civilizations.', 10.00, 'jordan-museum.jpg'),
    //     ('Roman Civilization', 'Amman Citadel', 'Discover Roman ruins and historical landmarks.', 15.00, 'amman-citadel.jpg'),
    //     ('Islamic Art', 'Jordan Museum', 'Learn about Islamic art and cultural heritage.', 8.00, 'islamic-art.jpg')
    // ");

    // // Insert tour sessions
    // $conn->exec("
    //     INSERT INTO tour_sessions (tour_id, session_date, start_time, total_seats) VALUES
    //     (1, '2026-11-01', '10:00:00', 20),
    //     (1, '2026-11-02', '14:00:00', 15),
    //     (2, '2026-11-03', '11:00:00', 25),
    //     (3, '2026-11-04', '09:00:00', 12)
    // ");

    // // Insert reservations
    // $conn->exec("
    //     INSERT INTO reservations (user_id, session_id, number_of_seats, status) VALUES
    //     (2, 1, 2, 'confirmed'),
    //     (3, 2, 3, 'confirmed'),
    //     (2, 3, 1, 'cancelled')
    // ");


} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}