<?php require_once 'templates/header.php';?>
<script type="text/javascript">

	$(document).ready(function () {

		$('#datetimepicker1').datepicker({
			dateFormat: "yy-mm-dd"
		});
		$('#datetimepicker2').datepicker({
			dateFormat: "yy-mm-dd"
		});
			//Prepare jTable
		$('#PeopleTableContainer').jtable({
			title: 'Registros Retail',
			paging: true,
			pageSize: 20,
			sorting: true,
			defaultSorting: 'name ASC',
			actions: {
				listAction: 'ReportActions.php?action=list',
				updateAction: 'ReportActions.php?action=update'
			},
			fields: {
				id: {
					key: true,
					create: false,
					edit: false,
					list: false
				},
				name: {
					title: 'Nombre',
					width: '40%',
					create: false,
					edit: false
				},
				tel: {
					title: 'Teléfono',
					width: '20%',
					create: false,
					edit: false
				},
				FechaFormateada: {
					title: 'Fecha de Alta',
					width: '20%',
					create: false,
					edit: false
				},
				folio: {
					title: 'Folio',
					width: '20%',
					create: false,
					edit: false
				},
				Cadena: {
					title: 'Cadena',
					width: '20%',
					create: false,
					edit: false
				},
				Nombre_tienda: {
					title: 'Nombre Tienda',
					width: '20%',
					create: false,
					edit: false
				},
				ID_Tienda: {
					title: 'ID Tienda',
					width: '20%',
					create: false,
					edit: false
				},
				file_name: {
						title: 'Imagen',
						width: '20%',
						create: false,
						edit: false,
						display: function (data) {
								return '<a href="javascript:void(0)" class="modalImagen" data-toggle="modal" data-target="#modal1"><img style="width: 30px;" src="https://navidadmovistar.com.mx/uploads/'+data.record.file_name+'" alt="Ticket"></a>';
						}
				},
				detalle: {
						title: 'Detalle Tienda',
						width: '20%',
						create: false,
						edit: false,
						display: function (data) {
								return '<a href="javascript:void(0)" data-Cadena="'+data.record.Cadena+'" data-ID_Tienda="'+data.record.ID_Tienda+'" data-Zona_Comercial="'+data.record.Zona_Comercial+'" data-Estado="'+data.record.Estado+'" data-Ciudad="'+data.record.Ciudad+'" data-Nombre_tienda="'+data.record.Nombre_tienda+'" data-Direccion="'+data.record.Direccion+'" data-Colonia="'+data.record.Colonia+'" data-CP="'+data.record.CP+'" data-Telefono_tienda="'+data.record.Telefono_tienda+'" data-ID_Lider="'+data.record.ID_Lider+'" data-Lider="'+data.record.Lider+'" data-Correo_Lider="'+data.record.Correo_Lider+'" data-Telefono_Lider="'+data.record.Telefono_lider+'" data-Jefe_inmediato="'+data.record.Jefe_inmediato+'" data-Telefono_Jefe_inmediato="'+data.record.Telefono_Jefe_inmediato+'" data-toggle="modal" data-target="#modal2" class="modalTienda">Ver más</a>';
						}
				},
				aprobado: {
						title: 'Aprobado',
						width: '20%',
						display: function (data) {
							if(data.record.aprobado == 1){
								return 'Sí';
							}else{
								return 'No';
							}

						},
						options: { '0': 'No Aprobado', '1': 'Aprobado'}
				}
			},
			recordsLoaded: function(event, data) {
				$(".modalImagen").bind('click', function () {
						$('#ticketDetalle').attr("src",this.firstElementChild.src);
						// $('#modal1').modal('show');
				});
				$(".modalTienda").bind('click', function () {

						$('#Cadena').html(this.dataset.cadena);
						$('#ID_Tienda').html(this.dataset.id_tienda);
						$('#Zona_Comercial').html(this.dataset.zona_comercial);
						$('#Estado').html(this.dataset.estado);
						$('#Ciudad').html(this.dataset.ciudad);
						$('#Nombre_tienda').html(this.dataset.nombre_tienda);
						$('#Direccion').html(this.dataset.direccion);
						$('#Colonia').html(this.dataset.colonia);
						$('#CP').html(this.dataset.cp);
						$('#Telefono_tienda').html(this.dataset.telefono_tienda);
						$('#ID_Lider').html(this.dataset.id_lider);
						$('#Lider').html(this.dataset.lider);
						$('#Correo_Lider').html(this.dataset.correo_lider);
						$('#Telefono_Lider').html(this.dataset.telefono_lider);
						$('#Jefe_inmediato').html(this.dataset.jefe_inmediato);
						$('#Telefono_Jefe_inmediato').html(this.dataset.telefono_jefe_inmediato);

						// $('#modal2').modal('show');
				});
			}
		});

		$('#LoadRecordsButton').click(function (e) {
			e.preventDefault();
			$('#PeopleTableContainer').jtable('load', {
				name: $('#input-name').val(),
				folio: $('#input-folio').val(),
				cadena: $('#input-cadena').val(),
				nombretienda: $('#input-nombretienda').val(),
				idtienda: $('#input-idtienda').val(),
				aprobado: $('#input-aprobado').val(),
				fechaInicio: $('#datetimepicker1').val(),
				fechaFin: $('#datetimepicker2').val()
			});
		});

		//Load person list from server
		$('#PeopleTableContainer').jtable('load');

		$('#ReportExcel').click(function (e) {
			e.preventDefault();

			var dataForm=[];

			dataForm['name']= $('#input-name').val();
			dataForm['folio']= $('#input-folio').val();
			dataForm['cadena']= $('#input-cadena').val();
			dataForm['nombretienda']= $('#input-nombretienda').val();
			dataForm['idtienda']= $('#input-idtienda').val();
			dataForm['aprobado']= $('#input-aprobado').val();
			dataForm['fechaInicio']= $('#datetimepicker1').val();
			dataForm['fechaFin']= $('#datetimepicker2').val();
			console.log(dataForm);
			$.ajax({
			    cache: false,
			    url: 'ReportActions.php?action=export-excel',
					type: 'POST',
			    data: {
							name: $('#input-name').val(),
							folio: $('#input-folio').val(),
							cadena: $('#input-cadena').val(),
							nombretienda: $('#input-nombretienda').val(),
							idtienda: $('#input-idtienda').val(),
							aprobado: $('#input-aprobado').val(),
							fechaInicio: $('#datetimepicker1').val(),
							fechaFin: $('#datetimepicker2').val()
					},
					dataType:'json'
			}).done(function(data){
			    var $a = $("<a>");
			    $a.attr("href",data.file);
			    $("body").append($a);
			    $a.attr("download","file.xlsx");
			    $a[0].click();
			    $a.remove();
			});
		});


	});

