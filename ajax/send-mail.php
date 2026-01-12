<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require 'dbh.php';

$today = new DateTime();
$now = $today->format('Y-m-d H:i:s');
$today = $today->format('Y-m-d');
// $today = "2021-05-27";
// $today = "2023-10-28";

echo $today . "<br/>";

$log_file = 'mail-log/' . $today . '.txt';

$smtp_server = 'mail.web-fuse.nl';
$smtp_port = 587; // USING STARTTLS

$name_from = 'FBCS reminders';
$mail_from = 'reminder@fbcs.nl';
$mail_from_password = 'jdv4uqDUwtkiiwpz';

$name_to = 'Info FBCS';
$mail_to = 'info@fbcs.nl';

$log_pointer = fopen($log_file, "a");

fwrite($log_pointer, "---------------------------------------------------------------------\nDate: $now\n");

//check if the reminder table exists
$sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_NAME = 'remindersnew' AND TABLE_SCHEMA LIKE '$mysqldb');";
if (!$result = $conn->query($sql)) {//log sql errors
  echo "error: ".$sql."\n".$conn->error."<br/>";
  fwrite($log_pointer, "error: ".$sql."\n".$conn->error."\n");
  $conn->close();
  exit;
}

if ($result->num_rows == 0) {
  echo "error: the reminder table has not been created yet<br/>";
  fwrite($log_pointer, "error: the reminder table has not been created yet\n");
  $conn->close();
  exit;
}

// check if history table exists
$sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_NAME = 'history' AND TABLE_SCHEMA LIKE '$mysqldb');";
$result = $conn->query($sql);
if (!$conn->query($sql)) {
  echo "error: ".$sql."\n".$conn->error."<br/>";
  fwrite($log_pointer, "error: ".$sql."\n".$conn->error."\n");
  $conn->close();
  exit;
}
// create the table if it does not exist
if ($result->num_rows == 0) {
  $sql = 'CREATE TABLE history (reminder_id int(11) NOT NULL, send_date DATE default CURRENT_TIMESTAMP);';
  if (!$conn->query($sql)) {
    echo "error: ".$sql."\n".$conn->error."<br/>";
    fwrite($log_pointer, "error: ".$sql."\n".$conn->error."\n");
    $conn->close();
    exit;
  }
}

// find reminders for today
$sql = "SELECT c.customer_id, c.customer, r.type, r.next_due, r.created_by, r.reminder_id, r.months, r.amount, r.optional FROM remindersnew r INNER JOIN customers c ON r.customer_id = c.customer_id WHERE r.next_due LIKE '$today' ORDER BY customer_id";

if (!$result = $conn->query($sql)) {//log sql errors
  echo "error: ".$sql."\n".$conn->error."<br/>";
  fwrite($log_pointer, "error: ".$sql."\n".$conn->error."\n");
  $conn->close;
  exit;
}

if ($result->num_rows == 0) {
  echo "success: er zijn geen reminders voor vandaag<br/>";
  fwrite($log_pointer, "success: er zijn geen reminders voor vandaag\n");
  $conn->close();
  exit;
}

$products = '';
$remindersMessage = '';
date_default_timezone_set('Europe/Amsterdam');
require '../vendor/autoload.php';
$reminder_id_arr = array();
$next_due_arr = array();

echo "sending reminders to $mail_to<br/>";
fwrite($log_pointer, "sending reminders to $mail_to\n");

$clients = array();

while ($row = $result->fetch_assoc()) {
  $id = $row["customer_id"];
  if (empty($clients[$id])) $clients[$id] = array();
  array_push($clients[$id], $row);
}

