<?php

include 'dbh.php';

$content_type = $_POST['content_type'];
$id = $_POST['id'];

if ($content_type === "klantdata") {
  $sql = "SELECT * FROM `test-klant-data` WHERE Klant like '$id';";
} else if ($content_type === "onderhoud") {
  $sql = "SELECT username FROM users WHERE id LIKE '$id';";
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
    $data = array('klant' => $row['klant'],
                  'pc_type' => $row['pc_type'],
                  'date' => $row['date'],
                  'medewerker' => $row['medewerker']);
    echo json_encode($data);
  } else if ($content_type === "onderhoud") {
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

//DEFUNCT CODE

require 'login/cookie.php';

$valid_cookie = json_decode(checkLoginCookie());

if ($valid_cookie->{"status"} !== "success") {
  echo '{"status": "error", "msg": "not logged in"}';
  exit;
}