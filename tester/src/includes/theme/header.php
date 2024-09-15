<?php include 'config.php' ?>

<body>

  <nav class='navbar navbar-expand-lg navbar-dark' style="box-shadow: 0px 0px;">
    <div class='container-fluid'>
      <a class='navbar-brand' href='/dashboard.php'>
        <img src="/assets/images/trilocor_logo.png" style="margin-right: 10px;" width="50" height="50" class="d-inline-block align-bottom" alt="">
        <?php echo $app_name ?>
      </a>
      <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarColor01' aria-controls='navbarColor01' aria-expanded='false' aria-label='Toggle navigation'>
        <span class='navbar-toggler-icon'></span>
      </button>
      <?php if ($_SESSION['authenticated'] && $_SESSION['role'] == 0) {
        echo "<div style='float: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'><h2>Flag: " . file_get_contents('/opt/auth_flag.txt') . "</h2></div>";
      } ?>
      <div class='navbar-lg' id='navbarColor01'>
        <ul class='navbar-nav me-auto'>
          <?php include 'nav.php'; ?>
        </ul>
      </div>
    </div>

  </nav>