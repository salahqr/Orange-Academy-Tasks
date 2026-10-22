<?php
declare(strict_types=1);
session_start();

if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/../model/tours.php";
require_once __DIR__ . "/../model/reservations.php";
require_once __DIR__ . "/../model/tourSessions.php";

$tourModel        = new Tour();
$reservationModel = new Reservation();
$sessionModel     = new TourSession();

$tours    = $tourModel->getAll();
$sessions = $sessionModel->getAll();

$tourById = [];
foreach ($tours as $t) {
    $tourById[$t['id']] = $t;
}

// Reservations section: only when ?session=ID is in the URL
$selectedSession = null;
$reservations    = [];
if (isset($_GET['session'])) {
    $selectedSession = $sessionModel->find((int) $_GET['session']);
    if ($selectedSession) {
        $reservations = $reservationModel->getBySession((int) $selectedSession['id']);
    }
}

// Dashboard numbers, calculated from data we already loaded
$now = time();
$upcomingCount = 0;
$seatsBooked   = 0;
$seatsLeft     = 0;
foreach ($sessions as $s) {
    $isUpcoming = strtotime($s['session_date'] . ' ' . $s['start_time']) > $now;
    if ($isUpcoming) {
        $upcomingCount++;
        $seatsLeft += (int) $s['seats_left'];
    }
    $seatsBooked += (int) $s['total_seats'] - (int) $s['seats_left'];
}

// Messages sent back by the services (e.g. ../view/admin.php?error=...)
$error   = $_GET['error']   ?? null;
$success = $_GET['success'] ?? null;

