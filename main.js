$(document).ready(function(e){

  cargaEstado();

  $("#fupForm").on('submit', function(e){
      e.preventDefault();
      $.ajax({
          type: 'POST',
          url: 'submit.php',
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData:false,
          beforeSend: function(){
              $('.submitBtn').attr("disabled","disabled");
              $('#fupForm').css("opacity",".5");
          },
          success: function(result){
              $('.statusMsg').html('');
              if(result.success == 1){
                  $('#fupForm')[0].reset();
                  $('.statusMsg').html('<span style="font-size:18px;color:#34A853">Registo exitoso, su folio es: '+result.folio+'.</span>');
              }else{
                  $('.statusMsg').html('<span style="font-size:18px;color:#EA4335">Ocurrió un error, intenta más tarde.</span>');
              }
              $('#fupForm').css("opacity","");
              $(".submitBtn").removeAttr("disabled");
          }
      });
  });

  //file type validation
  $("#file").change(function() {
      var file = this.files[0];
      var imagefile = file.type;
      var match= ["image/jpeg","image/png","image/jpg"];
      // var maxSize=5120;
      var maxSize=2048;
      if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))){
          alert('Por favor, selecciona un archivo de imagen válido (JPEG/JPG/PNG).');
          $("#file").val('');
          return false;
      }
      if(file.size/1024 > maxSize){
          alert('El peso máximo es de 5 MGs, selecciona otro archivo.');
          $("#file").val('');
          return false;
      }
  });

  $(document).on('change', '#estado', function (e) {
    e.stopImmediatePropagation();
    if(e.target.value !== '' && e.target.value !== null)
      cargaCiudad(e.target.value);
  });
  $(document).on('change', '#ciudad', function (e) {
    e.stopImmediatePropagation();
    if(e.target.value !== '' && e.target.value !== null)
      cargaTienda(e.target.value);
  });

  $(document).on('change', '#nombre', function (e) {
    e.stopImmediatePropagation();
    if(e.target.value !== '' && e.target.value !== null)
      $("#idTienda").val(e.target.value);
  });
  $(document).on('change', '#idTienda', function (e) {
    e.stopImmediatePropagation();
    if(e.target.value !== '' && e.target.value !== null)
      $("#nombre").val(e.target.value);
  });
});

function cargaEstado(){
  $.ajax({
      type: 'POST',
      url: 'combos.php',
      data: {cadena:'Coppel'},
      datatype: 'json',
      success: function(result){
        console.log(result);
          if(result.success == 1){
            var select = document.getElementById("estado");
            console.log('entró if');
            for(var i=0; i<result.estados.length;i++){
              console.log('entró for');
              var option = document.createElement("option");
              option.text = result.estados[i];
              option.value = result.estados[i];
              select.appendChild(option);
            }
          }else{
            console.log('error');
          }
      }
  });
}

function cargaCiudad(_estado){
    $.ajax({
        type: 'POST',
        url: 'combos.php',
        data: {estado: _estado},
        datatype: 'json',
        success: function(result){
          console.log(result);
            if(result.success == 1){
              var selectList = $('#ciudad');
              selectList.find('option').not(':first').remove();
              var select = document.getElementById("ciudad");
              console.log('entró if');
              for(var i=0; i<result.ciudades.length;i++){
                console.log('entró for');
                var option = document.createElement("option");
                option.text = result.ciudades[i];
                option.value = result.ciudades[i];
                select.appendChild(option);
              }
            }else{
              console.log('error');
            }
        }
    });
}

function cargaTienda(_ciudad){
    $.ajax({
        type: 'POST',
        url: 'combos.php',
        data: {ciudad: _ciudad},
        datatype: 'json',
        success: function(result){
          console.log(result);
            if(result.success == 1){
              var selectList = $('#nombre');
              selectList.find('option').not(':first').remove();
              var select = document.getElementById("nombre");
              var selectList2 = $('#idTienda');
              selectList2.find('option').not(':first').remove();
              var select2 = document.getElementById("idTienda");
              console.log('entró if');
              for(var i=0; i<result.tiendas.length;i++){
                console.log('entró for');
                var option = document.createElement("option");
                console.log(result.tiendas[i]['nombre']);
                option.text = result.tiendas[i]['nombre'];
                option.value = result.tiendas[i]['id'];
                select.appendChild(option);
                var option2 = document.createElement("option");
                option2.text = result.tiendas[i]['idTienda'];
                option2.value = result.tiendas[i]['id'];
                select2.appendChild(option2);
              }
            }else{
              console.log('error');
            }
        }
    });
}
