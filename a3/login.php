<?php
$pageTitle = 'Login';
require_once 'includes/db_connect.inc';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter your username and password.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT user_id, username, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Login successful!'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<h1 class="mb-4">Login</h1>

<div class="row">
    <div class="col-md-6 mx-auto">
        <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="register.php" class="btn btn-outline-light">Don&rsquo;t have an account? Register</a>
            </div>
        </form>
    </div>
</div>

<?php
require_once 'includes/footer.inc';
