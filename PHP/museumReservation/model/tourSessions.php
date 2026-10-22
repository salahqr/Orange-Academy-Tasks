<?php
declare(strict_types=1);
require_once __DIR__ . "/../db.php";

class TourSession
{
    private PDO $conn;

    public function __construct() { 
        global $conn;
        $this->conn = $conn;        
    }


    public function create(
        int $tourId,
        string $sessionDate,
        string $startTime,
        int $totalSeats
    ): int {
        $this->validate($sessionDate, $startTime, $totalSeats);

        if (strtotime("$sessionDate $startTime") <= time()) {
            throw new InvalidArgumentException("Session must be in the future.");
        }

        $sql = "INSERT INTO tour_sessions (tour_id, session_date, start_time, total_seats)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tourId, $sessionDate, $startTime, $totalSeats]);

        return (int) $this->conn->lastInsertId();
    }

    public function update(
        int $id,
        int $tourId,
        string $sessionDate,
        string $startTime,
        int $totalSeats
    ): bool {
        $this->validate($sessionDate, $startTime, $totalSeats);

        if ($totalSeats < $this->bookedSeats($id)) {
            throw new InvalidArgumentException("Total seats cannot be lower than seats already booked.");
        }

        $sql = "UPDATE tour_sessions
                SET tour_id = ?, session_date = ?, start_time = ?, total_seats = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$tourId, $sessionDate, $startTime, $totalSeats, $id]);
    }

    public function delete(int $id): bool
    {
        // reservations are removed by ON DELETE CASCADE
        $stmt = $this->conn->prepare("DELETE FROM tour_sessions WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

    public function find(int $id): array|false
    {
        $sql = $this->baseSelect() . " WHERE s.id = ? GROUP BY s.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    /** All sessions (for the admin), soonest first. */
    public function getAll(): array
    {
        $sql = $this->baseSelect() . " GROUP BY s.id ORDER BY s.session_date, s.start_time";

        return $this->conn->query($sql)->fetchAll();
    }

    /** Upcoming sessions of one tour (for the public details page). */
    public function getUpcomingByTour(int $tourId): array
    {
        $sql = $this->baseSelect() . "
                WHERE s.tour_id = ?
                  AND TIMESTAMP(s.session_date, s.start_time) > NOW()
                GROUP BY s.id
                ORDER BY s.session_date, s.start_time";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tourId]);

        return $stmt->fetchAll();
    }

    // ---------- helpers ----------

    /** One query that returns each session with its seats_left. */
    private function baseSelect(): string
    {
        return "SELECT s.id, s.tour_id, s.session_date, s.start_time, s.total_seats,
                       s.total_seats - COALESCE(SUM(r.number_of_seats), 0) AS seats_left
                FROM tour_sessions s
                LEFT JOIN reservations r
                       ON r.session_id = s.id AND r.status = 'confirmed'";
    }

    private function bookedSeats(int $sessionId): int
    {
        $stmt = $this->conn->prepare(
            "SELECT COALESCE(SUM(number_of_seats), 0)
             FROM reservations
             WHERE session_id = ? AND status = 'confirmed'"
        );
        $stmt->execute([$sessionId]);

        return (int) $stmt->fetchColumn();
    }

    private function validate(string $date, string $time, int $seats): void
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format('Y-m-d') !== $date) {
            throw new InvalidArgumentException("Invalid date (use YYYY-MM-DD).");
        }

        $t = DateTime::createFromFormat('H:i', $time);
        if (!$t || $t->format('H:i') !== $time) {
            throw new InvalidArgumentException("Invalid time (use HH:MM).");
        }

        if ($seats < 1) {
            throw new InvalidArgumentException("Total seats must be at least 1.");
        }
    }
}