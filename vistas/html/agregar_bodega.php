<?php
session_start();
if (!isset($_SESSION['user_login_status']) and $_SESSION['user_login_status'] != 1) {
    header("location: ../../login.php");
    exit;
}
/* Connect To Database*/
require_once "../db.php"; //Contiene las variables de configuracion para conectar a la base de datos
require_once "../php_conexion.php"; //Contiene funcion que conecta a la base de datos
//Inicia Control de Permisos
include "../permisos.php";
$user_id = $_SESSION['id_users'];
//$user_id = $_SESSION['id_users'];
get_cadena($user_id);
$modulo = "Bodegas Empresa";
permisos($modulo, $cadena_permisos);
//Finaliza Control de Permisos
?>

<?php require 'includes/header_start.php';?>

<?php require 'includes/header_end.php';?>

<!-- Begin page -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGulcdBtz_Mydtmu432GtzJz82J_yb-rs&libraries=places"></script>
<div id="wrapper">

	<?php require 'includes/menu.php';?>

	<!-- ============================================================== -->
	<!-- Start right Content here -->
	<!-- ============================================================== -->
	<div class="content-page">
		<!-- Start content -->
		<div class="content">
			<div class="container">
                            <h3 class="portlet-title">
							Agregar	Bodega
							</h3>
<?php if ($permisos_ver == 1) {
    ?><form id="formularioDatos" method="post" action="../ajax/guardar_bodega.php">
				<div class="form-group row">
										<div class="col-md-12">
											<div class="input-group">
                                                                                            
                                                                                            <input id="direccion" name="direccion" class="form-control " type="text" placeholder="Ingresa una dirección">
                                                                                            <input id="nombre" name="nombre" class="form-control " type="text" placeholder="Nombre de la bodega">
                                                                                            <select readonly class='form-control' name='localidad' id='localidad' required>
												<option value="">-- Selecciona --</option>
												<?php

    $query_categoria = mysqli_query($conexion, "select * from localidad order by codigo_parroquia;");
    while ($rw = mysqli_fetch_array($query_categoria)) {
        ?>
													<option value="<?php echo $rw['codigo_parroquia']; ?>"><?php echo $rw['parroquia']; ?></option>
													<?php
}
    ?>
											</select>
                                                                                            <input readonly id="direccion_completa" name="direccion_completa" class="form-control" type="text" placeholder="Ingresa una dirección">
                                                                                           
                                                                                            
                                                                                            
													<?php
                                                                                                        //echo '<h2>'. get_row('edificio', 'nombre', 'id_edificio', $id_edificio).'</h2>';
                                                                                                        ?>
												</div>
                                                                                    <div class="input-group">
                                                                                       <input readonly id="numero_casa" name="numero_casa" class="form-control " type="text" placeholder="Numeracion">
                                                                                             <input readonly id="referencia" name="referencia" class="form-control " type="text" placeholder="Ingrese referencia">
                                                                                            <input readonly id="latitud" name="latitud" class="form-control" type="text" placeholder="Latitud">
                                                                                            <input readonly id="longitud" name="longitud" class="form-control" type="text" placeholder="Longitud">   
                                                                                    </div>
                                                                                    <div class="input-group">
                                                                                       <input readonly id="nombre_contacto" name="nombre_contacto" class="form-control " type="text" placeholder="Ingrese Contacto">
                                                                                             <input readonly id="telefono" name="telefono" class="form-control " type="text" placeholder="Telefono de contacto">
                                                                                               
                                                                                    </div>
											</div>
											
											

										</div>
        <input class="btn btn-primary" type="submit" value="Guardar">
        </form>
                            
  <div id="mapa" style="height: 400px;"></div>
  <div id="infoDireccion"></div>

  <script>
    // Inicializar el mapa
    function initMap() {
      var map = new google.maps.Map(document.getElementById('mapa'), {
        center: {lat: 0, lng: -78},
        zoom: 7
      });

      var geocoder = new google.maps.Geocoder();
      var infowindow = new google.maps.InfoWindow();

      // Autocompletado de direcciones
      var input = document.getElementById('direccion');
      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.bindTo('bounds', map);

      // Crear un marcador inicial
      var marker = new google.maps.Marker({
        position: {lat: 0, lng: -78},
        map: map,
        draggable: true // Hacer el marcador arrastrable
      });

      // Al seleccionar una dirección, centrar el mapa en esa ubicación y colocar el marcador
      autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
          window.alert("No se encontraron detalles de la dirección: '" + place.name + "'");
          return;
        }

        map.setCenter(place.geometry.location);
        map.setZoom(15);

        marker.setPosition(place.geometry.location);

        // Obtener la dirección mediante geocodificación inversa
        geocoder.geocode({'location': place.geometry.location}, function(results, status) {
          if (status === 'OK') {
            if (results[0]) {
              infowindow.setContent('Dirección: ' + results[0].formatted_address);
              infowindow.open(map, marker);
             
            } else {
              window.alert('No se encontraron resultados');
            }
          } else {
            window.alert('Geocoder falló debido a: ' + status);
          }
        });
      });

      // Al mover el marcador, obtener la nueva dirección
      marker.addListener('dragend', function() {
        var latlng = marker.getPosition();

        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
            if (results[0]) {
              infowindow.setContent('Dirección: ' + results[0].formatted_address);
              infowindow.open(map, marker);
               var latitud = results[0].geometry.location.lat();
      var longitud = results[0].geometry.location.lng();
      alert(latlng)
            } else {
              window.alert('No se encontraron resultados');
            }
          } else {
            window.alert('Geocoder falló debido a: ' + status);
          }
        });
      });
    }
  </script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGulcdBtz_Mydtmu432GtzJz82J_yb-rs&libraries=places&callback=initMap"></script>

					<?php
} else {
    ?>
		<section class="content">
			<div class="alert alert-danger" align="center">
				<h3>Acceso denegado! </h3>
				<p>No cuentas con los permisos necesario para acceder a este módulo.</p>
			</div>
		</section>
		<?php
}
?>

				</div>
				<!-- end container -->
			</div>
			<!-- end content -->

			<?php require 'includes/pie.php';?>

		</div>
		<!-- ============================================================== -->
		<!-- End Right content here -->
		<!-- ============================================================== -->


	</div>
	<!-- END wrapper -->

	<?php require 'includes/footer_start.php'
