<?php

require 'login/cookie.php';

$valid_cookie = json_decode(checkLoginCookie());

if ($valid_cookie->{"status"} !== "success") {
  echo '{"status": "error", "msg": "not logged in"}';
  exit;
}

include 'dbh.php';

$content_type = $_POST['content_type'];
$customer_id = $_POST['customer_id'];
$filter_type = $_POST['filter_type'];

//what data is requested?
if ($content_type === "upcoming") {
  if ($filter_type === "alles") {
    $sql = "SELECT r.type, r.next_due, r.created_by, r.reminder_id, c.customer FROM remindersnew r INNER JOIN customers c ON r.customer_id = c.customer_id ORDER BY r.next_due ASC;";
  } else {
    $sql = "SELECT r.type, r.next_due, r.created_by, r.reminder_id, c.customer FROM remindersnew r INNER JOIN customers c ON r.customer_id = c.customer_id WHERE r.type LIKE '$filter_type' ORDER BY r.next_due ASC;";
  }
} else if ($content_type === "history") {
  $sql = "SELECT r.type, c.customer, r.next_due, r.created_by, r.reminder_id FROM remindersnew r INNER JOIN history h ON r.reminder_id = h.reminder_id INNER JOIN customers c ON c.customer_id = r.customer_id ORDER BY h.send_date DESC;";
} else if ($content_type === "customer") {
  $sql = "SELECT type, next_due, created_by, reminder_id FROM remindersnew WHERE customer_id LIKE '$customer_id';";
}

$result = $conn->query($sql);
if (!$conn->query($sql)) {
  echo "error: ".$sql."<br/>".$conn->error;
  http_response_code(500);
  $conn->close();
}

//return the data with a header
echo '<div class="list-container">';

if ($content_type !== "customer") {
  echo '<div> Klant </div>';
}
echo '<div> Type </div>
      <div> Datum </div>
      <div> Medewerker </div>
      <div> Bewerken </div>
    </div>';

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $next_due = date("Y-m-d", strtotime($row["next_due"]));
    $now = new DateTime();
    $now = $now->format("Y-m-d");
  
    if ($now > $next_due && $content_type === "upcoming") {
      echo '<div class="list-container error">';
    } else {
      echo '<div class="list-container">';
    }
  
    if ($content_type !== "customer") {
      echo '<div> '.$row["customer"].' </div>';
    }
    
    echo '<div> '.$row["type"].' </div>        
          <div> '.$row["next_due"].' </div>
          <div> '.$row["created_by"].' </div>
          <button id="'.$row["reminder_id"].'" class="infobutton"> Info </button>
          </div>';
  }
}
if ($content_type === "customer") {
  echo '<div class="list-container"> 
          <button id="new-reminder"> Toevoegen </button>
        </div>';
}

$conn->close();
?>