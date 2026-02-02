<?php
// Sample data arrays to replace database results
$sample_klantdata = [
    [
        'Klant' => 'Bedrijf A',
        'pc_type' => 'Desktop',
        'date' => '2024-01-15',
        'medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf B',
        'pc_type' => 'Laptop',
        'date' => '2024-02-20',
        'medewerker' => 'Piet Pieters'
    ],
    [
        'Klant' => 'Bedrijf C',
        'pc_type' => 'Server',
        'date' => '2024-03-10',
        'medewerker' => 'Marie de Vries'
    ],
    [
        'Klant' => 'Bedrijf D',
        'pc_type' => 'Desktop',
        'date' => '2024-01-25',
        'medewerker' => 'Jan Jansen'
    ]
];

$sample_customers = [
    [
        'customer_id' => 'CUST001',
        'customer' => 'Bedrijf A',
        'note' => 'Belangrijke klant'
    ],
    [
        'customer_id' => 'CUST002',
        'customer' => 'Bedrijf B',
        'note' => 'Standaard onderhoud'
    ],
    [
        'customer_id' => 'CUST003',
        'customer' => 'Bedrijf C',
        'note' => 'Server onderhoud gepland'
    ],
    [
        'customer_id' => 'CUST004',
        'customer' => 'Bedrijf D',
        'note' => ''
    ]
];

$sample_users = [
    [
        'id' => '1',
        'username' => 'Jan Jansen'
    ],
    [
        'id' => '2',
        'username' => 'Piet Pieters'
    ],
    [
        'id' => '3',
        'username' => 'Marie de Vries'
    ]
];

$content_type = isset($_POST['content_type']) ? $_POST['content_type'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';

$result_found = false;
$data = null;

if ($content_type === "klantdata") {
    // Search for matching Klant
    foreach ($sample_klantdata as $row) {
        if (stripos($row['Klant'], $id) !== false) {
            $data = array(
                'klant' => $row['Klant'],
                'pc_type' => $row['pc_type'],
                'date' => $row['date'],
                'medewerker' => $row['medewerker']
            );
            $result_found = true;
            break;
        }
    }
    
    if ($result_found) {
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => "no results"));
    }
    
} else if ($content_type === "reminder") {
    // Search for matching reminder (using klantdata)
    foreach ($sample_klantdata as $row) {
        if (stripos($row['Klant'], $id) !== false) {
            $data = array(
                'klant' => $row['Klant'],
                'pc_type' => $row['pc_type'],
                'date' => $row['date'],
                'medewerker' => $row['medewerker']
            );
            $result_found = true;
            break;
        }
    }
    
    if ($result_found) {
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => "no results"));
    }
    
} else if ($content_type === "onderhoud") {
    // Search for matching user
    foreach ($sample_users as $row) {
        if ($row['id'] === $id) {
            $data = array(
                'username' => $row['username']
            );
            $result_found = true;
            break;
        }
    }

    if ($result_found) {
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => "no results"));
    }

} else if ($content_type === "customer") {
    // Search for matching customer by customer_id
    foreach ($sample_customers as $row) {
        if ($row['customer_id'] === $id) {
            $data = array(
                'customer' => $row['customer'],
                'note' => $row['note']
            );
            $result_found = true;
            break;
        }
    }

    if ($result_found) {
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => "no results"));
    }

} else if ($content_type === "customers") {
    // Return all customers that match the search
    $data = array();
    foreach ($sample_customers as $row) {
        if (empty($id) || stripos($row['customer'], $id) !== false || stripos($row['customer_id'], $id) !== false) {
            $arr = array(
                'customer' => $row['customer'],
                'customer_id' => $row['customer_id']
            );
            array_push($data, $arr);
        }
    }

    if (count($data) > 0) {
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => "no results"));
    }
}
?>

//DEFUNCT CODE

require 'login/cookie.php';

$valid_cookie = json_decode(checkLoginCookie());

if ($valid_cookie->{"status"} !== "success") {
  echo '{"status": "error", "msg": "not logged in"}';
  exit;
}