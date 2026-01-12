<?php

require 'login/cookie.php';

$valid_cookie = json_decode(checkLoginCookie());

if ($valid_cookie->{"status"} !== "success") {
  echo '{"status": "error", "msg": "not logged in"}';
  exit;
}

include 'dbh.php';

$content_type = $_POST['content_type'];
$id = $_POST['id'];

if ($content_type === "reminder") {
  $sql = "SELECT * FROM remindersnew WHERE reminder_id like '$id';";
} else if ($content_type === "customer") {
  $sql = "SELECT customer, note FROM customers WHERE customer_id LIKE '$id';";
} else if ($content_type === "customers") {
  $sql = "SELECT customer, customer_id FROM customers WHERE customer LIKE '%$id%';";
}

//find the data that corresponds to the row

$result = $conn->query($sql);
if (!$conn->query($sql)) {
  echo "error: ".$sql."<br/>".$conn->error;
  http_response_code(500);
  $conn->close();
  exit;
}

if ($result->num_rows > 0) {
  if ($content_type === "reminder") {
    $row = $result->fetch_assoc();
  
    //set data from row into table so it can be send in json format
    $data = array('type' => $row['type'],
                  'date_start' => $row['date_start'],
                  'date_stop' => $row['date_stop'],
                  'interval' => $row['months'],
                  'amount' => $row['amount'],
                  'optional' => $row['optional'],
                  'customer_id' => $row['customer_id']);
  
    echo json_encode($data);
  } else if ($content_type === "customer") {
    $row = $result->fetch_assoc();
    $arr = array('customer' => $row['customer'],
                  'note' => $row['note']);

    echo json_encode($arr);
  } else if ($content_type === "customers") {
    $data = array();

    while ($row = $result->fetch_assoc()) {
      $arr = array('customer' => $row['customer'],
                    'customer_id' => $row['customer_id']);

      array_push($data, $arr);
    }

    echo json_encode($data);
  }
} else {
  echo json_encode(array('error' => "no results"));
}

$conn->close();
?>