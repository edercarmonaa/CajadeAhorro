 <!-- MODAL DE LOGIN  -->
<div id="modal-login" class="modal" ng-controller="loginCtrl" ng-init="muestraformualrio()">
  <div class="modal-content">
      <h4 id="modal-product-title">Inicio de Sesion</h4>
      <div class="row">
          <div class="input-field col s12">
              <input ng-model="nom_usr" type="number" class="validate" id="nom_usr"  />
              <label for="nom_usr">No de Empleado</label>
          </div>
          <div class="input-field col s12">
              <input ng-model="pass_usr" type="password" class="validate" id="pass_usr" />
              <label for="pass_usr">Contraseña</label>
          </div>
          <div class="input-field col s12">
              <a id="btn-create-product" class="waves-effect waves-light btn margin-bottom-1em" ng-click="consultarUsuario()"><i class="material-icons left">check</i>Iniciar Sesion</a>
          </div>
      </div>
  </div>
</div>
<script>
//INICIALIZA COMPONENTES PARA INTERFAZ
$(document).ready(function(){   
    $('#modal-login').openModal({
        dismissible: false
    });
});
</script>

</body>
</html>