<?php
declare(strict_types=1);
require_once __DIR__ . "/_bootstrap.php";

$type = $_POST['type'] ?? '';
$id   = (int) ($_POST['id'] ?? 0);
$back = "../../view/admin.php";

// Where to send the admin if validation fails (back to the edit form)
$editPage = "../../view/update.php?type=" . urlencode($type) . "&id=" . $id;

try {
    if ($id < 1) {
        throw new InvalidArgumentException("Invalid id.");
    }

    switch ($type) {
        case 'tour':
            $existing = $tourModel->find($id);
            if (!$existing) {
                throw new InvalidArgumentException("Tour not found.");
            }

            $data     = readTourInput();
            $newImage = saveTourImage($_FILES['image'] ?? []);
            $image    = $newImage ?? ($existing['image'] ?? '');   // no new file = keep the old one

            try {
                $tourModel->update(
                    $id, $data['name'], $data['museum'], $data['description'], $data['price'], $image
                );
            } catch (Throwable $e) {
                deleteTourImage($newImage);   // update failed: discard the new file
                throw $e;
            }

            if ($newImage !== null) {
                deleteTourImage($existing['image'] ?? null);       // only after the DB update succeeded
            }

            redirect("$back#tours", success: "Tour updated.");

        case 'session':
            $tourId = (int) ($_POST['tour_id'] ?? 0);
            if (!$tourModel->find($tourId)) {
                throw new InvalidArgumentException("Please choose a valid tour.");
            }

            $sessionModel->update(
                $id,
                $tourId,
                trim((string) ($_POST['session_date'] ?? '')),
                trim((string) ($_POST['start_time'] ?? '')),
                (int) ($_POST['total_seats'] ?? 0)
            );

            redirect("$back#sessions", success: "Session updated.");

        case 'reservation_cancel':
            $sessionId = (int) ($_POST['session_id'] ?? 0);

            if (!$reservationModel->cancelAsAdmin($id)) {
                throw new InvalidArgumentException("Reservation not found or already cancelled.");
            }

            redirect("$back?session=$sessionId#reservations", success: "Reservation cancelled.");

        default:
            throw new InvalidArgumentException("Unknown request.");
    }
} catch (InvalidArgumentException $e) {
    // Reservation errors go back to the dashboard, edit errors back to the form
    $target = ($type === 'reservation_cancel') ? $back : $editPage;
    redirect($target, error: $e->getMessage());
} catch (Throwable $e) {
    error_log($e->getMessage());
    redirect($back, error: "Something went wrong. Please try again.");
}