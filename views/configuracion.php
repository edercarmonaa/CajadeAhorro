 <!DOCTYPE html>
<html  ng-controller="reportesCtrl" >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Caja de Ahorro</title>
    <link rel="stylesheet" href="libs/css/materialize.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" /> 
    <link rel="stylesheet" href="libs/css/estilo.css" />
</head>
<body >
    <div ng-init="leeSemanas(); leeEjercicios(); ValidaLogin();" class="container">
        <div class="row">
		    <div class="col s12 ">
		      <ul class="tabs">
		        <li class="tab col s4 hoverable"><a class="active blue-text text-darken-2" href="#ejercicio">Ejercicios</a></li>
		        <li class="tab col s4 hoverable"><a class =" blue-text text-darken-2" href="#semana">Semanas</a></li>
		      </ul>
		    </div>
		    <!-- EMPIEZA LA SEGUNDA PESTAÑA RELACIONADA CON LAS SEMANAS -->
		    <div id="semana" class="col s12">
		     <table id="datos" class="hoverable bordered highlight ">
                <thead>
                	<tr>
                    <th class="text-align-center">No Semana</th>
                    <th class="text-align-center">Inicio</th>
                    <th class="text-align-center">Fin</th>
                    <th class="text-align-center">Saldo Inicial</th>
                    <th class="text-align-center">Saldo Final</th>
                    </tr>
                </thead>
                <tbody >
                    <tr ng-repeat="d in filtered = names2 | startFromGrid: currentPage * pageSize | limitTo: pageSize " >
                        <td class="text-align-center">{{ d.no_semana }}</td>
                        <td>{{ d.fecha_inicial }}</td>
                        <td>{{ d.fecha_final }}</td>
                        <td class="text-align-center">$ {{ d.saldo_inicial | number:2 }}</td>
                        <td class="text-align-center">$ {{ d.saldo_final | number:2 }}</td>
                        <td>
                            <a ng-click="muestraFormularioCorteSem(d.no_semana)" class="waves-effect waves-light waves-light btn btn-small margin-bottom-1em"><i class=" small material-icons center">done</i></a>
                        </td>
                    </tr>
                </tbody>
            </table>	
		    </div>
		    <div id="ejercicio" class="col s12">
		    <table id="datos" class="hoverable bordered highlight ">
                <thead>
                	<tr>
                    <th class="text-align-center">Ejercicio</th>
                    <th class="text-align-center">Inicio</th>
                    <th class="text-align-center">Fin</th>
                    <th class="text-align-center">Saldo Inicial</th>
                    <th class="text-align-center">Intereses</th>
                    <th class="text-align-center">Estatus</th>
                    </tr>
                </thead>
                <tbody >
                    <tr ng-repeat="d in filtered = names | startFromGrid: currentPage * pageSize | limitTo: pageSize " >
                        <td class="text-align-center">{{ d.nom_ejercicio }}</td>
                        <td>{{ d.fecha_inicial }}</td>
                        <td>{{ d.fecha_final }}</td>
                        <td class="text-align-center">$ {{ d.saldo_inicial | number:2 }}</td>
                        <td class="text-align-center">{{ d.interes | number:2 }}%</td>
                        <td class="text-align-center">{{ d.estatus }}</td>
                        <td>
                            <a ng-click="MuestraEjercicio(d.id_ejercicio)" class="waves-effect waves-light waves-light btn btn-small margin-bottom-1em"><i class=" small material-icons center">edit</i></a>
                        </td>
                    </tr>
                </tbody>
            </table>	
            <!-- BOTON FLOTANTE PARA AGREGAR OTRO Ejercicio -->
			<div class="fixed-action-btn" style="bottom:45px; right:24px;">
    			<a id="add" class="waves-effect waves-light  btn-floating btn-large green" ng-click="muestraFormulario()"><i class="large material-icons">add</i></a
			</div>
		    </div>
		    
  		</div>   
    </div> 
    <!-- MODAL PARA CREAR EJERCICIO -->
     <div id="modal-ejercicio-form" class="modal">
            <div class="modal-content">
                <h4 id="modal-product-title">Crear Ejercicio</h4>
                <div class="row">
                    <div class="input-field col s4">
                        <input ng-model="nom_ejercicio" type="number" class="validate" id="nom_ejercicio" placeholder="Año del Ejercicio..." />
                        <label for="nom_ejercicio">Año del Ejercicio</label>
                    </div>
                    <div class="input-field col s4">
                        <input  format="dd/mm/yyyy"  ng-model="fecha_ini" id="fecha_ini" type="date" class="datepicker">
                        <label for="fecha_ini">Fecha inicial</label>
                    </div>
                    <div class="input-field col s4">
                        <input  format="dd/mm/yyyy"  ng-model="fecha_fin" id="fecha_fin" type="date" class="datepicker">
                        <label for="fecha_fin">Fecha final</label>
                    </div>
                    <div class="input-field col s4">
                        <select id="estatus" ng-model="estatus" class="validate" >
                            <option id="cat" value="" disabled selected>Seleccione una Opción</option>
                            <option value="1">Activo</option>
                            <option value="0">Deshabilitado</option>
                        </select>
                        <label for="categoria">Estatus</label>
                    </div>
                    <div class="input-field col s4">
                        <input ng-model="saldo_ini" type="number" class="validate" id="saldo_ini" placeholder="Saldo inicial del ejercicio" />
                        <label for="accion">Saldo Inicial</label>
                    </div>
                    <div class="input-field col s4">
                        <span class="card-title grey-text text-darken-4">Semana Inicial</span>
                         <input id="sem_ejer" type="week" name="input" ng-model="sem_ejer" placeholder="YYYY-W##"  required/>
                    </div>
                    <div class="input-field col s12">
                        <a id="btn-create-ejercicio" class="waves-effect waves-light btn margin-bottom-1em" ng-click="creaEjercicio()"><i class="material-icons left">add</i>Agregar</a>
                        <a id="btn-update-ejercicio" class="waves-effect waves-light btn margin-bottom-1em" ng-click="actualizaEjercicio()"><i class="material-icons left">edit</i>Guarda Cambios</a>
                        <a class="modal-action modal-close waves-effect waves-light btn margin-bottom-1em"><i class="material-icons left">close</i>Cerrar</a>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- ################################################ -->
        <!-- MODAL PARA REALIZAR CORTE SEMANAL -->
     <div id="modal-corte-sem-form" class="modal">
            <div class="modal-content">
                <h4 id="modal-product-title">Corte Semanal</h4>
                <div class="row">
                    <div class="input-field col s4">
                        <input  ng-model="fecha_corte_sem" id="fecha_corte_sem" type="number" placeholder="No de Semana" >
                        <label for="fecha_corte_sem">No semana</label>
                    </div>
                    <div class="input-field col s4">
                        <input  format="dd/mm/yyyy"  ng-model="fecha_ini_sem" id="fecha_ini_sem" type="date" class="datepicker">
                        <label for="fecha_ini_sem">Fecha inicial</label>
                    </div>
                    <div class="input-field col s4">
                        <input  format="dd/mm/yyyy"  ng-model="fecha_fin_sem" id="fecha_fin_sem" type="date" class="datepicker">
                        <label for="fecha_fin_sem">Fecha final</label>
                    </div>
                    <div class="input-field col s12">
                        <a id="btn-corte-sem" class="waves-effect waves-light btn margin-bottom-1em" ng-click="corteSemanal()"><i class="material-icons left ">check</i>Realizar Corte</a>
                        <a class="modal-action modal-close waves-effect waves-light btn margin-bottom-1em"><i class="material-icons left">close</i>Cerrar</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ################################################ -->
<script type="text/javascript" src="libs/js/jquery-3.1.1.min.js"></script>
<script src="libs/js/materialize.min.js"></script>
<script>
//INICIALIZA COMPONENTES PARA INTERFAZ
$(document).ready(function(){   
    $('.modal-trigger').leanModal();
    $('select').material_select();
    var date = new Date();
    var day = date.getDate();
    var month = date.getMonth()+1;
    var year = date.getFullYear();
    var fecha=year+'-'+month+'-'+day;
    $('.datepicker').pickadate({
        selectYears: 3,
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
    $('#fecha_ini').val(fecha);
    $('#fecha_fin').val(fecha);
    $('#fecha_ini_sem').val(fecha);
    $('#fecha_fin_sem').val(fecha);
    $('ul.tabs').tabs();
});
</script>
</body>
</html>