 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Caja de Ahorro</title>
    <link rel="stylesheet" href="libs/css/materialize.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
   <link rel="stylesheet" href="libs/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="libs/css/estilo.css" />
</head>
<body>
 <div class="container" ng-controller="tarjetasCtrl">
    <div class="row">
        <div class="col s12">
            <h4>Tarjetas</h4>

            <!-- Cuadro de busquedad de Empleado -->
            <div class="input-field col s12">
              <i class="material-icons prefix">search</i>
              <input type="text" ng-model="buscar" class="form-control" placeholder="Buscar Empleado..." />
            </div>

            <!-- tabla que muestra los empleados -->
            <div id="datos">
            <table id="datos" class="hoverable bordered highlight">
                <thead>
                    <th  class="text-align-center"><p>
                        <input class="filled-in" id="selectedAll" type="checkbox" ng-model="selectedAll" ng-click="checkAll()" />
                        <label for="selectedAll"></label>
                        </p></th>
                        <th class="text-align-center">No de Empleado</th>
                        <th class="width-25-pct">Nombre</th>
                        <th class="text-align-center">Prestamos</th>
                        <th class="text-align-center">Abonos</th>
                        <th class="text-align-center">Ahorros</th>
                    </tr>
                </thead>
                <tbody ng-init="leeEmpleadosTarjetas();ValidaLogin();">
                    <tr dir-paginate="d in names | filter:buscar | orderBy:sortKey:reverse | itemsPerPage:pageSize " current-page="currentPage" pagination-id="tarjetas" >
                        <td class="text-align-center"><p>
                            <input type="checkbox" class="filled-in" id="{{d.id_empleado}}" ng-model="d.Selected" checkbox-group/>
                            <label for="{{d.id_empleado}}"></label>
                        <p></td>
                        <td class="text-align-center">{{ d.id_empleado }}</td>
                        <td>{{ d.nombre }}</td>
                        <td>$ {{ d.prestamos | number:2 }}</td>
                        <td class="text-align-center">$ {{ d.abonos | number:2 }}</td>
                        <td class="text-align-center">$ {{ d.ahorros | number:2 }}</td>
                        <td>
                            <a href="xls_tarjeta.php?id_empleado={{d.id_empleado}}" class="waves-effect waves-light btn margin-bottom-1em"><i class="large material-icons">file_download</i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="tarjetas" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html"></dir-pagination-controls>
            </div>



    <!-- BOTON FLOTANTE PARA AGREGAR OTRO EMPELADO -->
        <div class="fixed-action-btn" style="bottom:45px; right:24px;">
            <a id="add" class="waves-effect waves-light btn modal-trigger btn-floating btn-large green" href="#modal-product-form" ng-click="descargaTarjetas()"><i class="large material-icons">file_download</i></a
        </div>
        <div class="fixed-action-btn" style="bottom:110px; right:24px;">
            <a id="add" class="waves-effect waves-light btn modal-trigger btn-floating btn-large blue" href="#modal-product-form" ng-click="imprimeTarjetas()"><i class="large material-icons">print</i></a
        </div>


</div>
</div>
</div>
<div id="preloader" class="preloader-wrapper active">
    <div class="spinner-layer spinner-blue-only">
    <div class="circle-clipper left">
    <div class="circle"></div>
    </div><div class="gap-patch">
    <div class="circle"></div>
    </div><div class="circle-clipper right">
    <div class="circle"></div>
    </div>
    </div>
</div>
<script type="text/javascript" src="libs/js/jquery-3.1.1.min.js"></script>
<script src="libs/js/materialize.min.js"></script>
<script>
//INICIALIZA COMPONENTES PARA INTERFAZ
$(document).ready(function(){
  $( "#preloader" ).toggle();
})
</script>
</body>
</html>
