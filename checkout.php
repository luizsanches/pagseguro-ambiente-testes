<?php
ini_set('display_errors','On');
error_reporting(E_ALL);

include 'PagSeguroServer.php';
$server = new PagSeguroServer();

if (!empty($_GET) || !empty($_POST)) {
	if (empty($_GET))
		$data = $_POST;
	else
		$data = $_GET;

	$server->saveState($data);
}
else {
	die("Nenhum dado recebido.");
}

header("Content-Type:text/xml");
$xml  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
$xml .= "<checkout>\n";
$xml .= "  <code>" . $server->generateRandomString(36) . "</code>\n";
$xml .= "  <date>" . date("Y-m-d") . "T" . date("H:i:sP") . "</date>\n";
$xml .= "</checkout>\n";
echo $xml;
?>
