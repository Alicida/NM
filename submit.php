<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

header('Content-type: application/json');

//Include database configuration file
include('dbConfig.php');

if(!empty($_POST['name']) || !empty($_POST['tel']) || !empty($_FILES['file']['name']) || !empty($_POST['idTienda'])){
    $uploadedFile = '';
    if(!empty($_FILES["file"]["type"])){
        $fileName = time().'_'.$_FILES['file']['name'];
        $valid_extensions = array("jpeg", "jpg", "png");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);
        if((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) && in_array($file_extension, $valid_extensions)){
            $sourcePath = $_FILES['file']['tmp_name'];
            $targetPath = "uploads/".$fileName;
            if(move_uploaded_file($sourcePath,$targetPath)){
                $uploadedFile = $fileName;

                $name = $_POST['name'];
                $tel = $_POST['tel'];
                $idTienda = $_POST['idTienda'];
                $info = getdate();
                $date = $info['mday'];
                $month = $info['mon'];
                $year = $info['year'];
                $hour = $info['hours'];
                $min = $info['minutes'];
                $sec = $info['seconds'];

                $current_date = "$date$month$year$hour$min$sec";
                //include database configuration file
                include_once 'dbConfig.php';

                //insert form data in the database
                $insert = $db->query("INSERT form_data (name,tel,file_name,idTiendaNavidad) VALUES ('".$name."','".$tel."','".$uploadedFile."','".$idTienda."')");

                // echo $insert?'ok':'err';

                if($insert){
                  $lastid = mysqli_insert_id($db);
                  $folio = "F$lastid-$current_date";
                  $update = $db->query("UPDATE form_data SET folio='$folio' WHERE id=$lastid");
                  if($update){
                    $response["success"] = 1;
                    $response["folio"] = $folio;
                    die(json_encode($response));
                  }else{
                    $response["success"] = 0;
                    $response["msg"] = "No pudo actualizar";
                    die(json_encode($response));
                  }
                }else{
                  $response["success"] = 0;
                  $response["msg"] = "No pudo actualizar";
                  die(json_encode($response));
                }
            }
        }else {
          $response["success"] = 0;
          $response["msg"] = "No pudo cargar la imagen";
          die(json_encode($response));
        }
    }else {
      $response["success"] = 0;
      $response["msg"] = "No pudo cargar la imagen";
    }


}else{echo 'no entró';}
