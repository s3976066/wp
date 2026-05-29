<?php
$pageTitle = 'Register';
require_once 'includes/db_connect.inc';

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');

    $old = ['username' => $username, 'email' => $email, 'phone' => $phone, 'location' => $location];

    if ($username === '') $errors[] = 'Username is required.';
    if ($email === '') $errors[] = 'Email is required.';
    if ($password === '') $errors[] = 'Password is required.';

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if ($password !== '' && strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== '' && $password !== $passwordConfirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = 'Username already taken.';
        }
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = 'Email already registered.';
        }
        mysqli_stmt_close($stmt);
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn,
            "INSERT INTO users (username, email, password, phone, location) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sssss', $username, $email, $hash, $phone, $location);

        if (mysqli_stmt_execute($stmt)) {
            $newId = mysqli_insert_id($conn);
            session_regenerate_id(true);
            $_SESSION['user_id'] = $newId;
            $_SESSION['username'] = $username;
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Registration successful! Welcome to PetConnect!'];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Registration failed. Please try again.';
        }
        mysqli_stmt_close($stmt);
    }
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<h1 class="mb-4">Register</h1>

<div class="row">
    <div class="col-md-6 mx-auto">
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username *</label>
                <input type="text" name="username" id="username" class="form-control"
                       value="<?= htmlspecialchars($old['username'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" name="email" id="email" class="form-control"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password * (min. 8 characters)</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirm Password *</label>
                <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control"
                       value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control"
                       value="<?= htmlspecialchars($old['location'] ?? '') ?>">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Register</button>
                <a href="login.php" class="btn btn-outline-light">Already have an account? Log in</a>
            </div>
        </form>
    </div>
</div>

<?php
require_once 'includes/footer.inc';
