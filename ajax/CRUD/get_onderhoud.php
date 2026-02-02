<?php

require_once '../../../models/OnderhoudModel.php';

header('Content-Type: application/json');

try {
    $model = new OnderhoudModel();

    if (isset($_GET['id'])) {
        $onderhoud = $model->find($_GET['id']);
        echo json_encode($onderhoud);
    } else {
        $onderhouden = $model->getAll();
        echo json_encode($onderhouden);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}