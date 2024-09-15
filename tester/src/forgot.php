<?php

include 'includes/conn.php';
include 'includes/theme/head.php';
include 'includes/theme/header.php';
include 'includes/theme/carousel.php';


if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

    $check_user_query = $pdo->prepare("SELECT count(1) FROM users WHERE username = :username");
    $check_user_query->bindParam(':username', $username);
    $check_user_query->execute();

    $user_exists = $check_user_query->fetchColumn();

    if ($user_exists) {

        $get_user_query = $pdo->prepare("SELECT id, username, email FROM users WHERE username = :username");
        $get_user_query->bindParam(':username', $username);
        $get_user_query->execute();

        $row = $get_user_query->fetch();

        $id         = $row['id'];
        $username   = $row['username'];
        $email      = $row['email'];

        $deactivate_existing_token = $pdo->prepare("UPDATE reset_tokens SET active = 0 WHERE user_id = :id");
        $deactivate_existing_token->bindParam(':id', $id);
        $deactivate_existing_token->execute();

        $token_life = new DateInterval('PT1H'); // 2 hours
        $expiration = new DateTime('NOW');
        $expiration = $expiration->add($token_life);


        // Generate the token
        $token      = "1";
        $token_len  = 3;

        $c = 1;
        while ($c <= $token_len) {
            $token = $token . strval(rand(0, 9));
            $c++;
        }

        $insert_token = $pdo->prepare("INSERT INTO reset_tokens (user_id, token, expires, active) VALUES (:id, :token, :expires, 1)");
        $insert_token->bindParam(':id', $id);
        $insert_token->bindParam(':token', $token);
        $insert_token->bindParam(':expires', $expiration->format('Y-m-d H:i:s'));
        $insert_token->execute();

        echo success("You will receive a password reset token on your email.");
        header("Refresh:1, /reset.php?username=$username");

        // <insert some code that emails tokens here>

    } else {

        echo failure("User not found!");
        header("Refresh:2, /forgot.php");
    }
}

?>
<div class="container abs">
    <br><br>
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card border-0 shadow rounded-3 my-5">
                <div class="card-body p-4 p-sm-5">
                    <h1 class="card-title text-center mb-5 fw-light fs-1">Request Reset</h1>
                    <h4 class="text-warning text-center">Forgotten your password?</h4>
                    <p class="text-center">Enter your username below to request a password reset. A reset token will be sent to the email address on your account.<br></p>
                    <br>
                    <form action="/forgot.php" method="post">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Enter your username.">
                            <label for="floatingInput">Enter your username here.</label>
                        </div>
                        <input type="hidden" value="1" name="reset" />
                        <hr class="my-4">
                        <div class="d-grid">
                            <div class="d-grid" style="padding: 5px">
                                <button type="submit" class="btn btn-primary btn-login text-uppercase fw-bold">Request password reset.</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/theme/footer.php'; ?>