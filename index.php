<?php


?>

<html>
  <head>
    <title> FBCS Reminder </title>
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
          <div id="upcoming" class="state">
            Dashboard
            <select name="remindertype" id="remindertypeselector">
              <option value="alles">Alles</option>
              <option value="hosting">Web-Hosting</option>
              <option value="mail">Microsfot 365</option>
              <option value="domainname">Domeinnaam</option>
              <option value="ssl">SSL Certificaat</option>
              <option value="cloudcare">Cloudcare</option>
              <option value="cloudbackup">Cloud Backup</option>
              <option value="onderhoud">Onderhouds Abonnement</option>
              <option value="verhuur">Verhuur Apparatuur</option>
              <option value="voip">VOIP</option>
              <option value="internet">Internet</option>
            </select>
          </div>
          <div id="history" class="state"> History </div>
          <div id="customers" class="state"> Klanten </div>
        </header>
        <div id="dashboard-content">

        </div>
      </div>
      <div id="create">
        <header id="forms">
          <div id="hosting"> Web-Hosting en SSL </div>
          <div id="mail"> Microsoft 365 </div>
          <div id="domainname"> Domeinnaam </div>
          <div id="ssl"> SSL certificaat </div>
          <div id="cloudcare"> Cloudcare </div>
          <div id="cloudbackup"> Cloud Backup </div>
          <div id="onderhoud"> Onderhouds Abonnement </div>
          <div id="verhuur"> Verhuur Apparatuur </div>
          <div id="voip"> VOIP </div>
          <div id="internet"> Internet </div>
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
