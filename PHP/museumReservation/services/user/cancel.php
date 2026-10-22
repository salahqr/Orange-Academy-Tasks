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
        . "#my-reservations"
    );
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$userId = (int) $_SESSION['user_id'];

if (!$id || $id < 1) {
    redirectToUser('error', 'Invalid reservation.');
}

$reservationModel = new Reservation();

try {
    $cancelled = $reservationModel->cancel($id, $userId);

    if (!$cancelled) {
        redirectToUser(
            'error',
            'Reservation not found, already cancelled, or not yours.'
        );
    }

    redirectToUser('success', 'Reservation cancelled successfully.');
} catch (Throwable $e) {
    error_log($e->__toString());

    redirectToUser(
        'error',
        'Unable to cancel the reservation. Please try again.'
    );
}
