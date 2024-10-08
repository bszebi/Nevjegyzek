<?php
require "connect.php";
$sql = "SELECT * 
        FROM nevjegyek";
$result = mysqli_query($dbconn, $sql);
//$data = array();
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
//var_dump($data);
$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
header('Content-Type:application/json');
echo $json;