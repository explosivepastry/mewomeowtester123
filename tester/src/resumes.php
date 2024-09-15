<?php
include 'includes/conn.php';
include 'includes/theme/head.php';
include 'includes/theme/header.php';

if ($_SESSION['authenticated'] != true or $_SESSION['role'] != 0 or stripos($_SERVER['HTTP_USER_AGENT'], "sqlmap")) {
    die(header("Location: /login.php"));
}

?>

<style>
table {
    border-collapse: separate;
    border-spacing: 0;
    min-width: 350px;
}

table tr th,
table tr td {
    padding: 5px;
}

tbody {
    line-height: 50px;
}

table tr th {
    text-align: left;
}

table tr:first-child th:first-child,
table.Info tr:first-child td:first-child {
    border-top-left-radius: 20px;
}

table tr:first-child th:last-child,
table.Info tr:first-child td:last-child {
    border-top-right-radius: 20px;
}

table tr:last-child td:first-child {
    border-bottom-left-radius: 20px;
}

table tr:last-child td:last-child {
    border-bottom-right-radius: 20px;
}
</style>

<body id="tower-bg">
    <h1 class="display-3" style="text-align: center; margin-top: 100px;">User Resume Submissions</h1>
    <table class="table table-hover" style="width: 80%; margin: 40px 10% 10%;">
        <thead>
            <tr>
                <th scope="col">
                    <form id="resumesearch" name="resumesearch" method="POST">
                        <select name="search" class="form-select bg-secondary" style="width: 50%; border-radius: 10px;"
                            onchange='document.getElementsByName("resumesearch")[0].submit();'>
                            <option value="all">Username</option>
                            <?php
                            $sql = 'select username from resumes as resume INNER JOIN ( select id, username from users ) as u on resume.user_id = u.id;';
                            $user_query = $pdo->prepare("$sql");
                            $user_query->execute();

                            $users = array();
                            while ($row = $user_query->fetch()) {
                                $username = $row['username'];
                                echo "<option value='$username'>$username</option>";
                            }
                            ?>
                        </select>
                    </form>
                </th>
                </div>
                <th scope="col">File Name</th>
                <th scope="col">Upload Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_REQUEST['search'])) {
                $sql = "select * from resumes as resume INNER JOIN ( select id, username from users ) as u on resume.user_id = u.id where username='" . $_REQUEST['search'] . "';";
                // sqlmap prevention
                $badwords = ["/sleep/i", "/0x/i", "/\*\*/", "/-- [a-z0-9]{4}/i", "/ifnull/i"];
                foreach ($badwords as $badword) {
                    if (preg_match($badword, $_REQUEST['search'])) {
                        die(header("Location: /resumes.php"));
                    }
                }
            } else {
                $sql = "select * from resumes as resume INNER JOIN ( select id, username from users ) as u on resume.user_id = u.id;";
            }
            $user_query = $pdo->prepare("$sql");
            $user_query->execute();

            $users = array();
            while ($row = $user_query->fetch()) {
                echo ' <tr class="table-secondary">';
                echo '    <td>' . $row['username'] . '</td>';
                echo '    <td><a href=/uploads/' . $row['file_name'] . '>' . $row['file_name'] . '</a></td>';
                echo '    <td>' . $row['upload_date'] . '</td>';
                echo '  </tr>';
            }
            ?>
            </div>
        </tbody>
    </table>
</body>