<?php
declare(strict_types=1);
require_once __DIR__ . "/_bootstrap.php";

$type = $_POST['type'] ?? '';
$back = "../../view/admin.php";

try {
    switch ($type) {
        case 'tour':
            $data  = readTourInput();
            $image = saveTourImage($_FILES['image'] ?? []) ?? '';

            try {
                $tourModel->create(
                    $data['name'], $data['museum'], $data['description'], $data['price'], $image
                );
            } catch (Throwable $e) {
                deleteTourImage($image);   // don't leave an orphan file if the insert failed
                throw $e;
            }

            redirect("$back#tours", success: "Tour added.");

        case 'session':
            $tourId = (int) ($_POST['tour_id'] ?? 0);
            if (!$tourModel->find($tourId)) {
                throw new InvalidArgumentException("Please choose a valid tour.");
            }

            $sessionModel->create(
                $tourId,
                trim((string) ($_POST['session_date'] ?? '')),
                trim((string) ($_POST['start_time'] ?? '')),
                (int) ($_POST['total_seats'] ?? 0)
            );

            redirect("$back#sessions", success: "Session added.");

        default:
            throw new InvalidArgumentException("Unknown request.");
    }
} catch (InvalidArgumentException $e) {
    redirect($back, error: $e->getMessage());       // safe to show
} catch (Throwable $e) {
    error_log($e->getMessage());                    // details go to the log only
    redirect($back, error: "Something went wrong. Please try again.");
}