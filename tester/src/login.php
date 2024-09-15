<?php

include 'includes/conn.php';
include 'includes/theme/head.php';
include 'includes/theme/header.php';
include 'includes/theme/carousel.php';

if ($_SESSION['authenticated'] == true) {
    header("Location: /dashboard.php");
}

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password');

    $check_creds_query = $pdo->prepare("SELECT id, role_id, passwd, fname, lname FROM users WHERE username = :username");
    $check_creds_query->bindParam(':username', $username);
    $check_creds_query->execute();
    $result = $check_creds_query->fetch();

    if ($result != false) {

        $acc_id     = $result['id'];
        $acc_role   = $result['role_id'];
        $acc_passwd = $result['passwd'];
        $acc_first  = $result['fname'];
        $acc_last   = $result['lname'];

        if (password_verify($password, $acc_passwd)) {
            session_regenerate_id();
            $_SESSION['authenticated'] = true;
            $_SESSION['name'] = "$acc_first $acc_last";
            $_SESSION['id'] = $acc_id;
            $_SESSION['role'] = $acc_role;
            header("Location: /dashboard.php");
        } else {
            echo failure("Invalid credentials.");
        }
    } else {
        echo failure("Invalid username or password.");
    }
}
?>
<div class="container abs" style="margin-top:10%; margin-bottom:10%;">
    <br><br>
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card border-0 shadow rounded-3 my-5">
                <div class="card-body p-4 p-sm-5">
                    <h1 class="card-title text-center mb-5 fw-light fs-1">Portal Login</h1>

                    <form action="/login.php" method="post">

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Username">
                            <label for="floatingInput">Username</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                            <label for="floatingPassword">Password</label>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid" style="padding: 5px">
                            <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Sign in</button>
                        </div>
                        <div style="display: flex;">
                            <div style="padding: 5px;flex: 1;">
                                <a href="/register.php" class="btn btn-Light btn-login text-uppercase fw-bold">Register Account</a>
                            </div>
                            <div style="padding: 5px;flex: 1;">
                                <a href="/forgot.php" class="btn btn-Light btn-login text-uppercase fw-bold">Forgot Password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>