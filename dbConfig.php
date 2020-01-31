<?php
//DB details
$dbHost = 'PMYSQL117.dns-servicio.com';
$dbUsername = 'UserNavidad';
$dbPassword = 'L8ip~u53';
$dbName = '7063345_NavidadMovistar';

//Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if($db->connect_error){
    die("Unable to connect database: " . $db->connect_error);
}

$db->set_charset("utf8");
