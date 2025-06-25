<?php
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        header('Location: index.php?page=dashboard');
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TMS Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        background: #181818;
        min-height: 100vh;
        font-family: 'Inter', Arial, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .main-login-wrapper {
        border: 1px solid rgb(43, 43, 43);

        border-radius: 28px;
        box-shadow: 0 8px 40px #181818;
        display: flex;
        overflow: hidden;
        max-width: 900px;
        width: 100%;
        min-height: 520px;
    }

    .login-left {
        background: #181818;
        color: #fff;
        flex: 1.1;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        min-width: 320px;
    }

    .login-left-content {
        z-index: 2;
        position: relative;
        padding: 48px 32px;
        width: 100%;
    }

    .login-left h2 {
        font-size: 2.1rem;
        font-weight: 600;
        margin-bottom: 18px;
        letter-spacing: -1px;
    }

    .login-left .blurred-bg {
        position: absolute;
        left: 0;
        bottom: 0;
        right: 0;
        height: 60%;
        background: linear-gradient(90deg, #ffb86c 0%, #ff6a00 60%, #181818 100%);
        filter: blur(32px);
        opacity: 0.7;
        z-index: 1;
    }

    .login-right {
        flex: 1.3;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        padding: 48px 32px;
    }

    .login-form-box {
        width: 100%;
        max-width: 370px;
    }

    .login-logo {
        width: 48px;
        height: 48px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-logo span {
        display: inline-block;
        width: 48px;
        height: 48px;
        background: conic-gradient(from 0deg, #ffb86c, #ff6a00, #ffb86c 80%);
        border-radius: 50%;
    }

    .login-form-box h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #181818;
    }

    .login-form-box p {
        color: #888;
        margin-bottom: 28px;
    }

    .form-label {
        font-weight: 500;
        color: #222;
    }

    .form-control {
        border-radius: 10px;
        border: 1.5px solid #ececec;
        background: #fafafa;
        font-size: 1.08rem;
    }

    .form-control:focus {
        border-color: #ff6a00;
        box-shadow: 0 0 0 2px #ffb86c33;
    }

    .btn-orange {
        background: #ff6a00;
        color: #fff;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1.1rem;
        border: none;
        padding: 12px 0;
        margin-top: 8px;
        transition: background 0.18s;
    }

    .btn-orange:hover {
        background: #e65c00;
    }

    .login-form-box .form-text {
        color: #888;
        font-size: 0.97rem;
    }

    .login-form-box .text-link {
        color: #ff6a00;
        text-decoration: none;
        font-weight: 500;
    }

    .login-form-box .text-link:hover {
        text-decoration: underline;
    }

    .alert-danger {
        font-size: 0.98rem;
    }

    @media (max-width: 900px) {
        .main-login-wrapper {
            flex-direction: column;
            min-height: unset;
        }

        .login-left,
        .login-right {
            min-width: unset;
            padding: 32px 16px;
        }

        .login-left {
            justify-content: flex-start;
        }
    }
    </style>
</head>

<body>
    <div class="main-login-wrapper mx-auto my-5">
        <div class="login-left position-relative">
            <div class="login-left-content">
                <h2>Convert your Hardworks<br>into successful<br>Career.</h2>
            </div>
            <div class="blurred-bg"></div>
        </div>
        <div class="login-right">
            <div class="login-form-box">
                <div class="login-logo"></div>
                <h3>Get Started</h3>
                <p>Welcome to Sprints Pro — Let’s get started</p>
                <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Your username or email</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <div class="invalid-feedback">Please enter your username or email.</div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">Please enter your password.</div>
                    </div>
                    <button type="submit" class="btn btn-orange w-100">Sign In</button>
                </form>
                <div class="text-center mt-4">
                    <small class="form-text">Already have an account? <a href="login.php"
                            class="text-link">Login</a></small>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Bootstrap validation
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
    </script>
</body>

</html>