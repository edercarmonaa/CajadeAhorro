 <!DOCTYPE html>
<html>
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
 <div class="container" ng-controller="ent_salCtrl">
    <div class="row">
        <div class="col s12">
            <h4>Entradas / Salidas </h4>

            <!-- Cuadro de busquedad de Empleado -->
            <div class="input-field col s12">
              <i class="material-icons prefix">search</i>
              <input type="text" ng-model="buscar" class="form-control" placeholder="Buscar Movimiento..." />
            </div>

            <!-- tabla que muestra los empleados -->
            <div id="datos">
            <table id="datos" class="hoverable bordered highlight">
                <thead>
                        <th class="text-align-center">Fecha</th>
                        <th class="text-align-center">Semana</th>
                        <th class="text-align-center">Importe</th>
                        <th class="text-align-center">Concepto</th>
                        <th class="text-align-center">Tipo</th>
                    </tr>
                </thead>
                <tbody ng-init="leeMovimientos();ValidaLogin();">
                    <tr dir-paginate="d in names | filter:buscar | orderBy:sortKey:reverse | itemsPerPage:pageSize " current-page="currentPage" pagination-id="ent_sal" >
                        <td class="text-align-center">{{ d.fecha }}</td>
                        <td>{{ d.semana }}</td>
                        <td>$ {{ d.importe | number:2  }}</td>
                        <td class="text-align-center">{{ d.concepto}}</td>
                        <td class="text-align-center">{{ d.tipo}}</td>
                        <td>
                            <a ng-click="borraMovimiento(d.id_ent_sal)" class="waves-effect waves-light btn margin-bottom-1em"><i class="material-icons center">delete</i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="ent_sal" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html"></dir-pagination-controls>
            </div>

            <!-- MODAL PARA AGREGAR UN Movimiento -->
        <div id="modal-product-form" class="modal">
            <div class="modal-content">
                <h4 id="modal-product-title">Agregar Movimiento</h4>
                <div class="row">
                    <div class="input-field col s4">
                        <input  format="dd/mm/yyyy"  ng-model="fecha" id="fecha" type="date" class="datepicker">
                        <label for="fecha">Fecha</label>
                    </div>
                    <div class="input-field col s4">
                        <input ng-model="monto" type="number" class="validate" id="form-monto" placeholder="Importe..." />
                        <label for="accion">Importe</label>
                    </div>
                    <div class="input-field col s4">
                        <select id="tipo" ng-model="tipo" class="validate" >
                            <option id="cat" value="" disabled selected>Elige una Opcion</option>
                            <option value="1">Otras Entradas</option>
                            <option value="2">Otras Salidas</option>
                            <option value="3">Abonos x Tesoreria</option>
                            <option value="4">Ahorros x Tesoreria</option>
                            <option value="5">Abonos En Efectivo</option>
                            <option value="6">Gastos</option>
                        </select>
                        <label for="categoria">Tipo</label>
                    </div>
                    <div class="input-field col s12">
                        <input ng-model="concepto" type="text" class="validate" id="concepto" placeholder="Concepto..." />
                        <label for="accion">Concepto</label>
                    </div>
                    <div class="input-field col s12">
                        <a id="btn-create-product" class="waves-effect waves-light btn margin-bottom-1em" ng-click="creaMovimiento()"><i class="material-icons left">add</i>Agregar</a>
                        <a id="btn-update-product" class="waves-effect waves-light btn margin-bottom-1em" ng-click="actualizaEmpleado()"><i class="material-icons left">edit</i>Guardar Cambios</a>
                        <a class="modal-action modal-close waves-effect waves-light btn margin-bottom-1em"><i class="material-icons left">close</i>Cerrar</a>

                    </div>
                </div>
            </div>
        </div>
        <!-- ################################################ -->

        <!-- MODAL PARA IMPORTAR EMPLEADOS -->
        <div id="modal-import-empleados" class="modal">
            <div class="modal-content">
                <h4 id="modal-product-title">Importar Movimientos</h4>
                <div class="row">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Archivo</span>
                            <input id="archivo" data-upload-File-Movimiento type="file" ng-model="archivo">
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

    <!-- BOTON FLOTANTE PARA AGREGAR OTRO EMPELADO -->
<div class="fixed-action-btn" style="bottom:45px; right:24px;">
    <a id="add" class="waves-effect waves-light btn modal-trigger btn-floating btn-large green" href="#modal-product-form" ng-click="muestraFormulario()"><i class="large material-icons">add</i></a
</div>
<div class="fixed-action-btn" style="bottom:110px; right:24px;">
    <a id="add_file" class="waves-effect waves-light btn modal-trigger btn-floating btn-large blue" href="#modal-import-empleados" ng-click="muestraImportar()"><i class="large material-icons">file_upload</i></a
</div>
<div class="fixed-action-btn" style="bottom:175px; right:24px;">
    <a id="export_xls" class="waves-effect waves-light btn btn-floating btn-large blue" href="xls_movimiento.php" ng-click="" ><i class="large material-icons">file_download</i></a
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
