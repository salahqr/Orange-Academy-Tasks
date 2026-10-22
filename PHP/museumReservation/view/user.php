<?php
declare(strict_types=1);

session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../model/tours.php";
require_once __DIR__ . "/../model/tourSessions.php";
require_once __DIR__ . "/../model/reservations.php";

$tourModel = new Tour();
$sessionModel = new TourSession();
$reservationModel = new Reservation();

function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$q = trim($_GET['q'] ?? '');
$dateFilter = trim($_GET['date'] ?? '');

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

$tours = $tourModel->getAll();
$myReservations = $reservationModel->getByUser(
    (int) $_SESSION['user_id']
);

// Search tours by name or museum.
if ($q !== '') {
    $tours = array_filter($tours, function (array $tour) use ($q): bool {
        return stripos($tour['name'], $q) !== false
            || stripos($tour['museum_name'], $q) !== false;
    });
}

// Load upcoming sessions for each matching tour.
$sessionsByTour = [];

foreach ($tours as $tour) {
    $sessions = $sessionModel->getUpcomingByTour((int) $tour['id']);

    if ($dateFilter !== '') {
        $sessions = array_filter(
            $sessions,
            fn(array $session): bool =>
                $session['session_date'] === $dateFilter
        );
    }

    $sessionsByTour[$tour['id']] = array_values($sessions);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tours | Museum Tours</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
</head>
<body>
<header class="topbar">
    <a href="user.php" class="brand">Museum Tours</a>

    <nav>
        <a href="#tours" class="active">Tours</a>
        <a href="#my-reservations">My reservations</a>
        <a href="logout.php" class="nav-out">Log out</a>
    </nav>
</header>

<main class="container">
    <section class="page-head">
        <h1>Guided tours</h1>
        <p>Pick a tour, choose a session, and reserve your seats.</p>
    </section>

    <?php if ($error !== ''): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <form class="filters" method="GET" action="user.php">
        <input
            type="search"
            name="q"
            placeholder="Search tours or museums"
            value="<?= e($q) ?>"
        >

        <input
            type="date"
            name="date"
            aria-label="Filter by date"
            value="<?= e($dateFilter) ?>"
        >

        <button type="submit" class="btn btn-sm">Search</button>

        <?php if ($q !== '' || $dateFilter !== ''): ?>
            <a href="user.php" class="btn btn-sm btn-outline">Clear</a>
        <?php endif; ?>
    </form>

    <section id="tours" class="grid">
        <?php if (!$tours): ?>
            <p class="empty">No tours match your search.</p>
        <?php endif; ?>

        <?php foreach ($tours as $tour): ?>
            <?php $sessions = $sessionsByTour[$tour['id']] ?? []; ?>

            <article class="card tour-card">
                <?php if (!empty($tour['image'])): ?>
                    <div
                        class="tour-img"
                        style="background-image: url('<?= e($tour['image']) ?>')"
                    ></div>
                <?php else: ?>
                    <div class="tour-img"></div>
                <?php endif; ?>

                <div class="card-body">
                    <h2><?= e($tour['name']) ?></h2>

                    <p class="museum"><?= e($tour['museum_name']) ?></p>

                    <p class="desc"><?= e($tour['description']) ?></p>

                    <p class="price">
                        $<?= number_format((float) $tour['price'], 2) ?>
                        <span>per person</span>
                    </p>

                    <h3 class="sub">Upcoming sessions</h3>

                    <?php if (!$sessions): ?>
                        <p class="empty">
                            <?= $dateFilter !== ''
                                ? 'No sessions on this date.'
                                : 'No upcoming sessions yet.' ?>
                        </p>
                    <?php endif; ?>

                    <?php foreach ($sessions as $session): ?>
                        <?php
                        $seatsLeft = max(0, (int) $session['seats_left']);
                        $isFull = $seatsLeft === 0;
                        $badgeClass = $isFull
                            ? 'badge-full'
                            : ($seatsLeft <= 3 ? 'badge-warn' : 'badge-ok');
                        ?>

                        <form
                            class="session-row"
                            action="../services/user/book.php"
                            method="POST"
                        >
                            <input
                                type="hidden"
                                name="session_id"
                                value="<?= (int) $session['id'] ?>"
                            >

                            <div class="when">
                                <strong>
                                    <?= e(date(
                                        'D, d M',
                                        strtotime($session['session_date'])
                                    )) ?>
                                </strong>

                                <span>
                                    <?= e(substr($session['start_time'], 0, 5)) ?>
                                </span>
                            </div>

                            <span class="badge <?= $badgeClass ?>">
                                <?= $isFull ? 'Sold out' : $seatsLeft . ' left' ?>
                            </span>

                            <input
                                type="number"
                                name="seats"
                                value="1"
                                min="1"
                                max="<?= $seatsLeft ?>"
                                aria-label="Number of seats"
                                required
                                <?= $isFull ? 'disabled' : '' ?>
                            >

                            <button
                                type="submit"
                                class="btn btn-sm"
                                <?= $isFull ? 'disabled' : '' ?>
                            >
                                Reserve
                            </button>
                        </form>
                    <?php endforeach; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>

    <section id="my-reservations" class="card section">
        <h2>My reservations</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tour</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Seats</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!$myReservations): ?>
                        <tr>
                            <td colspan="6">You have no reservations yet.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($myReservations as $r): ?>
                        <tr>
                            <td>
                                <?= e($r['tour_name']) ?><br>
                                <small><?= e($r['museum_name']) ?></small>
                            </td>

                            <td>
                                <?= e(date(
                                    'd M Y',
                                    strtotime($r['session_date'])
                                )) ?>
                            </td>

                            <td><?= e(substr($r['start_time'], 0, 5)) ?></td>

                            <td><?= (int) $r['number_of_seats'] ?></td>

                            <td>
                                <?php if ($r['status'] === 'confirmed'): ?>
                                    <span class="badge badge-ok">Confirmed</span>
                                <?php else: ?>
                                    <span class="badge badge-muted">Cancelled</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($r['status'] === 'confirmed'): ?>
                                    <form
                                        action="../services/user/cancel.php"
                                        method="POST"
                                        data-confirm="Cancel this reservation?"
                                    >
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $r['id'] ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                        >
                                            Cancel
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer class="footer">&copy; Museum Tours</footer>

<script>
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
</script>
</body>
</html>
