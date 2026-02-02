<?php
require_once __DIR__ . '/models/Customer.php';
require_once __DIR__ . '/models/OnderhoudModel.php';
require_once __DIR__ . '/models/OverigeModel.php';
require_once __DIR__ . '/models/Reminder.php';


?>

<html>
  <head>
    <title>CRM FBCS</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/media-styles.css">
  </head>
  <body>
    <div class="container">
      <div id="dashboard">
        <header id="states">
          <div class="userstate">
            <?php echo $_COOKIE["name"]; ?>
            <button id="logout"> Log uit </button>
          </div>
          <div id="klantdata" class="state">
            Dashboard
            <select name="remindertype" id="remindertypeselector">
              <option value="alles">Alles</option>
              <option value="hosting">Onderhoudjes Overzicht</option>
              <option value="mail">Nieuwe PC klaarmaken Overzicht</option>
              <option value="domainname">Overige Werk</option>
            </select>
          </div>
          <div id="history" class="state">Geschiedenis</div>
          <div id="customers" class="state"> Klanten </div>
        </header>
        <div id="dashboard-content">

        </div>
      </div>
      <div id="create">
        <header id="forms">
          <div id="hosting">Onderhoud Klanten</div>
        </header>
        <div id="form">
        </div>
      </div>
    </div>
  </body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="js/form.js"></script>
<script src="js/dashboard.js"></script>
