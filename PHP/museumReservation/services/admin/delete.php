<?php
declare(strict_types=1);
require_once __DIR__ . "/_bootstrap.php";

$type = $_POST['type'] ?? '';
$id   = (int) ($_POST['id'] ?? 0);
$back = "../../view/admin.php";

try {
    if ($id < 1) {
        throw new InvalidArgumentException("Invalid id.");
    }

    switch ($type) {
        case 'tour':
            $tour = $tourModel->find($id);          // read the image name BEFORE deleting the row
            if (!$tour) {
                throw new InvalidArgumentException("Tour not found.");
            }

            $tourModel->delete($id);                // sessions + reservations go via ON DELETE CASCADE
            deleteTourImage($tour['image'] ?? null);

            redirect("$back#tours", success: "Tour deleted.");

        case 'session':
            if (!$sessionModel->delete($id)) {
                throw new InvalidArgumentException("Session not found.");
            }

            redirect("$back#sessions", success: "Session deleted.");

        default:
            throw new InvalidArgumentException("Unknown request.");
    }
} catch (InvalidArgumentException $e) {
    redirect($back, error: $e->getMessage());
} catch (Throwable $e) {
    error_log($e->getMessage());
    redirect($back, error: "Something went wrong. Please try again.");
}