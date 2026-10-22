<?php
declare(strict_types=1);
require_once __DIR__ . "/../db.php";

class Reservation
{
    private PDO $conn;

    public function __construct() { 
        global $conn;
        $this->conn = $conn;        
    }

    public function book(int $userId, int $sessionId, int $seats): int
    {
        if ($seats < 1) {
            throw new InvalidArgumentException("You must book at least 1 seat.");
        }

        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare(
                "SELECT total_seats,
                        TIMESTAMP(session_date, start_time) > NOW() AS is_upcoming
                 FROM tour_sessions
                 WHERE id = ?
                 FOR UPDATE"
            );
            $stmt->execute([$sessionId]);
            $session = $stmt->fetch();

            if (!$session) {
                throw new InvalidArgumentException("Session not found.");
            }
            if (!(int) $session['is_upcoming']) {
                throw new InvalidArgumentException("This session has already started.");
            }

            // 2. Count seats already taken (only confirmed bookings).
            $stmt = $this->conn->prepare(
                "SELECT COALESCE(SUM(number_of_seats), 0)
                 FROM reservations
                 WHERE session_id = ? AND status = 'confirmed'"
            );
            $stmt->execute([$sessionId]);
            $booked = (int) $stmt->fetchColumn();

            $seatsLeft = (int) $session['total_seats'] - $booked;

            if ($seats > $seatsLeft) {
                throw new InvalidArgumentException("Only $seatsLeft seat(s) left.");
            }

            // 3. Safe to insert: nobody else can have changed the count.
            $stmt = $this->conn->prepare(
                "INSERT INTO reservations (user_id, session_id, number_of_seats)
                 VALUES (?, ?, ?)"
            );
            $stmt->execute([$userId, $sessionId, $seats]);
            $id = (int) $this->conn->lastInsertId();

            $this->conn->commit();
            return $id;

        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Visitor cancels their OWN confirmed reservation.
     * Returns false if it doesn't exist, isn't theirs, or is already cancelled.
     */
    public function cancel(int $id, int $userId): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE reservations
             SET status = 'cancelled'
             WHERE id = ? AND user_id = ? AND status = 'confirmed'"
        );
        $stmt->execute([$id, $userId]);

        return $stmt->rowCount() > 0;
    }

    /** Admin cancels any reservation (check the admin role in the calling page). */
    public function cancelAsAdmin(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE reservations
             SET status = 'cancelled'
             WHERE id = ? AND status = 'confirmed'"
        );
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    public function find(int $id): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT id, user_id, session_id, number_of_seats, status, created_at
             FROM reservations WHERE id = ?"
        );
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT r.id, r.number_of_seats, r.status, r.created_at,
                    s.id AS session_id, s.session_date, s.start_time,
                    t.name AS tour_name, t.museum_name, t.price
             FROM reservations r
             JOIN tour_sessions s ON s.id = r.session_id
             JOIN tours t         ON t.id = s.tour_id
             WHERE r.user_id = ?
             ORDER BY s.session_date DESC, s.start_time DESC"
        );
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function getBySession(int $sessionId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT r.id, r.number_of_seats, r.status, r.created_at,
                    u.id AS user_id, u.username, u.email
             FROM reservations r
             JOIN users u ON u.id = r.user_id
             WHERE r.session_id = ?
             ORDER BY r.created_at"
        );
        $stmt->execute([$sessionId]);

        return $stmt->fetchAll();
    }
}