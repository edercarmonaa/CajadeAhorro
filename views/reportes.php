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
<body>
    <div class="container" ng-init="leeEjercicioActivoYear();resumenPrestamosSemana(fecha_con);resumenMovimientosSemana(fecha_rep_sem);leeEjercicioActivo();ValidaLogin();">
        <div class="row" >
        <!-- CONCENTRADO SEMANAL -->
        <div class=" col s6">
           <div class="card col s12  small hoverable">
                <div class="card-image waves-effect waves-block waves-light">
                <img class="activator" src="images/concentrado_semanal.jpg">
                </div>
                <div class="card-content">
                <span class="card-title activator grey-text text-darken-4">Concentrado Semanal<i class="material-icons right">more_vert</i></span>
                <p><a href='xls_concentrado_semanal.php?semana={{fecha_actual | date:"ww"}}'>Descargar Semana no: {{ fecha_actual | date:"ww"}}</a></p>
                </div>
                <div class="card-reveal">
                <span class="card-title grey-text text-darken-4">Semana No: {{ fecha_con | date:"ww"}}<i class="material-icons right">close</i></span>
                <p>Saldo: $ {{resumen_concentrado[0].saldo | number:2}}</p>
                <p>Saldo Favor: $ {{resumen_concentrado[0].saldo_favor | number:2}}</p>
                <p>Saldo Cargo: $ {{resumen_concentrado[0].saldo_cargo | number:2}}</p>
                <div class="input-field col s12">
                   <input id="exampleInput" type="week" name="input" ng-model="fecha_con" placeholder="YYYY-W##" min="{{ejercicios[0].year_ini}}-W50"
                        max="{{ejercicios[0].year_fin}}-W50" required ng-change="resumenPrestamosSemana(fecha_con)" />
                </div>
                <div class="input-field col s6">
                <a href='xls_concentrado_semanal.php?semana={{fecha_con | date:"ww" }}' class="waves-effect waves-light btn"><i class="large material-icons">file_download</i></a>
                </div>
                </div>
            </div>
        </div>
        <!-- REPORTE SEMANAL -->
         <div class=" col s6">
        <div class="card col s12  small hoverable">
                <div class="card-image waves-effect waves-block waves-light">
                <img class="activator" src="images/report_semanal.jpg">
                </div>
                <div class="card-content">
                <span class="card-title activator grey-text text-darken-4">Reporte Semanal<i class="material-icons right">more_vert</i></span>
                <p><a href='xls_reporte_semanal.php?semana={{fecha_con | date:"ww" }}'>Descargar Semana no: {{ fecha_actual | date:"ww"}}</a></p>
                </div>
                <div class="card-reveal">
                <span class="card-title grey-text text-darken-4">Semana No: {{ fecha_rep_sem | date:"ww"}}<i class="material-icons right">close</i></span>
                 <p>Efectivo en Caja: $ {{resumen_rep_sem[0].efectivo_caja | number:2}}</p>
                <div class="input-field col s12">
                   <input id="exampleInput" type="week" name="input" ng-model="fecha_rep_sem" placeholder="YYYY-W##" min="{{ejercicios[0].year_ini}}-W50"
                        max="{{ejercicios[0].year_fin}}-W50" required ng-change="resumenMovimientosSemana(fecha_rep_sem)" />
                </div>
                <div class="input-field col s6">
                <a href='xls_reporte_semanal.php?semana={{fecha_rep_sem | date:"ww" }}' class="waves-effect waves-light btn"><i class="large material-icons">file_download</i></a>
                </div>
                </div>
            </div>
             </div>
         <!--CONCENTRADO ANUAL-->
         <div class=" col s6">
         <div class="card col s12  small hoverable">
                <div class="card-image waves-effect waves-block waves-light">
                <img class="activator" src="images/concentrado_anual.jpg">
                </div>
                <div class="card-content">
                <span class="card-title grey-text text-darken-4">Concentrado Anual</span>
                <p><a href="xls_concentrado_anual.php?ejercicio={{ejercicio_activo[0].nom_ejercicio }}">Descargar Ejercicio: {{ejercicio_activo[0].nom_ejercicio}}</a></p>
                </div>
            </div>
            </div>
         <!--REPORTE  ANUAL-->
         <div class=" col s6">
            <div class="card col s12 small hoverable">
                <div class="card-image waves-effect waves-block waves-light">
                <img class="activator" src="images/reporte_anual.jpg">
                </div>
                <div class="card-content">
                <span class="card-title  grey-text text-darken-4">Reporte Anual</span>
                <p><a href="xls_reporte_anual.php?ejercicio={{ejercicio_activo[0].nom_ejercicio }}">Descargar Ejercicio: {{ejercicio_activo[0].nom_ejercicio}}</a></p>
                </div>
            </div>
          </div>
          <!--REPORTE  ANUAL-->
          <div class=" col s6">
             <div class="card col s12 small hoverable">
                 <div class="card-image waves-effect waves-block waves-light">
                 <img class="activator" src="images/hoja_datos.jpg">
                 </div>
                 <div class="card-content">
                 <span class="card-title  grey-text text-darken-4">Hoja de Datos</span>
                 <p><a href="xls_hoja_datos.php?ejercicio={{ejercicio_activo[0].nom_ejercicio }}">Descargar Ejercicio: {{ejercicio_activo[0].nom_ejercicio}}</a></p>
                 </div>
             </div>
           </div>
          <!--EMPLEADOS VALOR DE ACCION-->
          <div class=" col s6">
             <div class="card col s12 small hoverable">
                 <div class="card-image waves-effect waves-block waves-light">
                 <img class="activator" src="images/valor_accion.jpg">
                 </div>
                 <div class="card-content">
                     <span class="card-title  grey-text text-darken-4">Valor de Accion </span>
                     <p>
                   <div class="input-field col s3">
                   <a href='xls_valor_accion.php' class="waves-effect waves-light btn"><i class="material-icons">file_download</i></a>
                   </div>
                   <div class="input-field col s3">
                   <a href='pdf_valor_accion.php' class="waves-effect waves-light btn"><i class="large material-icons">print</i></a>
                   </div>
                 </p>
                 </div>
             </div>
           </div>
           <!--SALDO A CARGO-->
           <div class=" col s6">
              <div class="card col s12 small hoverable">
                  <div class="card-image waves-effect waves-block waves-light">
                  <img class="activator" src="images/saldo_cargo.png">
                  </div>
                  <div class="card-content">
                      <span class="card-title  grey-text text-darken-4">Saldo a Cargo</span>
                      <p>
                    <div class="input-field col s3">
                    <a href='xls_saldo_cargo.php' class="waves-effect waves-light btn"><i class="material-icons">file_download</i></a>
                    </div>
                    <div class="input-field col s3">
                    <a href='pdf_saldo_cargo.php' class="waves-effect waves-light btn"><i class="large material-icons">print</i></a>
                    </div>
                  </p>
                  </div>
              </div>
            </div>
            <!--SALDO A FAVOR-->
            <div class=" col s6">
               <div class="card col s12 small hoverable">
                   <div class="card-image waves-effect waves-block waves-light">
                   <img class="activator" src="images/saldo_favor.jpg">
                   </div>
                   <div class="card-content">
                       <span class="card-title  grey-text text-darken-4">Saldo a Favor</span>
                       <p>
                     <div class="input-field col s3">
                     <a href='xls_saldo_favor.php' class="waves-effect waves-light btn"><i class="material-icons">file_download</i></a>
                     </div>
                     <div class="input-field col s3">
                     <a href='pdf_saldo_favor.php' class="waves-effect waves-light btn"><i class="large material-icons">print</i></a>
                     </div>
                   </p>
                   </div>
               </div>
             </div>
             <!--DEUDORES-->
             <div class=" col s6">
                <div class="card col s12 small hoverable">
                    <div class="card-image waves-effect waves-block waves-light">
                    <img class="activator" src="images/deudores.jpg">
                    </div>
                    <div class="card-content">
                        <span class="card-title  grey-text text-darken-4">Deudores</span>
                        <p>
                      <div class="input-field col s3">
                      <a href='xls_deudores.php' class="waves-effect waves-light btn"><i class="material-icons">file_download</i></a>
                      </div>
                      <div class="input-field col s3">
                      <a href='pdf_deudores.php' class="waves-effect waves-light btn"><i class="large material-icons">print</i></a>
                      </div>
                    </p>
                    </div>
                </div>
              </div>
              <!--DEUDORES-->
              <div class=" col s6">
                 <div class="card col s12 small hoverable">
                     <div class="card-image waves-effect waves-block waves-light">
                     <img class="activator" src="images/devoluciones.jpg">
                     </div>
                     <div class="card-content">
                         <span class="card-title  grey-text text-darken-4">Devoluciones</span>
                         <p>
                       <div class="input-field col s3">
                       <a href='xls_devoluciones.php' class="waves-effect waves-light btn"><i class="material-icons">file_download</i></a>
                       </div>
                       <div class="input-field col s3">
                       <a href='pdf_devoluciones.php' class="waves-effect waves-light btn"><i class="large material-icons">print</i></a>
                       </div>
                     </p>
                     </div>
                 </div>
               </div>
        </div>
    </div>


        <!-- ################################################ -->
<script type="text/javascript" src="libs/js/jquery-3.1.1.min.js"></script>
<script src="libs/js/materialize.min.js"></script>
</body>
</html>
