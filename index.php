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
<body ng-app="ahorroApp">
<!-- BARRA DE MENU PRINCIPAL -->
<div class="navbar-fixed">
    <nav>
    <div class="nav-wrapper blue ">
      <a href="#" class="brand-logo center">Caja de Ahorro</a>
      <ul><li><a href="#" id="button-collapse" data-activates="slide-out"><i class="material-icons">menu</i></a></li></ul>
    </div>
  </nav>
</div>
<!-- MENU LATERAL -->
<ul id="slide-out" class="side-nav"  ng-controller="menu" ng-init="ValidaLogin();">
    <li><div class="userView">
      <img class="responsive-img" src="images/logo.jpg">
    </div></li>
    <li><a ui-sref="empleados" ui-sref-active="active" id="menu_empleados" class="waves-effect" href="#!"><i class="material-icons">perm_identity</i>Empleados</a></li>
    <li><a ui-sref="ahorros" ui-sref-active="active" class="waves-effect" href="#!"><i class="material-icons">attach_money</i>Ahorros</a></li>
    <li><a ui-sref="prestamos"class="waves-effect" href="#!"><i class="material-icons">credit_card</i>Prestamos</a></li>
    <li><a ui-sref="abonos"class="waves-effect" href="#!"><i class="material-icons">monetization_on</i>Abonos</a></li>
    <li><a ui-sref="abonos_efec"class="waves-effect" href="#!"><i class="material-icons">local_atm</i>Abonos en Efectivo</a></li>
    <li><a ui-sref="ent_sal"class="waves-effect" href="#!"><i class="material-icons">compare_arrows</i>Entradas / Salidas</a></li>
    <li><a ui-sref="devoluciones"class="waves-effect" href="#!"><i class="material-icons">backspace</i>Devoluciones</a></li>
    <li><a ui-sref="tarjetas"class="waves-effect" href="#!"><i class="material-icons">description</i>Tarjetas</a></li>
    <li><a ui-sref="reportes"class="waves-effect" href="#!"><i class="material-icons">insert_chart</i>Reportes</a></li>
    <li><div class="divider"></div></li>
    <li><a class="subheader">Administracion</a></li>
    <li><a ui-sref="configuracion" class="waves-effect" href="#!"><i class="material-icons">settings</i>Configuracion de Sistema</a></li>
    <li><a class="waves-effect" ui-sref="usuarios" href="#!"><i class="material-icons">account_box</i>Usuarios</a></li>
    <li><a class="waves-effect" ui-sref="ayuda" href="#!"><i class="material-icons">help</i>Ayuda</a></li>
    <li><a ng-click="logout()" class="waves-effect" href="#!"><i class="material-icons">exit_to_app</i>Salir</a></li>
  </ul>
  <!-- CONTENEDOR PRINCIPAL -->
  <div id="contenedor">
 <section ui-view></section>
  </div>
<script type="text/javascript" src="libs/js/jquery-3.1.1.min.js"></script>
<script src="libs/js/materialize.min.js"></script>
<script type="text/javascript" src="libs/js/funciones.js"></script>
<script type="text/javascript" src="libs/js/angular.min.js"></script>
<script type="text/javascript" src="libs/js/angular-ui-router.js"></script>
<script type="text/javascript" src="libs/js/dirPagination.js"></script>
<script type="text/javascript" src="libs/js/app.js"></script>
<script type="text/javascript" src="libs/js/empleado.js"></script>
<script type="text/javascript" src="libs/js/login.js"></script>
<script type="text/javascript" src="libs/js/ahorros.js"></script>
<script type="text/javascript" src="libs/js/prestamos.js"></script>
<script type="text/javascript" src="libs/js/abonos.js"></script>
<script type="text/javascript" src="libs/js/abonos_efec.js"></script>
<script type="text/javascript" src="libs/js/tarjetas.js"></script>
<script type="text/javascript" src="libs/js/reportes.js"></script>
<script type="text/javascript" src="libs/js/ent_sal.js"></script>
<script type="text/javascript" src="libs/js/configuracion.js"></script>
<script type="text/javascript" src="libs/js/Blob.js"></script>
<script type="text/javascript" src="libs/js/FileSaver.js"></script>
<script type="text/javascript" src="libs/js/angular-storage.js"></script>
<script type="text/javascript" src="libs/js/angular-jwt.js"></script>
<script type="text/javascript" src="libs/js/usuarios.js"></script>
<script type="text/javascript" src="libs/js/devoluciones.js"></script>
</body>
</html>
