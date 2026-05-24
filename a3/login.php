<?php
$pageTitle = 'Login';
require_once 'includes/db_connect.inc';

// 已登录则重定向
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = '请输入用户名和密码。';
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
            $_SESSION['flash'] = ['type' => 'success', 'message' => '登录成功！'];
            header('Location: index.php');
            exit;
        } else {
            $error = '用户名或密码错误。';
        }
    }
}

require_once 'includes/header.inc';
require_once 'includes/nav.inc';
?>

<h1 class="mb-4">登录</h1>

<div class="row">
    <div class="col-md-6 mx-auto">
        <?php if ($error !== null): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label for="username" class="form-label">用户名</label>
                <input type="text" name="username" id="username" class="form-control"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">密码</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">登录</button>
                <a href="register.php" class="btn btn-outline-light">没有账号？注册</a>
            </div>
        </form>
    </div>
</div>

<?php
require_once 'includes/footer.inc';
