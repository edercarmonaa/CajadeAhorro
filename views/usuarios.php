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
 <div class="container" ng-controller="userCtrl">
    <div class="row">
        <div class="col s12">
            <h4>Usuarios</h4>

            <!-- Cuadro de busquedad de Empleado -->
            <div class="input-field col s12">
              <i class="material-icons prefix">buscar</i>
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
                        <th class="text-align-center">Nivel</th>
                    </tr>
                </thead>
                <tbody ng-init="leeUsuarios();ValidaLogin();">
                    <tr dir-paginate="d in names | filter:buscar | orderBy:sortKey:reverse | itemsPerPage:pageSize " current-page="currentPage" pagination-id="usuarios" >
                        <td class="text-align-center"><p>
                            <input type="checkbox" class="filled-in" id="{{d.id_empleado}}" ng-model="d.Selected" checkbox-group/>
                            <label for="{{d.id_empleado}}"></label>
                        <p></td>
                        <td class="text-align-center">{{ d.id_empleado }}</td>
                        <td>{{ d.nom_usr }}</td>
                        <td>{{ d.nivel }}</td>
                        <td>
                            <a ng-click="leeUsuario(d.id_empleado)" class="waves-effect waves-light btn margin-bottom-1em"><i class="material-icons center">edit</i></a>
                            <a ng-click="borraUsuario(d.id_empleado)" class="waves-effect waves-light btn margin-bottom-1em"><i class="material-icons center">delete</i></a>

                        </td>
                    </tr>
                </tbody>
            </table>
              <dir-pagination-controls pagination-id="usuarios" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html"></dir-pagination-controls>
            </div>
            <!-- MODAL PARA AGREGAR UN EMPLEADO -->
        <div id="modal-product-form" class="modal">
            <div class="modal-content">
                <h4 id="modal-product-title">Agregar Usuario</h4>
                <div class="row">
                    <div class="input-field col s4">
                        <input ng-model="id_empleado" type="number" class="validate" id="form-id" placeholder="Numero de Empleado..." />
                        <label for="id_empleado">No de Empelado</label>
                    </div>
                    <div class="input-field col s8">
                        <input ng-model="nombre" type="text" class="validate" id="form-nombre" placeholder="Nombre Completo del Empelado..." />
                        <label for="nombre">Nombre de Usuario</label>
                    </div>
                    <div class="input-field col s4">
                        <input ng-model="password" type="password" class="validate" id="password" placeholder="Contraseña..." />
                        <label for="nombre">Contraseña</label>
                    </div>
                    <div class="input-field col s4">
                        <input ng-model="password2" type="password" class="validate" id="password2" placeholder="Consultar Contraseña..." />
                        <label for="nombre">Confirmar Contraseña</label>
                    </div>
                    <div class="input-field col s4">
                        <select id="nivel" ng-model="nivel" class="validate" >
                            <option id="cat" value="" disabled selected>Nivel</option>
                            <option value="1">Consulta</option>
                            <option value="2">Administrador</option>
                        </select>
                        <label for="categoria">Nivel</label>
                    </div>

                    <div class="input-field col s12">
                        <a id="btn-create-product" class="waves-effect waves-light btn margin-bottom-1em" ng-click="creaUsuario()"><i class="material-icons left">add</i>Agregar</a>
                        <a id="btn-update-product" class="waves-effect waves-light btn margin-bottom-1em" ng-click="actualizaUsuario()"><i class="material-icons left">edit</i>Guardar Cambios</a>
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
    <a id="add_file" class="waves-effect waves-light btn modal-trigger btn-floating btn-large red" href="#" ng-click="borraUsuarios();" ><i class="large material-icons">delete</i></a
</div>
<div class="fixed-action-btn" style="bottom:175px; right:24px;">
    <a id="export_xls" class="waves-effect waves-light btn btn-floating btn-large blue" href="xls_usuario.php" ng-click="" ><i class="large material-icons">file_download</i></a
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