?>
        
	<!-- ============================================================== -->
	<!-- Todo el codigo js aqui-->
	<!-- ============================================================== -->
        <script>
    // Inicializar el mapa
    function initMap() {
      var map = new google.maps.Map(document.getElementById('mapa'), {
        center: {lat: 0, lng: -78},
        zoom: 7
      });

      var geocoder = new google.maps.Geocoder();
      var infowindow = new google.maps.InfoWindow();

      // Autocompletado de direcciones
      var input = document.getElementById('direccion');
      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.bindTo('bounds', map);

      // Crear un marcador inicial
      var marker = new google.maps.Marker({
        position: {lat: 0, lng: -78},
        map: map,
        draggable: true // Hacer el marcador arrastrable
      });

      // Al seleccionar una dirección, centrar el mapa en esa ubicación y colocar el marcador
      autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
          window.alert("No se encontraron detalles de la dirección: '" + place.name + "'");
          return;
        }

        map.setCenter(place.geometry.location);
        map.setZoom(15);

        marker.setPosition(place.geometry.location);

        // Obtener la dirección mediante geocodificación inversa
        geocoder.geocode({'location': place.geometry.location}, function(results, status) {
          if (status === 'OK') {
            if (results[0]) {
              infowindow.setContent('Dirección: ' + results[0].formatted_address);
              infowindow.open(map, marker);
            } else {
              window.alert('No se encontraron resultados');
            }
          } else {
            window.alert('Geocoder falló debido a: ' + status);
          }
        });
      });

      // Al mover el marcador, obtener la nueva dirección
      marker.addListener('dragend', function() {
        var latlng = marker.getPosition();

        geocoder.geocode({'location': latlng}, function(results, status) {
          if (status === 'OK') {
            if (results[0]) {
              infowindow.setContent('Dirección: ' + results[0].formatted_address);
              infowindow.open(map, marker);
              var latitud = results[0].geometry.location.lat();
      var longitud = results[0].geometry.location.lng();
      $("#latitud").val(latitud);
      $("#longitud").val(longitud);
           direccionCompleta=  results[0].formatted_address
           $("#direccion_completa").val(direccionCompleta);
var addressComponents = direccionCompleta.split(',');

// Obtener la penúltima parte (posiblemente el código postal)
var penultimatePart = addressComponents[addressComponents.length - 2].trim();

// Verificar si la penúltima parte es un código postal (puedes ajustar la expresión regular según el formato)
//var codigoPostal = /^\d{6}$/.test(penultimatePart) ? penultimatePart : '';
let ultimosSeisSubstring = penultimatePart.substring(penultimatePart.length - 6);
// Mostrar el código postal
 $("#localidad").val(ultimosSeisSubstring);
$('#localidad, #direccion_completa, #latitud, #longitud, #referencia, #numero_casa, #nombre_contacto, #telefono').prop('readonly', false);
            } else {
              window.alert('No se encontraron resultados');
            }
          } else {
            window.alert('Geocoder falló debido a: ' + status);
          }
        });
      });
    }
  </script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGulcdBtz_Mydtmu432GtzJz82J_yb-rs&libraries=places&callback=initMap"></script>
	<script type="text/javascript" src="../../js/VentanaCentrada.js"></script>
	<script type="text/javascript" src="../../js/bodegas.js"></script>
<script>
       $(document).ready( function () {
        $(".UpperCase").on("keypress", function () {
         $input=$(this);
         setTimeout(function () {
          $input.val($input.val().toUpperCase());
         },50);
        })
       })
       function reporte_excel(){
			var q=$("#q").val();
			window.location.replace("../excel/rep_clientes.php?q="+q);
    //VentanaCentrada('../excel/rep_gastos.php?daterange='+daterange+"&employee_id="+employee_id,'Reporte','','500','25','true');+"&tipo="+tipo
}

      </script>
      <script type="text/javascript">
      	function reporte(){
		var q=$("#q").val();
		VentanaCentrada('../pdf/documentos/rep_clientes.php?q='+q,'Reporte','','800','600','true');
	}
      </script>

	<?php require 'includes/footer_end.php'
?>