// Helpers
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function seatsBadge(int $left): string
{
    if ($left <= 0)  return 'badge-full';
    if ($left <= 3)  return 'badge-warn';
    return 'badge-ok';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Museum Tours</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
</head>
<body>
    <header class="topbar">
        <a href="admin.php" class="brand">Museum Tours <span class="tag">Admin</span></a>
        <nav>
            <a href="#overview" class="active">Overview</a>
            <a href="#tours">Tours</a>
            <a href="#sessions">Sessions</a>
            <a href="#reservations">Reservations</a>
            <a href="logout.php" class="nav-out">Log out</a>
        </nav>
    </header>

    <main class="container">
        <section id="overview" class="page-head">
            <h1>Dashboard</h1>
            <p>Manage tours, schedules, and reservations.</p>
        </section>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat"><span>Tours</span><strong><?= count($tours) ?></strong></div>
            <div class="stat"><span>Upcoming sessions</span><strong><?= $upcomingCount ?></strong></div>
            <div class="stat"><span>Seats booked</span><strong><?= $seatsBooked ?></strong></div>
            <div class="stat"><span>Seats left (upcoming)</span><strong><?= $seatsLeft ?></strong></div>
        </div>

        <!-- ================= TOURS ================= -->
        <section id="tours" class="card section">
            <h2>Tours</h2>

            <form class="form-grid" action="../services/admin/add.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="tour">

                <div class="field">
                    <label for="t-name">Name</label>
                    <input type="text" id="t-name" name="name" maxlength="30" required>
                </div>
                <div class="field">
                    <label for="t-museum">Museum name</label>
                    <input type="text" id="t-museum" name="museum_name" maxlength="30" required>
                </div>
                <div class="field">
                    <label for="t-price">Price</label>
                    <input type="number" id="t-price" name="price" min="0" step="0.01" value="0.00" required>
                </div>
              <div class="field">
    <label for="t-image">Tour image</label>
    <input type="file" id="t-image" name="image" accept="image/png,image/jpeg,image/webp">
    <small class="hint">JPG, PNG or WebP, max 2 MB.</small>
</div>
                <div class="field full">
                    <label for="t-desc">Description</label>
                    <textarea id="t-desc" name="description" rows="3" maxlength="255"></textarea>
                </div>
                <div class="field full">
                    <button type="submit" class="btn">Add tour</button>
                </div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>ID</th><th>Name</th><th>Museum</th><th>Price</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!$tours): ?>
                            <tr><td colspan="5">No tours yet.</td></tr>
                        <?php endif; ?>

                        <?php foreach ($tours as $t): ?>
                        <tr>
                            <td><?= e($t['id']) ?></td>
                            <td><?= e($t['name']) ?></td>
                            <td><?= e($t['museum_name']) ?></td>
                            <td>$<?= number_format((float) $t['price'], 2) ?></td>
                            <td class="actions">
                                <a class="btn btn-sm btn-outline"
                                   href="update.php?type=tour&id=<?= (int) $t['id'] ?>">Edit</a>
                                <form action="../services/admin/delete.php" method="POST"
                                      data-confirm="Delete this tour? All its sessions and reservations will be deleted too.">
                                    <input type="hidden" name="type" value="tour">
                                    <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ================= SESSIONS ================= -->
        <section id="sessions" class="card section">
            <h2>Sessions</h2>

            <form class="form-grid" action="../services/admin/add.php" method="POST">
                <input type="hidden" name="type" value="session">

                <div class="field">
                    <label for="s-tour">Tour</label>
                    <select id="s-tour" name="tour_id" required>
                        <option value="">Choose a tour</option>
                        <?php foreach ($tours as $t): ?>
                            <option value="<?= (int) $t['id'] ?>"><?= e($t['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="s-date">Date</label>
                    <input type="date" id="s-date" name="session_date" required>
                </div>
                <div class="field">
                    <label for="s-time">Start time</label>
                    <input type="time" id="s-time" name="start_time" required>
                </div>
                <div class="field">
                    <label for="s-seats">Total seats</label>
                    <input type="number" id="s-seats" name="total_seats" min="1" value="20" required>
                </div>
                <div class="field full">
                    <button type="submit" class="btn">Add session</button>
                </div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>ID</th><th>Tour</th><th>Date</th><th>Time</th><th>Booked / Total</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!$sessions): ?>
                            <tr><td colspan="6">No sessions yet.</td></tr>
                        <?php endif; ?>

                        <?php foreach ($sessions as $s):
                            $booked   = (int) $s['total_seats'] - (int) $s['seats_left'];
                            $tourName = $tourById[$s['tour_id']]['name'] ?? 'Unknown tour';
                        ?>
                        <tr>
                            <td><?= e($s['id']) ?></td>
                            <td><?= e($tourName) ?></td>
                            <td><?= e(date('d M Y', strtotime($s['session_date']))) ?></td>
                            <td><?= e(substr($s['start_time'], 0, 5)) ?></td>
                            <td>
                                <span class="badge <?= seatsBadge((int) $s['seats_left']) ?>">
                                    <?= $booked ?> / <?= (int) $s['total_seats'] ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a class="btn btn-sm btn-outline"
                                   href="admin.php?session=<?= (int) $s['id'] ?>#reservations">Bookings</a>
                                <a class="btn btn-sm btn-outline"
                                   href="update.php?type=session&id=<?= (int) $s['id'] ?>">Edit</a>
                                <form action="../services/admin/delete.php" method="POST"
                                      data-confirm="Delete this session? Its reservations will be deleted too.">
                                    <input type="hidden" name="type" value="session">
                                    <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ================= RESERVATIONS ================= -->
        <section id="reservations" class="card section">
            <?php if ($selectedSession):
                $rTour = $tourById[$selectedSession['tour_id']]['name'] ?? 'Unknown tour';
            ?>
                <h2>Reservations
                    <small>
                        <?= e($rTour) ?> &middot;
                        <?= e(date('d M Y', strtotime($selectedSession['session_date']))) ?>,
                        <?= e(substr($selectedSession['start_time'], 0, 5)) ?>
                    </small>
                </h2>
            <?php else: ?>
                <h2>Reservations <small>Click "Bookings" on a session to see who booked it.</small></h2>
            <?php endif; ?>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>ID</th><th>Visitor</th><th>Email</th><th>Seats</th><th>Booked at</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php if ($selectedSession && !$reservations): ?>
                            <tr><td colspan="7">No reservations for this session yet.</td></tr>
                        <?php endif; ?>

                        <?php foreach ($reservations as $r): ?>
                        <tr>
                            <td><?= e($r['id']) ?></td>
                            <td><?= e($r['username']) ?></td>
                            <td><?= e($r['email']) ?></td>
                            <td><?= (int) $r['number_of_seats'] ?></td>
                            <td><?= e(date('d M Y, H:i', strtotime($r['created_at']))) ?></td>
                            <td>
                                <?php if ($r['status'] === 'confirmed'): ?>
                                    <span class="badge badge-ok">Confirmed</span>
                                <?php else: ?>
                                    <span class="badge badge-muted">Cancelled</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($r['status'] === 'confirmed'): ?>
                                <form action="../services/admin/update.php" method="POST"
                                      data-confirm="Cancel this reservation?">
                                    <input type="hidden" name="type" value="reservation_cancel">
                                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                                    <input type="hidden" name="session_id" value="<?= (int) $selectedSession['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
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
            form.addEventListener('submit', (e) => {
                if (!confirm(form.dataset.confirm)) e.preventDefault();
            });
        });
    </script>
</body>
</html>