<?php
class Devolucion{

    private $conn;
    private $table_name = "devoluciones";

    public $id_empleado;
    public $nombre;
    public $id_ejercicio;
    public $prestamos;
    public $ahorros;
    public $abonos;
	  public $saldo;
    public $valor_accion;
    public $fecha;


    public function __construct($db){
        $this->conn = $db;
    }

function creaDevolucion(){
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                id_empleado=:id_empleado, id_ejercicio=:id_ejercicio, prestamos=:prestamos,
                valor_accion=:valor_accion, ahorros=:ahorros, abonos=:abonos, saldo=:saldo, fecha=:fecha, nombre=:nombre";
    $stmt = $this->conn->prepare($query);
    $this->id_empleado=htmlspecialchars(strip_tags($this->id_empleado));
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));
    $this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
    $this->valor_accion=htmlspecialchars(strip_tags($this->valor_accion));
    $this->prestamos =htmlspecialchars(strip_tags($this->prestamos));
    $this->ahorros=htmlspecialchars(strip_tags($this->ahorros));
    $this->abonos=htmlspecialchars(strip_tags($this->abonos));
    $this->fecha=htmlspecialchars(strip_tags($this->fecha));
	  $this->saldo=htmlspecialchars(strip_tags($this->saldo));

    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->bindParam(":nombre", $this->nombre);
    $stmt->bindParam(":id_ejercicio", $this->id_ejercicio);
    $stmt->bindParam(":valor_accion", $this->valor_accion);
    $stmt->bindParam(":prestamos", $this->prestamos);
    $stmt->bindParam(":abonos", $this->abonos);
    $stmt->bindParam(":ahorros", $this->ahorros);
    $stmt->bindParam(":saldo", $this->saldo);
    $stmt->bindParam(":fecha", $this->fecha);

    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
}

function leeEmpleados(){
    $query = "SELECT id_empleado, nombre, tpo_categoria as categoria, valor_accion as accion, valor_interes as interes
            ,saldo FROM
                " . $this->table_name . " as e
           left join categoria  as c on e.id_categoria=c.id_categoria
           left join interes as i on e.id_interes=i.id_interes
order by id_empleado";

   $stmt = $this->conn->prepare( $query );
   $stmt->execute();
   return $stmt;
}

function leeEmpleado(){
    $query = "SELECT nombre, id_categoria as categoria, valor_accion as accion, saldo
            FROM " . $this->table_name . "
           WHERE id_empleado = ? ";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(1, $this->id_empleado);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->nombre = $row['nombre'];
    $this->categoria = $row['categoria'];
    $this->accion = $row['accion'];
    $this->saldo = $row['saldo'];
}

function leeDevoluciones(){
    $query = "SELECT nom_ejercicio, id_empleado, nombre, saldo, valor_accion, ahorros FROM " . $this->table_name . " as d
              left join (select * from ejercicio where activo = 1) as e on d.id_ejercicio = e.id_ejercicio";
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    return $stmt;
}



}
?>
