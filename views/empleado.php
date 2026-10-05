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
 <div class="container" ng-controller="empleadosCtrl">
    <div class="row">
        <div class="col s12">
            <h4>Empleados</h4>
            <!-- Cuadro de busquedad de Empleado -->
            <div class="input-field col s12">
              <i class="material-icons prefix">search</i>
              <input type="text" ng-model="buscar" class="form-control" placeholder="Buscar Empleado..." />
            </div>
            <!-- tabla que muestra los empleados -->
            <div id="datos">
            <table id="TablaDatos" class="hoverable bordered highlight">
                <thead>
                    <th  class="text-align-center"><p>
                        <input class="filled-in" id="selectedAll" type="checkbox" ng-model="selectedAll" ng-click="checkAll()" />
                        <label for="selectedAll"></label>
                        </p></th>
                        <th class="text-align-center">No de Empleado</th>
                        <th class="text-align-center">Nombre</th>
                        <th class="text-align-center">Categoria</th>
                        <th class="text-align-center">Valor de Accion</th>
                        <th class="text-align-center">Intereses</th>
                        <th class="text-align-center">Saldo Inicial</th>
                    </tr>
                </thead>
                <tbody ng-init="leeEmpleados(); ValidaLogin();">
                    <tr dir-paginate="d in names | filter:buscar | orderBy:sortKey:reverse | itemsPerPage:pageSize " current-page="currentPage" pagination-id="empleados" >
                        <td class="text-align-center"><p>
                            <input type="checkbox" class="filled-in" id="{{d.id_empleado}}" ng-model="d.Selected" checkbox-group/>
                            <label for="{{d.id_empleado}}"></label>
                        <p></td>
                        <td class="text-align-center">{{ d.id_empleado }}</td>
                        <td>{{ d.nombre }}</td>
                        <td>{{ d.categoria }}</td>
                        <td class="text-align-center">$ {{ d.accion | number:2 }}</td>
                        <td class="text-align-center">{{ d.interes | number:2 }}%</td>
                         <td class="text-align-center">{{ d.saldo | number:2 }}</td>
                        <td>
                            <a ng-click="leeEmpleado(d.id_empleado)" class="waves-effect waves-light waves-light btn btn-small margin-bottom-1em"><i class=" small material-icons center">edit</i></a>
                            <a ng-click="borraEmpleado(d.id_empleado)" class="waves-effect waves-light btn btn-small margin-bottom-1em"><i class=" small material-icons center">delete</i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <dir-pagination-controls pagination-id="empleados" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html"></dir-pagination-controls>
            </div>


            <!-- MODAL PARA AGREGAR UN EMPLEADO -->
        <div id="modal-product-form" class="modal">
            <div class="modal-content">
                <h4 id="modal-product-title">Agregar Empelado</h4>
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
                        <select id="categoria" ng-model="categoria" class="validate" >
                            <option id="cat" value="" disabled selected>Elige una Opcion</option>
                            <option value="1">Eventual</option>
                            <option value="2">Planta</option>
                        </select>
                        <label for="categoria">Categoria</label>
                    </div>
                    <div class="input-field col s4">
                        <input ng-model="accion" type="number" class="validate" id="form-accion" placeholder="Valor de Accion..." />
                        <label for="accion">Valor de Accion</label>
                    </div>
                     <div class="input-field col s4">
                        <input ng-model="saldo" type="number" class="validate" id="form-saldo" placeholder="Saldo del Empleado..." />
                        <label for="saldo">Saldo</label>
                    </div>
                    <div class="input-field col s12">
                        <a id="btn-create-product" class="waves-effect waves-light btn margin-bottom-1em" ng-click="creaEmpleado()"><i class="material-icons left">add</i>Agregar</a>
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
                <h4 id="modal-product-title">Importar Empelados</h4>
                <div class="row">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Archivo</span>
                            <input id="archivo" data-upload-File type="file" ng-model="archivo">
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
         <!-- MODAL PARA IMPORTAR SALDOS -->
        <div id="modal-import-saldos" class="modal">
            <div class="modal-content">
                <h4 id="modal-product-title">Importar Saldos</h4>
                <div class="row">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Archivo</span>
                            <input id="archivo" data-upload-Saldos type="file" ng-model="archivo">
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
<div class="fixed-action-btn vertical  style="bottom:45px; right:24px;"">
    <a class="btn-floating btn-large red">
      <i class="material-icons">menu</i>
    </a>
    <ul>
    <li>
      	<a id="add_file" class="waves-effect waves-light btn modal-trigger btn-floating blue" href="#modal-import-saldos" ng-click="muestraImportar()"><i class="material-icons">local_atm</i></a>
    </li>
    <li>
    	<a id="export_xls" class="waves-effect waves-light btn btn-floating blue" href="xls_empleado.php" ng-click="" ><i class="large material-icons">file_download</i></a>
    </li>
    <li>
      	<a id="add_file" class="waves-effect waves-light btn modal-trigger btn-floating blue" href="#modal-import-empleados" ng-click="muestraImportar()"><i class="large material-icons">file_upload</i></a>
    </li>
    <li>
      	<a id="delete" class="waves-effect waves-light btn modal-trigger btn-floating  red" href="#" ng-click="borraEmpleados()" ><i class="large material-icons">delete</i></a>
    </li>
    <li>
    	<a id="add" class="waves-effect waves-light btn modal-trigger btn-floating green" href="#modal-product-form" ng-click="muestraFormulario()"><i class="large material-icons">add</i></a>
    </li>



    </ul>
</div>



        </div>
    </div>
</div>
<script type="text/javascript" src="libs/js/jquery-3.1.1.min.js"></script>
<script src="libs/js/materialize.min.js"></script>
<script>
//INICIALIZA COMPONENTES PARA INTERFAZ
$(document).ready(function(){
    $('.modal-trigger').leanModal();
    $('select').material_select();
});
</script>

</body>
</html>