foreach($clients as $c) {
  $lastClientRow = end($c);

  $reminderBody = '';
  $products = '';
  foreach($c as $r) {
    $reminderBody .= composeBody($r);
    $products .= $r["type"] . ", ";
  }
  $products = rtrim($products, ",");

  $customer = $lastClientRow["customer"];
  $customer_id = $lastClientRow["customer_id"];

  $mail = new PHPMailer;
  //Tell PHPMailer to use SMTP - requires a local mail server
  //Faster and safer than using mail()
  //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
  $mail->isSMTP();                                            // Send using SMTP
  $mail->Host       = $smtp_server;                    // Set the SMTP server to send through
  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
  $mail->Username   = $mail_from;                     // SMTP username
  $mail->Password   = $mail_from_password;                               // SMTP password
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
  $mail->Port       = $smtp_port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
  $mail->SMTPOptions = array(
    'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
    )
  );

  // Send mail using sendmail
  // $mail->isMail();

  //Use a fixed address in your own domain as the from address
  //**DO NOT** use the submitter's address here as it will be forgery
  //and will cause your messages to fail SPF checks
  $mail->setFrom($mail_from, $name_from);
  //Send the message to yourself, or whoever should receive contact for submissions
  $mail->addAddress($mail_to, $name_to);
  //Put the submitter's address in a reply-to header
  //This will fail if the address provided is invalid,
  //in which case we should ignore the whole request
  if (!$mail->addReplyTo($mail_from, $name_from)) {
    echo "error: invalid recipient mail address<br/>";
    fwrite($log_pointer, "error: invalid recipient mail address\n");
    continue;
  }
  $mail->Subject = 'Reminder: Product is verlopen voor '.$customer;
  $mail->isHTML(true);
  $mail->Body = '
    <style type="text/css">
      .container {
        width: 600px;
      }
      .text {
        padding: 0 20%;
        padding: 20px;
        text-align: left;
      }
      .message {
        padding: 1rem;
        margin: 1rem 0;
        background-color: #EEEEEE;
        line-height: 1.25rem;
        border-radius: .5rem;
      }
      .standout {
        border-radius: 5px;
        /*background-color: #D1D1D1;*/
        font-weight: bold;
      }
    </style>
    <div class="container">
      <img width="400px" height="auto" style="margin-left: 100px;" src="https://fbcs.nl/wp-content/uploads/2020/02/FBCS-Stransparant.png" alt="Uw bericht is ontvangen!" />
      <div class="text">
        <p>Goedemorgen,</p>
        <h3>' . $customer . ' (' . $customer_id . ')</h3>
        '.$reminderBody.'
        <p>Reageer niet op dit bericht.</p>
        <p>Gemaakt door Luc Appelman</p>
      </div>
    </div>';
  $mail->AltBody = '
    Goedemorgen,

    Kon het volledige bericht niet weergeven omdat HTML niet word ondersteunt.
    Ga naar de website voor meer info, er zijn producten verlopen voor '.$customer.' (klantnummer '.$customer_id.').

    Reageer niet op dit bericht.
    Gemaakt door Luc Appelman'
  ;

  echo "sending reminder:<br/>&emsp;--product: $products<br/>--customer: $customer<br/>";
  fwrite($log_pointer, "sending reminder:\n    --product: $products\n--customer: $customer\n");

  // Send the message, check for errors
  if (!$mail->send()) {
    echo "error: could not send mail<br/>";
    echo $mail->ErrorInfo;
    fwrite($log_pointer, "error: could not send mail\n$mail->ErrorInfo\n");
    exit;
  }

  echo "success:<br/>&emsp;send reminder successful<br/>";
  fwrite($log_pointer, "success:\n    send reminder successful\n");

  foreach ($c as $r) {
    $reminder_id = $r["reminder_id"];
    $interval = $r["months"];
    $next_due = $r["next_due"];
    $new_next_due = date("Y-m-d", strtotime("+$interval months", strtotime($next_due)));


    $sql = "INSERT INTO history (reminder_id, send_date) VALUES ('$reminder_id','$today');";
    if (!$conn->query($sql)) {
      echo "error: ".$sql."\n".$conn->error."<br/>";
      fwrite($log_pointer, "error: ".$sql."\n".$conn->error."\n");
    }

    $sql = "UPDATE remindersnew SET next_due = '$new_next_due' WHERE reminder_id LIKE '$reminder_id';";
    if (!$conn->query($sql)) {
      echo "error: ".$sql."\n".$conn->error."<br/>";
      fwrite($log_pointer, "error: ".$sql."\n".$conn->error."\n");
    }
  }

  echo "&emsp;updated history and next reminder date<br/>";
  fwrite($log_pointer, "    updated history and next reminder date\n");
}

fclose($log_pointer);

