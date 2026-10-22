<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . "/../../db.php";
require_once __DIR__ . "/../../model/tours.php";
require_once __DIR__ . "/../../model/reservations.php";
require_once __DIR__ . "/../../model/tourSessions.php";

// ---------- Access control (every service file includes this) ----------
if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    exit("Forbidden");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header("Allow: POST");
    exit("Method not allowed");
}

$tourModel        = new Tour();
$sessionModel     = new TourSession();
$reservationModel = new Reservation();

// ---------- Settings ----------
const TOUR_UPLOAD_DIR = __DIR__ . "/../../uploads/tours/";
const MAX_IMAGE_BYTES = 2 * 1024 * 1024;   // 2 MB

// ---------- Redirect back to a page with a message ----------
function redirect(string $to, ?string $success = null, ?string $error = null): never
{
    [$path, $fragment] = array_pad(explode('#', $to, 2), 2, null);

    $params = [];
    if ($success !== null) $params['success'] = $success;
    if ($error !== null)   $params['error']   = $error;

    if ($params) {
        $path .= (str_contains($path, '?') ? '&' : '?') . http_build_query($params);
    }
    if ($fragment !== null) {
        $path .= '#' . $fragment;
    }

    header("Location: $path");
    exit;
}

// ---------- Read and validate the tour form fields ----------
function readTourInput(): array
{
    $name        = trim((string) ($_POST['name'] ?? ''));
    $museum      = trim((string) ($_POST['museum_name'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $price       = filter_var($_POST['price'] ?? '', FILTER_VALIDATE_FLOAT);

    if ($name === '' || mb_strlen($name) > 30) {
        throw new InvalidArgumentException("Name is required (max 30 characters).");
    }
    if ($museum === '' || mb_strlen($museum) > 30) {
        throw new InvalidArgumentException("Museum name is required (max 30 characters).");
    }
    if (mb_strlen($description) > 255) {
        throw new InvalidArgumentException("Description is too long (max 255 characters).");
    }
    if ($price === false || $price < 0) {
        throw new InvalidArgumentException("Price must be a number, 0 or more.");
    }

    return [
        'name'        => $name,
        'museum'      => $museum,
        'description' => $description,
        'price'       => (float) $price,
    ];
}

// ---------- Image upload ----------
/** Returns the new file name, or null if no file was chosen. */
function saveTourImage(array $file): ?string
{
    $error = $file['error'] ?? UPLOAD_ERR_NO_FILE;

    if ($error === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) {
        throw new InvalidArgumentException("Image is too large (max 2 MB).");
    }
    if ($error !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException("Image upload failed. Please try again.");
    }
    if ($file['size'] > MAX_IMAGE_BYTES) {
        throw new InvalidArgumentException("Image is too large (max 2 MB).");
    }

    // Detect the real type from the file contents, not the name or browser header
    $mime    = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    if (!isset($allowed[$mime]) || getimagesize($file['tmp_name']) === false) {
        throw new InvalidArgumentException("Image must be a JPG, PNG or WebP file.");
    }

    if (!is_dir(TOUR_UPLOAD_DIR)) {
        mkdir(TOUR_UPLOAD_DIR, 0755, true);
    }

    // Our own random name: the original name is never used
    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];

    if (!move_uploaded_file($file['tmp_name'], TOUR_UPLOAD_DIR . $name)) {
        throw new RuntimeException("Could not save the uploaded image.");
    }

    return $name;
}

function deleteTourImage(?string $name): void
{
    if ($name === null || $name === '') {
        return;
    }
    $path = TOUR_UPLOAD_DIR . basename($name);   // basename() blocks ../ tricks
    if (is_file($path)) {
        unlink($path);
    }
}