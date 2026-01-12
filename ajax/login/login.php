<?php 

include 'dbh.php';

function generateRandomString($length) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

//get post variables
$username = $_POST["username"];
$password = $_POST["password"];

//verify login
$sql = "SELECT password FROM users where username LIKE '$username';";
if (!$result = $conn->query($sql)) {
  echoResponse("error", $sql."<br/>".$conn->error);
}

if ($result->num_rows < 1) {
  echoResponse("error", "could not find user");
}

$row = $result->fetch_assoc(); //there should only be one result so there is no while loop required

$hash= $row["password"];
if (!password_verify($password, $hash)) {
  echoResponse("error", "invalid password");
}

//check if cookies table exists
$sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_NAME = 'cookies' AND TABLE_SCHEMA LIKE '$mysqldb');";
if (!$result = $conn->query($sql)) {
  echoResponse("error", $sql."<br/>".$conn->error);
}
//create the table if it does not exist
if ($result->num_rows == 0) {
  $sql = 'CREATE TABLE cookies (username varchar(20) NOT NULL, uuid varchar(64), expires DATETIME NOT NULL);';
  if (!$conn->query($sql)) {
    echoResponse("error", $sql."<br/>".$conn->error);
  }
}

//get next month in seconds and DateTime
$month = new DateInterval("P1M");
$nextMonth = new DateTime();
$nextMonth->add($month);
$nextMonth = $nextMonth->format("Y-m-d H:i:s");

$nextMonthInSeconds = mktime().time()+strtotime("+1 month");

$expires = $nextMonthInSeconds;
$path = '/';
$domain = 'reminder.fbcs.nl';
$secure = true;

$uuid = generateRandomString(64); //this will be the uuid/cookie

if (!setcookie("name", $username, time() + $nextMonthInSeconds, $path, $domain, $secure)) {
  echoResponse("error", "could not set name cookie");
}

if (!setcookie("uuid", $uuid, time() + $nextMonthInSeconds, $path, $domain, $secure)) {
  echoResponse("error", "could not set uuid cookie");
}

//insert new user with encrypted password
$sql = "INSERT INTO cookies (username, uuid, expires) VALUES ('$username','$uuid','$nextMonth');";
if (!$conn->query($sql)) {
  echoResponse("error", $sql."<br/>".$conn->error);
}

echoResponse("success", "login was successful");

function echoResponse($status, $msg) {
  global $conn;
  $conn->close();
  $res = array("status" => $status,
                "msg" => $msg);
  echo json_encode($res);
  exit;
}

?>