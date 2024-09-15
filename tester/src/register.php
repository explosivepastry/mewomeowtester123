<?php

include 'includes/conn.php';
include 'includes/theme/head.php';
include 'includes/theme/header.php';
include 'includes/theme/carousel.php';

if ($_SESSION['authenticated'] == true) {
    header("Location: /dashboard.php");
}

if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    // form data

    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name  = filter_input(INPUT_POST, 'last_name',  FILTER_SANITIZE_STRING);
    $username   = filter_input(INPUT_POST, 'username',   FILTER_SANITIZE_STRING);
    $email      = filter_input(INPUT_POST, 'email',      FILTER_SANITIZE_STRING);
    $password   = filter_input(INPUT_POST, 'password');
    $pass_conf  = filter_input(INPUT_POST, 'pass_conf');

    // error handling 

    $errors   = array();
    $pass_len = 6;

    if (strlen($password) < $pass_len) {
        $errors[] = "Your password must be longer than $pass_len characters.";
    }
    if ($password !== $pass_conf) {
        $errors[] = "The password confirmation field must be the same as the password.";
    }

    if (empty($username)) {
        $errors[] = "Your username cannot be blank.";
    }
    if (empty($first_name)) {
        $errors[] = "Your first name cannot be blank.";
    }
    if (empty($last_name)) {
        $errors[] = "Your last name cannot be blank.";
    }
    if (empty($email)) {
        $errors[] = "Your email cannot be blank.";
    }

    $check_user_query = $pdo->prepare("SELECT count(1) FROM users WHERE username = :username");
    $check_user_query->bindParam(':username', $username);
    $check_user_query->execute();

    $user_exists = $check_user_query->fetchColumn();
    if ($user_exists) {
        $errors[] = "Username unavailable.";
    }


    // registration flow

    if (!$errors) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $insert_user_query = $pdo->prepare("INSERT INTO users (username, fname, lname, passwd, email) VALUES (:user, :fname, :lname, :passwd, :email)");

        $insert_user_query->bindParam(':user', $username);
        $insert_user_query->bindParam(':fname', $first_name);
        $insert_user_query->bindParam(':lname', $last_name);
        $insert_user_query->bindParam(':passwd', $hashed);
        $insert_user_query->bindParam(':email', $email);
        $insert_user_query->execute();

        $check_user_query->execute();
        $user_exists = $check_user_query->fetchColumn();
        if ($user_exists) {
            echo success("Registration successful.");
            header("Refresh:1, /login.php");
        } else {
            echo failure("Something went wrong. Maybe the robots have seized control?");
        }
    } else {
        echo failure($errors);
    }
}
?>
<div class="container abs">
    <br><br>
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card border-0 shadow rounded-3 my-5">
                <div class="card-body p-4 p-sm-5">
                    <h1 class="card-title text-center mb-5 fw-light fs-1">Job Portal Registration</h1>

                    <form action="/register.php" method="post">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Username">
                            <label for="floatingInput">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" name="email" placeholder="Email">
                            <label for="floatingInput">Email</label>
                        </div>
                        <hr class="my-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" name="first_name" placeholder="First Name">
                            <label for="floatingInput">First Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" name="last_name" placeholder="Last Name">
                            <label for="floatingInput">Last Name</label>
                        </div>
                        <hr class="my-4">
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
                            <label for="floatingPassword">Password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="floatingPassword" name="pass_conf" placeholder="Confirm Password">
                            <label for="floatingPassword">Confirm Password</label>
                        </div>
                        <hr class="my-4">
                        <div class="d-grid">
                            <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Sign
                                up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/theme/footer.php' ?>