function composeBody($row) {
  $customer_id = $row["customer_id"];
  $customer = $row["customer"];
  $type = $row["type"];
  $reminder_id = $row["reminder_id"];
  $amount = $row["amount"];
  $optional = $row["optional"];
  $interval = $row["months"];

  if ($type === "hosting") {
    $remindersMessage = '
<div class="message">
  Dit is een reminder voor het product <span class="standout">Web-Hosting</span>.<br/>
  De klant heeft <span class="standout">'.$amount.'</span> websites en in totaal <span class="standout">'.$optional.' uitbreidingen</span> van 250 MB.<br/>';
  } else if ($type === "mail") {
    $remindersMessage = '
<div class="message">
  Dit is een reminder voor het product <span class="standout">Microsoft 365</span>.<br/>
  De klant heeft <span class="standout">'.$amount.'</span> keer het abbonnement <span class="standout">'.$optional.'</span>.<br/>';
  } else if ($type === "ssl") {
    $certificateText = "certificaat";
    if ($amount !== "1") $certificateText = "certificaten";

    $remindersMessage = '
<div class="message">
  Dit is een reminder voor het product <span class="standout">SSL Certificaat</span>.<br/>
  De klant heeft <span class="standout">'.$amount.'</span> SSL '.$certificateText.'.<br/>';
  } else if ($type === "domainname") {
    $domainText = "domeinnaam";
    if ($amount !== "1") $domainText = "domeinnamen";

    $remindersMessage = '
<div class="message">
  Dit is een reminder voor het product <span class="standout">Domeinnaam</span>.<br/>
  De klant heeft <span class="standout">'.$amount.'</span> '.$domainText.' met de extensie <span class="standout">'.$optional.'</span>.<br/>';
  } else if ($type === "cloudcare") {
    $subscriptionText = "abbonnement";
    if ($amount !== "1") $subscriptionText = "abbonnementen";

    $remindersMessage = '
<div class="message">
  Dit is een reminder voor het product <span class="standout">Cloudcare</span>.<br/>
  De klant heeft <span class="standout">'.$amount.'</span> beveiligings '.$subscriptionText.'.<br/>';
  } else if ($type === "cloudbackup") {
    $subscriptionText = "abbonnement";
    if ($amount !== "1") $subscriptionText = "abbonnementen";

    $remindersMessage = '
<div class="message">
  Dit is een reminder voor het product <span class="standout">Cloud Backup</span>.<br/>
  De klant heeft <span class="standout">'.$amount.'</span> backup '.$subscriptionText.' van 50GB.<br/>';
  } else if ($type === "onderhoud") {
    $subscriptionText = "computer";
    if ($amount !== "1") $subscriptionText = "computers";

    $remindersMessage = '
<div class="message">
  Dit is een reminder voor het product <span class="standout">Onderhouds Abbonement</span>.<br/>
  De klant heeft <span class="standout">'.$amount.'</span> '.$subscriptionText.' in onderhoud met het plan <span class="standout">'.$optional.'</span>.<br/>';
  } else if ($type === "verhuur") {
    $subscriptionText = "apparaat";
    if ($amount !== "1") $subscriptionText = "apparaten";

    $remindersMessage = '
<div class="message">
  Dit is een reminder voor het product <span class="standout">Verhuur Apparatuur</span>.<br/>
  De klant heeft <span class="standout">'.$amount.'</span> '.$subscriptionText.' met de omschrijving <span class="standout">'.$optional.'</span> gehuurd.<br/>';
  } else if ($type === "voip") {
    $subscriptionText = "abbonnement";
    if ($amount !== "1") $subscriptionText = "abbonnementen";

    $remindersMessage = '
<div class="message">
  Dit is een reminder voor het product <span class="standout">VOIP</span>.<br/>
  De klant heeft <span class="standout">'.$amount.'</span> VOIP '.$subscriptionText.'.<br/>';
  } else if ($type === "internet") {
    $remindersMessage = '
<div class="message">
  Dit is een reminder voor het product <span class="standout">Internet</span>.<br/>
  De klant heeft <span class="standout">'.$amount.'</span> keer internet van het type <span class="standout">'.$optional.'</span>.<br/>';
  }

  $intervalText = "maand";
  if ($interval !== "1") $intervalText = "maanden";

  $remindersMessage .= '
  Het product wordt per <span class="standout">'.$interval.' '.$intervalText.'</span> gefactureerd.<br/>
  Voor de volledige informatie klik <a href="https://fbcs.nl/reminder?id='.$reminder_id.'">hier</a>.
</div>';

  return $remindersMessage;
}