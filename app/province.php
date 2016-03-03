<?php

include("Connection.php");

$conn = Connection::getConnection();

$query = "SELECT * FROM province ORDER BY province_name";

$data = array();

if ($result = $conn->query($query)) {

    while ($row = $result->fetch_object()) {
        $data[] = array('id' => $row->id, 'text' => $row->province_name);
    }

    $result->free();
}

$conn->close();

echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>