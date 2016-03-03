<?php
include("Connection.php");

$data = array();

if (isset($_GET['province_id']) && is_numeric($_GET['province_id']) && isset($_GET['q'])) {
    $province = (int)$_GET['province_id'];
    $conn = Connection::getConnection();
    $q = $conn->real_escape_string($_GET['q']);
    $query = "SELECT * FROM city WHERE province_id = $province AND (LOWER(city_name) like LOWER('%$q%') OR cp = '$q') ORDER BY city_name";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_object()) {
            $data[] = array('id' => $row->id, 'text' => $row->city_name . " (" . $row->cp . ")");
        }
        $result->free();
    }
    $conn->close();
} else {
    $data[] = array('id' => '-1', 'text' => 'No se encontraron resultados');
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);


