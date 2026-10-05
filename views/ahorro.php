 <!DOCTYPE html>
<html  ng-controller="ahorrosCtrl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Caja de Ahorro</title>
    <link rel="stylesheet" href="libs/css/materialize.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="libs/css/estilo.css" />
</head>
<body>
 <div class="container">

    <div class="row">
        <div class="col s12">
            <h4>Ahorros</h4>
            <!-- Cuadro de busquedad de Empleado -->
            <div class="row">
              <div class="input-field col s12">
                <i class="material-icons prefix">search</i>
                <input type="text" ng-model="buscar" class="form-control" placeholder="Buscar Empleado..." />
              </div>
            <!-- tabla que muestra los empleados -->
            <div id="datos">
            <table id="datos" class="hoverable bordered highlight">
                <thead>
                        <th class="text-align-center">No de Empleado</th>
                        <th class="width-25-pct text-align-center">Nombre</th>
                        <th class="text-align-center">Total Ahorrado</th>
                    </tr>
                </thead>
                <tbody ng-init="leeAhorros();ValidaLogin();">
                    <tr dir-paginate="d in names | filter:buscar | orderBy:sortKey:reverse | itemsPerPage:pageSize " current-page="currentPage" pagination-id="ahorros" >
                        <td class="text-align-center">{{ d.id_empleado }}</td>
                        <td class="text-align-left">{{ d.nombre }}</td>
                        <td class="text-align-right">$ {{ d.monto | number:2 }}</td>
                        <td>
                            <a ng-click="agregaAhorro(d.id_empleado)" class="waves-effect waves-light btn margin-bottom-1em"><i class="material-icons">attach_money</i></a>
                            <a ng-click="detalleAhorro(d.id_empleado, d.nombre)" class="waves-effect waves-light btn margin-bottom-1em"><i class="material-icons">description</i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="ahorros" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html"></dir-pagination-controls>
            </div>



            <!-- MODAL PARA AGREGAR AHORRO -->
        <div id="modal-add-ahorro" class="modal">
            <div class="modal-content">
                <h4 id="modal-product-title">Agregar Ahorro</h4>
                <div class="row">
                    <div class="input-field col s4">
                        <input ng-model="id_empleado" type="number" class="validate" id="form-id" placeholder="Numero de Empleado..." />
                        <label for="id_empleado">No de Empelado</label>
                    </div>
                    <div class="input-field col s8">
                        <input ng-model="nombre" type="text" class="validate" id="form-nombre" placeholder="Nombre Completo del Empelado..." />
                        <label for="nombre">Nombre</label>
                    </div>
                    <div class="input-field col s4">
                        <input  format="dd/mm/yyyy"  ng-model="fecha" id="fecha" type="date" class="datepicker">
                        <label for="fecha">Fecha</label>
                    </div>
                    <div class="input-field col s8">
                        <input ng-model="monto" type="number" class="validate" id="form-monto" placeholder="Monto a Ahorrar..." />
                        <label for="accion">Monto a Ahorrar</label>
                    </div>
                    <div class="input-field col s12">
                        <a id="btn-guarda-ahorro" class="waves-effect waves-light btn margin-bottom-1em" ng-click="guardaAhorro()"><i class="material-icons left">edit</i>Guardar Cambios</a>
                        <a class="modal-action modal-close waves-effect waves-light btn margin-bottom-1em"><i class="material-icons left">close</i>Cerrar</a>

                    </div>
                </div>
            </div>
        </div>
        <!-- ################################################ -->

        <!-- MODAL PARA IMPORTAR EMPLEADOS -->
        <div id="modal-import-ahorros" class="modal">
            <div class="modal-content">
                <h4 id="modal-product-title">Importar Ahorros</h4>
                <div class="row">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Archivo</span>
                            <input id="archivo" data-upload-Ahorros type="file" ng-model="archivo">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" ng-model="ruta_archivo">
                        </div>
                        </div>
                    <div class="input-field col s12">
                        <a class="modal-action modal-close waves-effect waves-light btn margin-bottom-1em"><i class="material-icons left">close</i>Cerrar</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ################################################ -->
        <!-- MODAL PARA DETALLE DE AHORRO -->
            <div id="modal-detalle-ahorros" class="modal">
                <div class="modal-content">
                    <h4>Detalle de Aportaciones</h4>
                        <p id="modal-text-detalle"></p>
                        <table id="tbl-detalle-ahorro" class="hoverable bordered highlight">
                            <thead>
                            	<tr>
                                    <th class="text-align-center">No de Semana</th>
                                    <th class="text-align-center">Fecha</th>
                                    <th class="text-align-center">Monto Ahorrado</th>
                               </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="d in det_ahorro" >
                                    <td class="text-align-center">{{ d.semana }}</td>
                                    <td class="text-align-center">{{ d.fecha }}</td>
                                    <td class="text-align-right">$ {{ d.monto | number:2 }}</td>
                                    <td>
                                        <a ng-click="borraAhorro(d.id_empleado, d.fecha, d.monto)" class="waves-effect waves-light btn margin-bottom-1em"><i class="material-icons">delete</i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-light btn margin-bottom-1em"><i class="material-icons left">close</i>Cerrar</a>
            </div>
            </div>

        <!-- ################################################ -->

    <!--BOTONES FLOTANTES -->
<div class="fixed-action-btn" style="bottom:45px; right:24px;">
    <a id="add_file" class="waves-effect waves-light btn modal-trigger btn-floating btn-large blue" href="#modal-import-ahorros" ng-click="muestraImportar()"><i class="large material-icons">file_upload</i></a
</div>
<div class="fixed-action-btn" style="bottom:110px; right:24px;">
    <a id="export_xls" class="waves-effect waves-light btn btn-floating btn-large blue" href="xls_ahorro.php" ng-click="" ><i class="large material-icons">file_download</i></a
</div>

        </div>
    </div>

</div>
<script type="text/javascript" src="libs/js/jquery-3.1.1.min.js"></script>
<script src="libs/js/materialize.min.js"></script>
<script>
//INICIALIZA COMPONENTES PARA INTERFAZ
$(document).ready(function(){
    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth()+1;
    var year = date.getFullYear();
    var fecha=year+'-'+month+'-'+day;
    $('.datepicker').pickadate({
        selectYears: 5,
        firstDay: true,
        format: 'yyyy-mm-dd',
        labelMonthNext: 'Mes siguiente',
        labelMonthPrev: 'Mes anterior',
        labelMonthSelect: 'Selecciona un mes',
        labelYearSelect: 'Selecciona un año',
        monthsFull: [ 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
        monthsShort: [ 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic' ],
        weekdaysFull: [ 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado' ],
        weekdaysShort: [ 'Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab' ],
        weekdaysLetter: [ 'D', 'L', 'M', 'X', 'J', 'V', 'S' ],
        today: 'Hoy',
        clear: 'Limpiar',
        close: 'Cerrar',
         container: 'body',
         onSet: function( arg ){
            if ( 'select' in arg ){ //prevent closing on selecting month/year
                this.close();
            }
        }
    });
    $('.modal-trigger').leanModal();
    $('select').material_select();
     $('#fecha').val(fecha);
});
</script>

</body>
</html>
