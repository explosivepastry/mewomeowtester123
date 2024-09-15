<?php

include 'includes/conn.php';
include 'includes/theme/head.php';
include 'includes/theme/header.php';

if ($_SESSION['authenticated'] != true) {
    die(header("Location: /login.php"));
} else if ($_SESSION['role'] == 0) {
    die(header("Location: /resumes.php"));
}

?>

<body id="tower-bg">
    <div>
        <section class="left-column note" style="border-width: 0px;">
            <figure>
                <blockquote class="blockquote">
                    <h4>Welcome, <strong class="text-primary"><?php echo "$name!"; ?> </strong></h4>
                    <?php if ($_SESSION['role'] != 0) : ?>
                        <br />
                        <p>You've successfully registered with Trilocor Job Portal. Congratulations! That's the first step in your <span class="text-primary">Trilocor-Journey™</span></p>
                        <p>Here at Trilocor, we believe the world doesn't stop for anyone- that's why we are committed to revolutionizing the field
                            of robotics and constantly push at the outer-bounds of what is possible. We are committed to being <strong>the</strong> industry
                            leader in our field, and to do that, we need talent.</p>
                        <p>We are looking for highly motivated and driven individuals to join our team. <abbr title="Ideal candidates should also have very little regard for their own safety.">If you love technology and have a strong desire to change
                                the world</abbr>, please review our job postings and see if any are a match.
                        </p>
                        <p>I look forward to speaking with you!</p><br />
                        <figcaption class="blockquote-footer">
                            Roy Batty - <cite title="Source Title">Talent Acquisition Manager</cite>
                        </figcaption>
                    <?php endif; ?>
                </blockquote>
            </figure>

        </section>

        <section class="right-column">

            <?php
            if ($_SESSION['role'] != 0) {
                function applyForm($job_id, $applied_jobs)
                {
                    if (in_array($job_id, $applied_jobs)) {
                        $form = "<span class='badge bg-warning float-right'>Application Submitted</span>";
                    } else {
                        $form = "<form method='post' action='/apply.php' class='float-right'/>
                        <input type='hidden' name='job_id' value='$job_id' />

                        <button type='submit' class='btn btn-success float-right'>Apply</button>
                     </form>";
                    }

                    return ($form);
                }


                $apps_query = $pdo->prepare("SELECT * FROM applications WHERE user_id = :user_id");
                $apps_query->bindParam(':user_id', $sess_id);
                $apps_query->execute();

                $applied_jobs = array();
                while ($row = $apps_query->fetch()) {
                    $applied_jobs[] = $row['job_id'];
                }


                $jobs_query = $pdo->query("SELECT * FROM jobs");
                while ($row = $jobs_query->fetch()) {
                    $id          = $row['id'];
                    $title       = $row['title'];
                    $description = $row['description'];
                    $location    = $row['location'];
                    $salary      = $row['salary'];

                    if ($salary == Null) {
                        $salary = "Undisclosed.";
                    }

                    $job_card = "
        <div class='job-card card mb-3' style='max-width: 20rem;'>
            <div class='card-header text-muted'>$location</div>
            <div class='card-body'>
                <h4 class='card-title'>$title</h4>
                <p class='card-text'>$description</p>
                <p class='card-text'>
                " . applyForm($id, $applied_jobs) . "
                    <strong>Salary ($):</strong> $salary
                </p>
            </div>
        </div><br/>";

                    echo $job_card;
                }
            }
            ?>
        </section>
    </div>
</body>