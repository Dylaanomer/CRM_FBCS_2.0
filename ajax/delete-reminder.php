<?php

require 'login/cookie.php';

$valid_cookie = json_decode(checkLoginCookie());

if ($valid_cookie->{"status"} !== "success") {
  echo '{"status": "error", "msg": "not logged in"}';
  exit;
}

include 'dbh.php';

$id = $_POST['id'];

$sql ="DELETE FROM remindersnew WHERE reminder_id LIKE '$id';";
if (!$conn->query($sql)) {
  echoResponse("error", $sql."<br/>".$conn->error);
} else {
  echoResponse("success", "deleted succesfully");
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