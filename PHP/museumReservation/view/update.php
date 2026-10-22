<?php
declare(strict_types=1);

session_start();

if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../model/tours.php";
require_once __DIR__ . "/../model/tourSessions.php";

$tourModel = new Tour();
$sessionModel = new TourSession();

$type = $_GET['type'] ?? '';
$id = (int) ($_GET['id'] ?? 0);

if ($id < 1 || !in_array($type, ['tour', 'session'], true)) {
    header("Location: admin.php?error=" . urlencode("Invalid item."));
    exit;
}

$tour = null;
$session = null;
$tours = [];

if ($type === 'tour') {
    $tour = $tourModel->find($id);

    if (!$tour) {
        header("Location: admin.php?error=" . urlencode("Tour not found."));
        exit;
    }
} else {
    $session = $sessionModel->find($id);

    if (!$session) {
        header("Location: admin.php?error=" . urlencode("Session not found."));
        exit;
    }

    $tours = $tourModel->getAll();
}

function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update | Museum Tours</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
</head>
<body>
    <header class="topbar">
        <a href="admin.php" class="brand">
            Museum Tours <span class="tag">Admin</span>
        </a>

        <nav>
            <a href="admin.php">&larr; Back to dashboard</a>
            <a href="logout.php" class="nav-out">Log out</a>
        </nav>
    </header>

    <main class="container narrow">
        <section class="page-head">
            <h1>Update</h1>
            <p>Change the details below and save.</p>
        </section>

        <?php if ($type === 'tour'): ?>
            <section class="card section">
                <h2>Edit tour</h2>

                <form class="form-grid"
                      action="../services/admin/update.php"
                      method="POST"
                      enctype="multipart/form-data">

                    <input type="hidden" name="type" value="tour">
                    <input type="hidden" name="id" value="<?= (int) $tour['id'] ?>">

                    <div class="field">
                        <label for="u-name">Name</label>
                        <input type="text" id="u-name" name="name"
                               maxlength="30"
                               value="<?= e($tour['name']) ?>" required>
                    </div>

                    <div class="field">
                        <label for="u-museum">Museum name</label>
                        <input type="text" id="u-museum" name="museum_name"
                               maxlength="30"
                               value="<?= e($tour['museum_name']) ?>" required>
                    </div>

                    <div class="field">
                        <label for="u-price">Price</label>
                        <input type="number" id="u-price" name="price"
                               min="0" step="0.01"
                               value="<?= e($tour['price']) ?>" required>
                    </div>

                    <div class="field">
                        <label for="u-image">Tour image</label>
                        <input type="file" id="u-image" name="image"
                               accept="image/png,image/jpeg,image/webp">
                        <small class="hint">Leave empty to keep the current image.</small>

                        <?php if (!empty($tour['image'])): ?>
                            <p>Current image: <?= e($tour['image']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="field full">
                        <label for="u-desc">Description</label>
                        <textarea id="u-desc" name="description"
                                  rows="4" maxlength="255"><?= e($tour['description']) ?></textarea>
                    </div>

                    <div class="field full row-end">
                        <a href="admin.php" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn">Save changes</button>
                    </div>
                </form>
            </section>

        <?php else: ?>
            <section class="card section">
                <h2>Edit session</h2>

                <form class="form-grid"
                      action="../services/admin/update.php"
                      method="POST">

                    <input type="hidden" name="type" value="session">
                    <input type="hidden" name="id" value="<?= (int) $session['id'] ?>">

                    <div class="field">
                        <label for="us-tour">Tour</label>
                        <select id="us-tour" name="tour_id" required>
                            <option value="">Choose a tour</option>

                            <?php foreach ($tours as $t): ?>
                                <option value="<?= (int) $t['id'] ?>"
                                    <?= (int) $t['id'] === (int) $session['tour_id'] ? 'selected' : '' ?>>
                                    <?= e($t['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="us-date">Date</label>
                        <input type="date" id="us-date" name="session_date"
                               value="<?= e($session['session_date']) ?>" required>
                    </div>

                    <div class="field">
                        <label for="us-time">Start time</label>
                        <input type="time" id="us-time" name="start_time"
                               value="<?= e(substr($session['start_time'], 0, 5)) ?>" required>
                    </div>

                    <div class="field">
                        <label for="us-seats">Total seats</label>
                        <input type="number" id="us-seats" name="total_seats"
                               min="1"
                               value="<?= (int) $session['total_seats'] ?>" required>

                        <small class="hint">
                            <?= (int) $session['total_seats'] - (int) $session['seats_left'] ?>
                            seats are already booked. Total seats cannot be lower than this number.
                        </small>
                    </div>

                    <div class="field full row-end">
                        <a href="admin.php" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn">Save changes</button>
                    </div>
                </form>
            </section>
        <?php endif; ?>
    </main>

    <footer class="footer">&copy; Museum Tours</footer>
</body>
</html>
