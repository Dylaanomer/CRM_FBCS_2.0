<?php

require 'login/cookie.php';

$valid_cookie = json_decode(checkLoginCookie());

if ($valid_cookie->{"status"} !== "success") {
  echo '{"status": "error", "msg": "not logged in"}';
  exit;
}

include 'dbh.php';

$customer_id = $_POST['customer_id'];
$customer = addslashes($_POST['customer']); #filter_input(INPUT_POST, 'customer', FILTER_SANITIZE_SPECIAL_CHARS); #$_POST['customer'];
$note = addslashes($_POST['note']); #filter_input(INPUT_POST, 'note', FILTER_SANITIZE_SPECIAL_CHARS); #$_POST['note'];

if ($note === 'undefined') {
    $note = null;
}

$sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_NAME = 'customers' AND TABLE_SCHEMA LIKE '$mysqldb');";
$result = $conn->query($sql);
if (!$conn->query($sql)) {
  echoResponse("error", $sql."<br/>".$conn->error);
}

if ($result->num_rows == 0) {
  $sql = 'CREATE TABLE customers (customer_id int(11) NOT NULL, customer varchar(50) NOT NULL, note varchar(255), PRIMARY KEY (customer_id));';
  if (!$conn->query($sql)) {
    echoResponse("error", $sql."<br/>".$conn->error);
  }
}

$sql = "SELECT customer_id FROM customers WHERE customer_id LIKE '$customer_id';";
if (!$result = $conn->query($sql)) {
  echoResponse("error", $sql."<br/>".$conn->error);
}

if ($result->num_rows > 0) {
  //UPDATE EXISTING customer
  $sql = "UPDATE customers SET
          customer = '$customer',
          note = '$note'
          WHERE customer_id LIKE '$customer_id';";
  if (!$conn->query($sql)) {
    echoResponse("error", $sql."<br/>".$conn->error);
  } else {
    echoResponse("success", "update was succesful");
  }
} else {
  //INSERT NEW customer
  $sql = "INSERT INTO customers (customer_id, customer, note) VALUES ('$customer_id','$customer','$note');";
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