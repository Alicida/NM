<?php
// error_reporting(E_ALL);
// ini_set('display_errors','On');
error_reporting(0);
// header('Content-type: application/json');

try
{
	//Open database connection
	//DB details
	$dbHost = 'PMYSQL117.dns-servicio.com';
	$dbUsername = 'UserNavidad';
	$dbPassword = 'L8ip~u53';
	$dbName = '7063345_NavidadMovistar';

	//Create connection and select DB
	$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
	mysqli_set_charset($db,"utf8");

	//Getting records (listAction)
	if($_GET["action"] == "list")
	{

		//Get records from database
		if(!empty($_POST)){
			$where= '';
			if($_POST["name"] != ''){
				$where .= '&& form_data.name like "%'.$_POST["name"].'%" ';
			}
			if($_POST["folio"] != ''){
				$where .= '&& form_data.folio like "%'.$_POST["folio"].'%" ';
			}
			if($_POST["cadena"] != ''){
				$where .= '&& tiendas.Cadena like "%'.$_POST["cadena"].'%" ';
			}
			if($_POST["nombretienda"] != ''){
				$where .= '&& tiendas.Nombre_tienda like "%'.$_POST["nombretienda"].'%" ';
			}
			if($_POST["idtienda"] != ''){
				$where .= '&& tiendas.ID_Tienda like "%'.$_POST["idtienda"].'%" ';
			}
			if($_POST["aprobado"] != ''){
				$where .= '&& form_data.aprobado ='.$_POST["aprobado"].' ';
			}
			if($_POST["fechaInicio"] != '' && $_POST["fechaFin"] != ''){
				$where .= '&& CONVERT_TZ(form_data.fecha_alta,"+00:00","-07:00") BETWEEN "'.$_POST["fechaInicio"].' 00:00:00" AND "'.$_POST["fechaFin"].' 23:59:59" ';
			}else{
				if($_POST["fechaInicio"] != '' && $_POST["fechaFin"] == ''){
					$where .= '&& CONVERT_TZ(form_data.fecha_alta,"+00:00","-07:00") >= "'.$_POST["fechaInicio"].' 00:00:00" ';
				}else{
					if($_POST["fechaFin"] != ''){
						$where .= '&& CONVERT_TZ(form_data.fecha_alta,"+00:00","-07:00") <= "'.$_POST["fechaFin"].' 23:59:59" ';
					}
				}
			}
			// echo 'variable where: '."SELECT form_data.*, tiendas.*, CONVERT_TZ(form_data.fecha_alta,'+00:00','-07:00') AS FechaFormateada FROM form_data, tiendas WHERE form_data.idTiendaNavidad = tiendas.idTiendasNavidad ".$where." ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"];
			$result = $db->query("SELECT form_data.*, tiendas.*, CONVERT_TZ(form_data.fecha_alta,'+00:00','-07:00') AS FechaFormateada FROM form_data, tiendas WHERE form_data.idTiendaNavidad = tiendas.idTiendasNavidad ".$where." ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"]);
			//Get record count
			$resultCount = $db->query("SELECT COUNT(*) AS RecordCount FROM form_data, tiendas WHERE form_data.idTiendaNavidad = tiendas.idTiendasNavidad ".$where);
			$rowCount = $resultCount->fetch_assoc();
			$recordCount = $rowCount['RecordCount'];

		}else{
			$result = $db->query("SELECT form_data.*, tiendas.*, CONVERT_TZ(form_data.fecha_alta,'+00:00','-07:00') AS FechaFormateada FROM form_data, tiendas WHERE form_data.idTiendaNavidad = tiendas.idTiendasNavidad ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"]);
			//Get record count
			$resultCount = $db->query("SELECT COUNT(*) AS RecordCount FROM form_data;");
			$rowCount = $resultCount->fetch_assoc();
			$recordCount = $rowCount['RecordCount'];
		}

		// $result = $db->query("SELECT * FROM form_data");

		//Add all records to an array
		$rows = array();
		while($row = $result->fetch_assoc())
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult, JSON_UNESCAPED_UNICODE);
	}
	else if($_GET["action"] == "update")
	{
		//Update record in database

		$result = $db->query("UPDATE form_data SET aprobado=".$_POST["aprobado"]." WHERE id = " . $_POST["id"]);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	else if($_GET["action"] == "export-excel")
	{
		require 'PHPExcel.php';
		$fila = 7; //Establecemos en que fila inciara a imprimir los datos

		$gdImage = imagecreatefromjpeg('../images/Movistar-emblema.jpg');//Logotipo

		//Objeto de PHPExcel
		$objPHPExcel  = new PHPExcel();
		ob_start();

		//Propiedades de Documento
		$objPHPExcel->getProperties()->setCreator("Movistar")->setDescription("Reporte de Registros");

		//Establecemos la pestaña activa y nombre a la pestaña
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle("Registros");

		$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setName('Logotipo');
		$objDrawing->setDescription('Logotipo');
		$objDrawing->setImageResource($gdImage);
		$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
		$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
		$objDrawing->setHeight(100);
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		$estiloTituloReporte = array(
	    'font' => array(
		'name'      => 'Arial',
		'bold'      => true,
		'italic'    => false,
		'strike'    => false,
		'size' =>13
	    ),
	    'fill' => array(
		'type'  => PHPExcel_Style_Fill::FILL_SOLID
		),
	    'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_NONE
		)
	    ),
	    'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
	    )
		);

		$estiloTituloColumnas = array(
	    'font' => array(
		'name'  => 'Arial',
		'bold'  => true,
		'size' =>10,
		'color' => array(
		'rgb' => 'FFFFFF'
		)
	    ),
	    'fill' => array(
		'type' => PHPExcel_Style_Fill::FILL_SOLID,
		'color' => array('rgb' => '538DD5')
	    ),
	    'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	    ),
	    'alignment' =>  array(
		'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
	    )
		);

		$estiloInformacion = new PHPExcel_Style();
		$estiloInformacion->applyFromArray( array(
	    'font' => array(
		'name'  => 'Arial',
		'color' => array(
		'rgb' => '000000'
		)
	    ),
	    'fill' => array(
		'type'  => PHPExcel_Style_Fill::FILL_SOLID
		),
	    'borders' => array(
		'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	    ),
		'alignment' =>  array(
		'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
	    )
		));

		$objPHPExcel->getActiveSheet()->getStyle('A1:E4')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A6:V6')->applyFromArray($estiloTituloColumnas);

		// $objPHPExcel->getActiveSheet()->setCellValue('B3', 'REPORTE DE REGISTROS');
		// $objPHPExcel->getActiveSheet()->mergeCells('B3:D3');

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('A6', 'ID');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->setCellValue('B6', 'NOMBRE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('C6', 'TELÉFONO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('D6', 'IMÁGEN');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('E6', 'FECHA FORMATEADA');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('F6', 'FOLIO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('G6', 'APROBADO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('H6', 'CADENA');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('I6', 'ID TIENDA');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('J6', 'ZONA COMERCIAL');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('K6', 'ESTADO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('L6', 'CIUDAD');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('M6', 'NOMBRE TIENDA');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('N6', 'DIRECCION');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('O6', 'COLONIA');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('P6', 'CP');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('Q6', 'TELEFONO TIENDA');
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('R6', 'LIDER');
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('S6', 'CORREO LIDER');
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('T6', 'TELEFONO LIDER');
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('U6', 'JEFE INMEDIATO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('V6', 'TELEFONO JEFE INMEDIATO');

		//Get records from database
		if(!empty($_POST)){
			$where= '';
			if($_POST["name"] != ''){
				$where .= '&& form_data.name like "%'.$_POST["name"].'%" ';
			}
			if($_POST["folio"] != ''){
				$where .= '&& form_data.folio like "%'.$_POST["folio"].'%" ';
			}
			if($_POST["cadena"] != ''){
				$where .= '&& tiendas.Cadena like "%'.$_POST["cadena"].'%" ';
			}
			if($_POST["nombretienda"] != ''){
				$where .= '&& tiendas.Nombre_tienda like "%'.$_POST["nombretienda"].'%" ';
			}
			if($_POST["idtienda"] != ''){
				$where .= '&& tiendas.ID_Tienda like "%'.$_POST["idtienda"].'%" ';
			}
			if($_POST["aprobado"] != ''){
				$where .= '&& form_data.aprobado ='.$_POST["aprobado"].' ';
			}
			if($_POST["fechaInicio"] != '' && $_POST["fechaFin"] != ''){
				$where .= '&& CONVERT_TZ(form_data.fecha_alta,"+00:00","-07:00") BETWEEN "'.$_POST["fechaInicio"].' 00:00:00" AND "'.$_POST["fechaFin"].' 23:59:59" ';
			}else{
				if($_POST["fechaInicio"] != '' && $_POST["fechaFin"] == ''){
					$where .= '&& CONVERT_TZ(form_data.fecha_alta,"+00:00","-07:00") >= "'.$_POST["fechaInicio"].' 00:00:00" ';
				}else{
					if($_POST["fechaFin"] != ''){
						$where .= '&& CONVERT_TZ(form_data.fecha_alta,"+00:00","-07:00") <= "'.$_POST["fechaFin"].' 23:59:59" ';
					}
				}
			}
			// echo 'variable where: '.$where;
			$result = $db->query("SELECT form_data.*, tiendas.*, CONVERT_TZ(form_data.fecha_alta,'+00:00','-07:00') AS FechaFormateada FROM form_data, tiendas WHERE form_data.idTiendaNavidad = tiendas.idTiendasNavidad ".$where);
			//Get record count
			$resultCount = $db->query("SELECT COUNT(*) AS RecordCount FROM form_data, tiendas WHERE form_data.idTiendaNavidad = tiendas.idTiendasNavidad ".$where);
			$rowCount = $resultCount->fetch_assoc();
			$recordCount = $rowCount['RecordCount'];

		}else{
			$result = $db->query("SELECT form_data.*, tiendas.*, CONVERT_TZ(form_data.fecha_alta,'+00:00','-07:00') AS FechaFormateada FROM form_data, tiendas WHERE form_data.idTiendaNavidad = tiendas.idTiendasNavidad");
			//Get record count
			$resultCount = $db->query("SELECT COUNT(*) AS RecordCount FROM form_data;");
			$rowCount = $resultCount->fetch_assoc();
			$recordCount = $rowCount['RecordCount'];
		}

		$rows = array();
		while($row = $result->fetch_assoc())
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $row['id']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $row['name']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $row['tel']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, 'https://navidadmovistar.com.mx/uploads/'.$row['file_name']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $row['FechaFormateada']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $row['folio']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $row['aprobado']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, $row['Cadena']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $row['ID_Tienda']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $row['Zona_Comercial']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $row['Estado']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, $row['Ciudad']);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, $row['Nombre_tienda']);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$fila, $row['Direccion']);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$fila, $row['Colonia']);
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$fila, $row['Cp']);
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$fila, $row['Telefono_tienda']);
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$fila, $row['Lider']);
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$fila, $row['Correo_Lider']);
			$objPHPExcel->getActiveSheet()->setCellValue('T'.$fila, $row['Telefono_lider']);
			$objPHPExcel->getActiveSheet()->setCellValue('U'.$fila, $row['Jefe_inmediato']);
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$fila, $row['Telefono_Jefe_inmediato']);
			// $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, '=C'.$fila.'*D'.$fila);
			$fila++;
		}

		$fila = $fila-1;

		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A7:E".$fila);

		$filaGrafica = $fila+2;

		$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Disposition: attachment;filename="Reportes.xlsx"');
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
		$xlsData = ob_get_contents();
		ob_end_clean();

		$response =  array(
        'op' => 'ok',
        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
    );

		die(json_encode($response));
	}

	//Close database connection
	mysqli_close($db);

}
catch(Exception $ex)
{
    //Return error message
	$jTableResult = array();
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = $ex->getMessage();
	print json_encode($jTableResult);
}

?>
