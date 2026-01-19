<?php

require 'login/cookie.php';

$valid_cookie = json_decode(checkLoginCookie());

if ($valid_cookie->{"status"} !== "success") {
  echo '{"status": "error", "msg": "not logged in"}';
  exit;
}

include 'dbh.php';

$type = addslashes($_POST['type']); #filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS); # $_POST['type'];
$date_start = $_POST['date_start'];
$months = $_POST['interval'];
$next_due = $_POST['next_due'];
$amount = $_POST['amount'];
$optional = addslashes($_POST['optional']); #filter_input(INPUT_POST, 'optional', FILTER_SANITIZE_SPECIAL_CHARS); #$_POST['optional'];
$created_by = $_POST['created_by'];
$customer_id = $_POST['customer_id'];
$reminder_id = $_POST['reminder_id'];

$sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_NAME = 'remindersnew' AND TABLE_SCHEMA LIKE '$mysqldb');";
$result = $conn->query($sql);
if (!$conn->query($sql)) {
  echoResponse("error", $sql."<br/>".$conn->error);
}

if ($result->num_rows == 0) {
  $sql = 'CREATE TABLE remindersnew (reminder_id int(11) NOT NULL AUTO_INCREMENT, type varchar(15) NOT NULL, date_start DATE NOT NULL, date_stop DATE, months int(2) NOT NULL, next_due DATE NOT NULL, amount int(2) NOT NULL, optional varchar(100), created_by varchar(20) NOT NULL, customer_id varchar(11) NOT NULL, created TIMESTAMP default CURRENT_TIMESTAMP, PRIMARY KEY (reminder_id));';
  if (!$conn->query($sql)) {
    echoResponse("error", $sql."<br/>".$conn->error);
  }
}

$sql = "SELECT customer FROM remindersnew WHERE customer LIKE '$customer';";
$result = $conn->query($sql);

if ($reminder_id !== 'null') {
  //UPDATE EXISTING REMINDER
  $date_stop = $_POST["date_stop"];

  if ($date_stop == "" || $date_stop == 'null') {
    $date_stop = "NULL";
  } else if ($date_stop !== null) {
    $date_stop = "'".$date_stop."'";
  }

  $sql = "UPDATE remindersnew SET
          type = '$type',
          date_start = '$date_start',
          date_stop = $date_stop,
          next_due = '$next_due',
          months = '$months',
          amount = '$amount',
          optional = '$optional',
          created_by = '$created_by'
          WHERE reminder_id LIKE '$reminder_id';";
  if (!$conn->query($sql)) {
    echoResponse("error", $sql."<br/>".$conn->error);
  } else {
    echoResponse("success", "update was succesful");
  }
} else {
  //INSERT NEW REMINDER
  $sql = "INSERT INTO remindersnew (type, date_start, months, next_due, customer_id, amount, optional, created_by) VALUES ('$type','$date_start','$months','$next_due','$customer_id','$amount','$optional','$created_by');";
  if (!$conn->query($sql)) {
    echoResponse("error", $sql."<br/>".$conn->error);
  } else {
    echoResponse("success", "added successfully");
  }
}

function echoResponse($status, $msg) {
  global $conn;
  $conn->close();
  $res = array("status" => $status,
                "msg" => $msg);
  echo json_encode($res);
  exit;
}
?>