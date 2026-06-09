<?php
$pageTitle = 'Browse Pets';
require_once 'includes/db_connect.inc';

$stmt = mysqli_prepare($conn,
    "SELECT p.*, u.username AS owner_name FROM pets p
     JOIN users u ON p.user_id = u.user_id
     ORDER BY p.created_at DESC");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pets = $result->fetch_all(MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<h1 class="mb-4">All Available Pets</h1>

<?php if (empty($pets)): ?>
    <div class="text-center py-5">
        <span class="material-icons" style="font-size: 4rem; color: var(--text-muted);">pets</span>
        <h2 class="mt-3">No pets available for adoption.</h2>
        <p class="text-muted">Please check back later!</p>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-5 col-lg-4 mb-4">
            <img src="assets/images/pets_banner.jpg" alt="Pets Banner"
                 class="img-fluid rounded w-100" style="object-fit: cover;">
        </div>
        <div class="col-md-7 col-lg-8 mb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>SPECIES</th>
                            <th>BREED</th>
                            <th>SIZE</th>
                            <th>FEE ($)</th>
                            <th>OWNER</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pets as $pet): ?>
                        <tr>
                            <td>
                                <a href="details.php?id=<?= (int)$pet['pet_id'] ?>">
                                    <?= htmlspecialchars($pet['name']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($pet['species']) ?></td>
                            <td><?= htmlspecialchars($pet['breed'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($pet['size']) ?></td>
                            <td><?= number_format($pet['adoption_fee'], 2) ?></td>
                            <td>
                                <a href="owner.php?user_id=<?= (int)$pet['user_id'] ?>">
                                    <?= htmlspecialchars($pet['owner_name']) ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
require_once 'includes/footer.inc';