</script>
	<div class="content">
     	<div class="container">
     		<div class="col-md-12 col-sm-12 col-xs-12">


				  <div class="filtering">
				      <form>
								<div class="form-group col-md-6">
							    <label for="input-name">Nombre</label>
							    <input type="text" class="form-control" name="input-name" id="input-name" />
							  </div>
								<div class="form-group col-md-6">
							    <label for="input-name">Folio</label>
							    <input type="text" class="form-control" name="input-folio" id="input-folio" />
							  </div>
								<div class="form-group col-md-6">
							    <label for="input-name">Cadena</label>
							    <input type="text" class="form-control" name="input-cadena" id="input-cadena" />
							  </div>
								<div class="form-group col-md-6">
							    <label for="input-name">Nombre Tienda</label>
							    <input type="text" class="form-control" name="input-nombretienda" id="input-nombretienda" />
							  </div>
								<div class="form-group col-md-6">
							    <label for="input-name">Id Tienda</label>
							    <input type="number" class="form-control" name="input-idtienda" id="input-idtienda" />
							  </div>
								<div class="form-group col-md-6">
							    <label for="input-name">Aprobado</label>
									<select id="input-aprobado" class="form-control" name="input-aprobado">
				              <option selected="selected" value="">Todos los status</option>
				              <option value="0">Sí</option>
				              <option value="1">No</option>
				          </select>
							  </div>
								<div class="form-group col-md-6">
									<div class="form-group">
										<label for="datetimepicker1">Desde</label>
                    <input type='text' id='datetimepicker1' class="form-control" />
			            </div>
							  </div>
								<div class="form-group col-md-6">
									<label for="datetimepicker2">Hasta</label>
                  <input type='text' id='datetimepicker2' class="form-control" />
							  </div>
				         <button type="submit" class="btn btn-primary" id="LoadRecordsButton">Buscar</button>
				      </form>
				  </div>
					<br><br><br>
				  <div id="PeopleTableContainer" style="width: 100%;"></div>
					<br><br><br>
					<div class="report">
						<button type="submit" class="btn btn-primary" id="ReportExcel">Generar reporte en Excel</button>
						<!-- <a href="ReportActions.php?action=export-excel"> Generar reporte en Excel</a> -->
					</div>
				  <!-- Modal Structure -->
				  <div id="modal1" class="modal fade">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h4 id='statusMsg' class="modal-title">Ticket</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
								</div>
								<div class="modal-body">
									<img id="ticketDetalle" src="" alt="ticket" style="width: 100%;">
								</div>
						    <div class="modal-footer">
						      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						    </div>
					    </div>
						</div>
				  </div>

				  <div id="modal2" class="modal fade">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 id='Nombre_tienda' class="modal-title">Modal Header</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
								</div>
								<div class="modal-body">
									<p>Cadena: <span id="Cadena"></span></p>
									<p>ID_Tienda: <span id="ID_Tienda"></span></p>
									<p>Zona_Comercial: <span id="Zona_Comercial"></span></p>
									<p>Estado: <span id="Estado"></span></p>
									<p>Ciudad: <span id="Ciudad"></span></p>
									<p>Direccion: <span id="Direccion"></span></p>
									<p>Colonia: <span id="Colonia"></span></p>
									<p>CP: <span id="CP"></span></p>
									<p>Telefono_tienda: <span id="Telefono_tienda"></span></p>
									<p>ID_Lider: <span id="ID_Lider"></span></p>
									<p>Lider: <span id="Lider"></span></p>
									<p>Correo_Lider: <span id="Correo_Lider"></span></p>
									<p>Telefono_Lider: <span id="Telefono_Lider"></span></p>
									<p>Jefe_inmediato: <span id="Jefe_inmediato"></span></p>
									<p>Telefono_Jefe_inmediato: <span id="Telefono_Jefe_inmediato"></span></p>
								</div>
						    <div class="modal-footer">
						      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						    </div>
					    </div>
						</div>
				  </div>

     		</div>
     		<?php require_once 'templates/sidebar.php';?>

     	</div>
    </div> <!-- /container -->
<?php require_once 'templates/footer.php';?>
