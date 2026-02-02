<?php
// Sample data array to replace database results
$sample_data = [
    [
        'Klant' => 'Bedrijf A',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-15',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf B',
        'Pctype' => 'Laptop',
        'Datum' => '2024-02-20',
        'Medewerker' => 'Piet Pieters'
    ],
    [
        'Klant' => 'Bedrijf C',
        'Pctype' => 'Server',
        'Datum' => '2024-03-10',
        'Medewerker' => 'Marie de Vries'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ],
    [
        'Klant' => 'Bedrijf D',
        'Pctype' => 'Desktop',
        'Datum' => '2024-01-25',
        'Medewerker' => 'Jan Jansen'
    ]

];

$content_type = isset($_POST['content_type']) ? $_POST['content_type'] : 'klantdata';
$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : '';
$filter_type = isset($_POST['filter_type']) ? $_POST['filter_type'] : 'alles';

// Return the data with a header
echo '<div class="list-container">';
if ($content_type !== "klantdata") {  // Changed from "klant-data" to "klantdata"
  echo '<div> Klant </div>';
}
echo '<div> Klant </div>
      <div> Datum </div>
      <div> PC Type </div>
      <div> Checklist Onderhoud </div>
      <div> Overige Notities </div>
      <div> Medewerker </div>
      <div> Bewerken </div>
    </div>';

// Loop through sample data
if (count($sample_data) > 0) {
  foreach ($sample_data as $row) {
    echo '<div class="list-container">';

    if ($content_type !== "klantdata") {  // Changed from "klant-data" to "klantdata"
      echo '<div> '.$row["Klant"].' </div>';
    }

    echo '<div> '.$row["Klant"].' </div>
          <div> '.$row["Datum"].' </div>
          <div> '.$row["Pctype"].' </div>
          <div> - </div>
          <div> - </div>
          <div> '.$row["Medewerker"].' </div>
          <button class="infobutton"> Info </button>
          </div>';
  }
}

if ($content_type === "klantdata") {  // Changed from "klant-data" to "klantdata"
  echo '<div class="list-container">
          <button id="new-reminder"> Toevoegen </button>
        </div>';
}
?>