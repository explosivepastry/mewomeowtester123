<?php

include 'includes/theme/head.php';
include 'includes/theme/header.php';
include 'includes/conn.php';

if ($_SESSION['authenticated'] != true) {
    die(header("Location: /login.php"));
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_FILES['resume'])) {
        die(failure("No file uploaded."));
    }

    $file_path = $_FILES['resume']['tmp_name'];
    $file_size = filesize($file_path);
    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = finfo_file($file_info, $file_path);

    $allowed = ['application/pdf' => 'pdf'];

    if (!in_array($file_type, array_keys($allowed)) || !preg_match('/^.*\.pdf$/', basename($_FILES["resume"]["name"]))) {
        die(failure("File not allowed. Only PDF files can be uploaded."));
    }

    $file_name = md5($_SESSION['id']);
    $extension = $allowed[$file_type];
    $full_file_name = "$file_name.$extension";

    $upload_path = __DIR__ . "/uploads";

    $new_file_path = "$upload_path/$full_file_name";

    if (!copy($file_path, $new_file_path)) {
        die(failure("Couldn't upload file. Have you tried turning it off and back on again?"));
    }

    unlink($file_path);

    $resume_query = $pdo->prepare("SELECT count(1) FROM resumes WHERE user_id = :id");
    $resume_query->bindParam(':id', $_SESSION['id']);
    $resume_query->execute();

    $resume_exists = $resume_query->fetchColumn();

    if ($resume_exists) {
        $update_resume = $pdo->prepare("UPDATE resumes SET file_name = :full_file_name WHERE user_id = :id");
        $update_resume->bindParam(':full_file_name', $full_file_name);
        $update_resume->bindParam(':id', $_SESSION['id']);
        $update_resume->execute();
    } else {
        $cur_date = new DateTime('NOW');

        $insert_resume = $pdo->prepare("INSERT INTO resumes (user_id, file_name, upload_date) VALUES (:id, :full_file_name, :upload_date)");
        $session_id = intval($_SESSION['id']);
        $insert_resume->bindParam(':id', $session_id);
        $insert_resume->bindParam(':full_file_name', $full_file_name);
        $insert_resume->bindParam(':upload_date', $cur_date->format('Y-m-d H:i:s'));
        $insert_resume->execute();
    }

    echo success("Resume uploaded successfully!");
}

?>

<body id="tower-bg">
    <div class='container' style="margin-top:10%; margin-bottom:10%;">
        <br><br>
        <div class='row'>
            <div class='col-sm-9 col-md-7 col-lg-5 mx-auto'>
                <div class='card border-0 shadow rounded-3 my-5'>
                    <div class='card-body p-4 p-sm-5'>
                        <h1 class='card-title text-center mb-5 fw-light fs-1'>Upload Your Resume</h1>

                        <form action='/upload.php' method='post' enctype='multipart/form-data'>
                            <div style='width:200px;margin:auto;'>
                                <img src='/assets/images/document.png' style='width:100%; padding:10px;' />
                            </div>
                            <div class='form-group'>
                                <label for='formFile' class='form-label mt-4'>Please attach your resume in PDF format.</label>
                                <input class='form-control' type='file' id='formFile' name="resume" accept=".pdf">
                            </div>
                            <hr class="my-4">
                            <div class="d-grid">
                                <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Upload</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>