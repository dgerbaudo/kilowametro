<?php

include('Stats.php');
$period = $_GET['period'];
$res = null;

if (isset($period)) {
    $stats = new Stats();
    $chartData = array(['Provincia', 'Costo kWh']);
    $tableData = array(['Provincia', 'Cant. registros', 'Total kWh', 'Total gastado', 'Promedio de kWh',
        'Promedio gastado', 'Promedio costo por kWh', 'Mínimo costo por kWh', 'Máximo costo por kWh']);
    foreach ($stats->getData($_GET['period']) as $value) {
        $chartData[] = [$value->province, (float)$value->avg_amount_per_kwh];
        $tableData[] = [$value->province, (int)$value->numrec, (int)$value->sum_kwh, (float)$value->sum_amount, (float)$value->avg_kwh,
            (float)$value->avg_amount, (float)$value->avg_amount_per_kwh, (float)$value->min_amount_per_kwh, (float)$value->max_amount_per_kwh];
    }
    $res = array('status' => 'success', 'chartData' => $chartData, 'tableData' => $tableData);
} else {
    $res = array('status' => 'danger', 'info' => 'Ha ocurrido un error.');
}

echo json_encode($res, JSON_UNESCAPED_UNICODE);
