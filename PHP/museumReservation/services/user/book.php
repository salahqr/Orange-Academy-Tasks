<?php
declare(strict_types=1);

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

if (empty($_SESSION['user_id'])) {
    header("Location: ../../view/login.php");
    exit;
}

require_once __DIR__ . "/../../model/reservations.php";

function redirectToUser(string $key, string $message): never
{
    header(
        "Location: ../../view/user.php?"
        . http_build_query([$key => $message])
        . "#tours"
    );
    exit;
}

$sessionId = filter_input(INPUT_POST, 'session_id', FILTER_VALIDATE_INT);
$seats = filter_input(INPUT_POST, 'seats', FILTER_VALIDATE_INT);
$userId = (int) $_SESSION['user_id'];

if (!$sessionId || $sessionId < 1 || !$seats || $seats < 1) {
    redirectToUser('error', 'Please select a valid session and number of seats.');
}

$reservationModel = new Reservation();

try {
    $reservationModel->book($userId, $sessionId, $seats);

    redirectToUser('success', 'Reservation confirmed!');
} catch (InvalidArgumentException $e) {
    redirectToUser('error', $e->getMessage());
} catch (Throwable $e) {
    error_log($e->__toString());

    redirectToUser(
        'error',
        'Unable to complete your reservation. Please try again.'
    );
}
