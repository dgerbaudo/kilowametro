<?php
include("Connection.php");
require('recaptcha/autoload.php');

define("PROVINCE_CABA", 2);
define("CITY_CABA", 5001);

$res = null;
$secret = 'Clave de recaptcha';

$recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\CurlPost());
$remoteIp = $_SERVER['REMOTE_ADDR'];
$gRecaptchaResponse = $_POST['g-recaptcha-response'];

$resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
if (isset($gRecaptchaResponse) && $resp->isSuccess()) {
	$conn = Connection::getConnection();

	$province = (int) $_POST['province'];
	$city = (int) $_POST['city'];	
	$period = $conn->real_escape_string($_POST['period']) . '-01 00:00:00';
	$days = (int) $_POST['days'];
	$kWh = (int) $_POST['kWh'];
	$amount = $conn->real_escape_string($_POST['amount']);
	$amount = str_replace('.', '', $amount);
	$amount = str_replace(',', '.', $amount);
	
	if ($province == PROVINCE_CABA) {
		$city = CITY_CABA;	
	}
	
	if (isset($province) && isset($city) && isset($period)  && isset($days) && isset($kWh)  && isset($amount) 
			&& $days > 0 && $kWh > 0 && $amount > 0) {
		$stmt = $conn->prepare("INSERT INTO `kwm_data`(`province_id`, `city_id`, `period`, `days`, `kwh`, `amount`) VALUES (?, ?, ?, ?, ?, ?)");

		$stmt->bind_param('iisiid', $province, $city, $period, $days, $kWh, $amount);
		
		$stmt->execute();
		
		$stmt->close();

		$res =  array('status' => 'success', 'info' => '¡Gracias por su colaboración!');
	} else {
	    $res =  array('status' => 'danger', 'info' => 'Todos los campos son requeridos y deben ser mayores a cero.');
	}

	$conn->close();

} else {
   	$res =  array('status' => 'danger', 'info' => 'Error al validar el captcha.');
}

echo json_encode($res, JSON_UNESCAPED_UNICODE);
