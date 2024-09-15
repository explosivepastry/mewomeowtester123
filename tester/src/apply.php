<?php

    include 'includes/conn.php';
    include 'includes/theme/head.php';

    if ($_SESSION['authenticated'] != true) { die(header("Location: /login.php")); }

    if ($_SESSION["REQUEST_METHOD"] = POST)
    {
        $job_id = filter_input(INPUT_POST, 'job_id', FILTER_SANITIZE_STRING);

        echo strval($job_id);
        
        $insert_application = $pdo->prepare("INSERT INTO applications (user_id, job_id) VALUES (:user_id, :job_id)");
        $insert_application->bindParam(':user_id', $sess_id);
        $insert_application->bindParam(':job_id', $job_id);
        $insert_application->execute();
    }

    header("Location: /dashboard.php");
?>