<?php
// error_reporting(E_ALL);
// ini_set('display_errors','On');

header('Content-type: application/json');

//Include database configuration file
include('dbConfig.php');

//Get all country data
if($_POST['estado'] || $_POST['ciudad']){
  if($_POST['estado']){
    comboCiudad($_POST['cadena'],$_POST['estado'],$db);
  }
  if($_POST['ciudad']){
    comboTienda($_POST['cadena'],$_POST['ciudad'],$db);
  }
}else{
  if($_POST['cadena']){
    comboEstado(strtoupper($_POST['cadena']),$db);
  }
}


function comboEstado($cadena,$db){
  $query = $db->query("SELECT DISTINCT Estado FROM tiendas WHERE Cadena = '$cadena' ORDER BY Estado ASC");
  $rowCount = $query->num_rows;
  if ($rowCount > 0) {
    $response["estados"] = array();
    while($row = $query->fetch_assoc()) {
      array_push($response["estados"], $row["Estado"]);
    }
    $response["success"] = 1;
  } else {
    $response["success"] = 0;
    $response["msg"] = "No hay registros que coincidan";
  }
  die(json_encode($response));
}

function comboCiudad($cadena,$estado,$db){

  $query = $db->query("SELECT DISTINCT Ciudad FROM tiendas WHERE Estado = '$estado' AND Cadena = '$cadena' ORDER BY Ciudad ASC");
  $rowCount = $query->num_rows;
  if ($rowCount > 0) {
    $response["ciudades"] = array();
    while($row = $query->fetch_assoc()) {
      array_push($response["ciudades"], $row["Ciudad"]);
    }
    $response["success"] = 1;
  } else {
    $response["success"] = 0;
    $response["msg"] = "No hay registros que coincidan";
  }
  die(json_encode($response));
}

function comboTienda($cadena,$ciudad,$db){

  $query = $db->query("SELECT idTiendasNavidad, Nombre_tienda, ID_Tienda FROM tiendas WHERE Ciudad = '$ciudad' AND Cadena = '$cadena' ORDER BY Nombre_tienda ASC");
  $rowCount = $query->num_rows;
  if ($rowCount > 0) {
    $response["tiendas"] = array();
    while($row = $query->fetch_assoc()) {
      $post = array();
      $post["id"] = $row["idTiendasNavidad"];
      $post["nombre"] = $row["Nombre_tienda"];
      $post["idTienda"] = $row["ID_Tienda"];
      array_push($response["tiendas"], $post);
    }
    $response["success"] = 1;
  } else {
    $response["success"] = 0;
    $response["msg"] = "No hay registros que coincidan";
  }
  die(json_encode($response));
}
