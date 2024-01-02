<?php
// Establecer la conexión a la base de datos
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
$sql = "SELECT latitud, longitud FROM bodega where id_empresa=$user_id";
$resultado = $conexion->query($sql);

$bodegas = [];
if ($resultado->num_rows > 0) {
  while ($fila = $resultado->fetch_assoc()) {
    $bodegas[] = $fila;
  }
}

$conexion->close();
?>
<?php require 'includes/header_start.php';?>

<?php require 'includes/header_end.php';?>
<!DOCTYPE html>


  <title>Mapa de Bodegas</title>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGulcdBtz_Mydtmu432GtzJz82J_yb-rs"></script>
  <style>
    #mapa {
      height: 600px;
    }
  </style>

  <div id="wrapper">
      <?php require 'includes/menu.php';?>
      <div class="content-page">
		<!-- Start content -->
		<div class="content">
			<div class="container">
                            <h3 class="portlet-title">
							Bodegas
							</h3>
  <div id="mapa"></div>
  </div>
                    </div>
                </div>
      </div>
  <script>
    function initMap() {
      var map = new google.maps.Map(document.getElementById('mapa'), {
        center: { lat: 0, lng: -78 },
        zoom: 8
      });

      var bodegas = <?php echo json_encode($bodegas); ?>;

      bodegas.forEach(function(bodega) {
        var marker = new google.maps.Marker({
          position: { lat: parseFloat(bodega.latitud), lng: parseFloat(bodega.longitud) },
          map: map
        });
      });
    }
  </script>
  <script>
    google.maps.event.addDomListener(window, 'load', initMap);
  </script>
