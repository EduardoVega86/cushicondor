<?php

/*-------------------------
Autor: Eduardo vega
---------------------------*/
include 'is_logged.php'; //Archivo verifica que el usario que intenta acceder a la URL esta logueado
/* Connect To Database*/
require_once "../db.php";
require_once "../php_conexion.php";
//Inicia Control de Permisos
include "../permisos.php";
$user_id = $_SESSION['id_users'];
get_cadena($user_id);
$modulo = "Categorias";
permisos($modulo, $cadena_permisos);
//Finaliza Control de Permisos
//Archivo de funciones PHP
require_once "../funciones.php";
$id_moneda = get_row('perfil', 'moneda', 'id_perfil', 1);

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != null) ? $_REQUEST['action'] : '';
if ($action == 'ajax') {
    // escaping, additionally removing everything that could be (html/javascript-) code
    $q        = mysqli_real_escape_string($conexion, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
    $aColumns = array('placa'); //Columnas de busqueda
    $sTable   = "camiones";
    $sWhere   = "where user_id=$user_id";
    if ($_GET['q'] != "") {
        $sWhere = "WHERE (";
        for ($i = 0; $i < count($aColumns); $i++) {
            $sWhere .= $aColumns[$i] . " LIKE '%" . $q . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }
    $sWhere .= " order by id";
    include 'pagination.php'; //include pagination file
    //pagination variables
    $page      = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $per_page  = 10; //how much records you want to show
    $adjacents = 4; //gap between pages after number of adjacents
    $offset    = ($page - 1) * $per_page;
    //Count the total number of row in your table*/
    $count_query = mysqli_query($conexion, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
    $row         = mysqli_fetch_array($count_query);
    $numrows     = $row['numrows'];
    $total_pages = ceil($numrows / $per_page);
    $reload      = '../html/lineas.php';
    //main query to fetch the data
    $sql   = "SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
    $query = mysqli_query($conexion, $sql);
    //loop through fetched data
    if ($numrows > 0) {

        ?>
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <tr  class="info">
                  <th>Id</th>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Tipo</th>
                    <th>Tonelaje</th>
                    <th>Año</th>
                    <th class='text-right'>Acciones</th>

                </tr>
                <?php
while ($row = mysqli_fetch_array($query)) {
           $id     = $row['id'];
            $placa      = $row['placa'];
            $brand_id  = $row['brand_id'];
            $vantype_id = $row['vantype_id'];
            $weight_id = $row['weight_id'];
            $anio = $row['anio'];
            $estado_id = $row['estado_id'];

            ?>

     <input type="hidden" value="<?php echo $placa; ?>" id="placa<?php echo  $id; ?>">
    <input type="hidden" value="<?php echo $brand_id; ?>" id="brand_id<?php echo  $id; ?>">
    <input type="hidden" value="<?php echo $vantype_id; ?>" id="vantype_id<?php echo  $id; ?>">
    <input type="hidden" value="<?php echo $weight_id; ?>" id="weight_id<?php echo  $id; ?>">
    <input type="hidden" value="<?php echo $anio; ?>" id="anio<?php echo  $id; ?>">
    <input type="hidden" value="<?php echo $estado_id; ?>" id="estado<?php echo  $id; ?>">

    <tr onclick="documentos(<?php echo $id; ?>)" id="camion<?php echo $id; ?>">
        <td><span class="badge badge-purple"><?php echo $id; ?></span></td>
        <td><?php echo $placa; ?></td>
        <td><?php echo get_row('brand', 'name', 'id', $brand_id); ?></td>
        <td><?php echo get_row('vantype', 'name', 'id', $vantype_id); ?></td>
        <td><?php echo get_row('weight', 'name', 'id', $weight_id); ?></td>
        <td><?php echo $anio; ?></td>
        
        <td >
            <div class="dropdown-menu dropdown-menu-right">
                               <?php if ($permisos_editar == 1) {?>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editarCamion" onclick="obtener_datos_camion('<?php echo $id; ?>');"><i class='fa fa-edit'></i> Editar</a>   
                               <?php }
            if ($permisos_eliminar == 1) {?>
                               <a class="dropdown-item" href="#" data-toggle="modal" data-target="#dataDelete" data-id="<?php echo $id_linea; ?>"><i class='fa fa-trash'></i> Borrar</a>
                               <?php }?>


                           </div>
            <div class="btn-group dropdown">
                <button type="button" class="btn btn-warning btn-sm dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"> <i class='fa fa-cog'></i> <i class="caret"></i> </button>
                
           </div>

       </td>

   </tr>
   <?php
}
        ?>
<tr>
    <td colspan="7">
        <span class="pull-right">
            <?php
echo paginate($reload, $page, $total_pages, $adjacents);
        ?></span>
        </td>
    </tr>
</table>
</div>
<?php
}
//Este else Fue agregado de Prueba de prodria Quitar
    else {
        ?>
    <div class="alert alert-warning alert-dismissible" role="alert" align="center">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>Aviso!</strong> No hay Registro de Linea
  </div>
  <?php
}
// fin else
}
?>