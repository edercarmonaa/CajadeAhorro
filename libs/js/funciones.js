$(document).ready(function(){
	//$("#contenedor").load( "views/login.php" );
    
   

    $('#button-collapse').sideNav({
        menuWidth: 300, // Default is 240
        closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
    });
  
    $('.collapsible').collapsible();

/*    $( "#menu_empleados" ).click(function() {
        $.post( "views/empleado.php", function( data ) {
            $( "#contenedor" ).html( data );
        });
    });
    */

});

