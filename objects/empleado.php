<?php
class Empleado{

    private $conn;
    private $table_name = "empleado";

    public $id_empleado;
    public $nombre;
    public $categoria;
    public $valor_accion;
    public $interes;
	  public $saldo;


    public function __construct($db){
        $this->conn = $db;
    }

function creaEmpleado(){
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                id_empleado=:id_empleado, nombre=:nombre, id_categoria=:id_categoria,
                valor_accion=:valor_accion, id_interes=:interes, saldo=:saldo";
    $stmt = $this->conn->prepare($query);

    $this->id_empleado=htmlspecialchars(strip_tags($this->id_empleado));
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));
    $this->categoria=htmlspecialchars(strip_tags($this->categoria));
    $this->valor_accion=htmlspecialchars(strip_tags($this->valor_accion));
    $this->interes = $this->calculaInteres($this->valor_accion);
    $this->interes=htmlspecialchars(strip_tags($this->interes));
	$this->saldo=htmlspecialchars(strip_tags($this->saldo));

    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->bindParam(":nombre", $this->nombre);
    $stmt->bindParam(":id_categoria", $this->categoria);
    $stmt->bindParam(":valor_accion", $this->valor_accion);
    $stmt->bindParam(":interes", $this->interes);
    $stmt->bindParam(":saldo", $this->saldo);

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

function leeTotalAccion(){
  $query = "SELECT SUM(valor_accion) as accion FROM   " . $this->table_name;
   $stmt = $this->conn->prepare( $query );
   $stmt->execute();
   return $stmt;
}

function leeEmpleadosTarjetas(){
    $query = "select distinct e.id_empleado, e.nombre,
(SELECT case when sum(monto) is null then 0 else sum(case when deuda_ant=0 then monto else 0 end) end  as monto
from ahorro as a left join (select * from ejercicio where activo = 1)  as i on a.id_ejercicio= i.id_ejercicio
where a.id_empleado = e.id_empleado) as ahorro,
(SELECT case when sum(monto) is null then 0 else sum(case when deuda_ant=0 then monto else 0 end) end  as monto
FROM abonos as a left join (select * from ejercicio where activo = 1)  as i on a.id_ejercicio= i.id_ejercicio
where a.id_empleado = e.id_empleado) as abono,
(SELECT sum(monto) + sum(interes) + sum(recibos)
FROM prestamo as p
left join (select * from ejercicio where activo = 1)  as i on p.id_ejercicio= i.id_ejercicio
 where p.id_empleado=e.id_empleado) as prestamo
from   " . $this->table_name . "  as e
order by e.id_empleado ";

   $stmt = $this->conn->prepare( $query );
   $stmt->execute();
   return $stmt;
}

function leeEmpleadosDevoluciones(){
    $query = "select distinct e.id_empleado, e.nombre,e.valor_accion,
(SELECT case when sum(monto) is null then 0 else sum(case when deuda_ant=0 then monto else 0 end) end  as monto
from ahorro as a left join (select * from ejercicio where activo = 1)  as i on a.id_ejercicio= i.id_ejercicio
where a.id_empleado = e.id_empleado) as ahorro,
(SELECT case when sum(monto) is null then 0 else sum(case when deuda_ant=0 then monto else 0 end) end  as monto
FROM abonos as a left join (select * from ejercicio where activo = 1)  as i on a.id_ejercicio= i.id_ejercicio
where a.id_empleado = e.id_empleado) as abono,
(SELECT sum(monto) + sum(interes) + sum(recibos)
FROM prestamo as p
left join (select * from ejercicio where activo = 1)  as i on p.id_ejercicio= i.id_ejercicio
 where p.id_empleado=e.id_empleado) as prestamo
from   " . $this->table_name . "  as e
order by e.id_empleado ";

   $stmt = $this->conn->prepare( $query );
   $stmt->execute();
   return $stmt;
}

function EmpleadoDevolucion(){
    $query = "select distinct e.id_empleado, e.nombre,e.valor_accion,
(SELECT case when sum(monto) is null then 0 else sum(case when deuda_ant=0 then monto else 0 end) end  as monto
from ahorro as a left join (select * from ejercicio where activo = 1)  as i on a.id_ejercicio= i.id_ejercicio
where a.id_empleado = e.id_empleado) as ahorro,
(SELECT case when sum(monto) is null then 0 else sum(case when deuda_ant=0 then monto else 0 end) end  as monto
FROM abonos as a left join (select * from ejercicio where activo = 1)  as i on a.id_ejercicio= i.id_ejercicio
where a.id_empleado = e.id_empleado) as abono,
(SELECT case when sum(monto) is null then 0 else sum(monto) + sum(interes) + sum(recibos) end
FROM prestamo as p
left join (select * from ejercicio where activo = 1)  as i on p.id_ejercicio= i.id_ejercicio
 where p.id_empleado=e.id_empleado) as prestamo,
 (select id_ejercicio from ejercicio where activo = 1) as id_ejercicio
from   " . $this->table_name . "  as e
where id_empleado =:id_empleado
order by e.id_empleado ";

   $stmt = $this->conn->prepare( $query );
   $this->id_empleado=htmlspecialchars(strip_tags($this->id_empleado));
   $stmt->bindParam(":id_empleado", $this->id_empleado);
   $stmt->execute();
   return $stmt;
}

function leeIds(){
    $query = "SELECT id_empleado
            FROM
                " . $this->table_name . " as e
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

function saldoNegativo(){
    $query = "SELECT saldo
            FROM " . $this->table_name . "
           WHERE id_empleado = ? ";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(1, $this->id_empleado);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->saldo = $row['saldo'];
}

function actSaldo(){
    $query = "UPDATE " . $this->table_name . "
    	   SET saldo=:saldo
           WHERE id_empleado =:id_empleado ";
  $stmt = $this->conn->prepare( $query );
	$this->saldo=htmlspecialchars(strip_tags($this->saldo));
	$this->id_empleado=htmlspecialchars(strip_tags($this->id_empleado));
  $stmt->bindParam(":id_empleado", $this->id_empleado);
	$stmt->bindParam(":saldo", $this->saldo);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->saldo = $row['saldo'];
}

function leeEmpleadoXls(){
    $query = "SELECT nombre, tpo_categoria as categoria, valor_accion as accion, valor_interes as interes , saldo
            FROM " . $this->table_name . " as e
            left join  categoria  as c on e.id_categoria =  c.id_categoria
            left join  interes as i on e.id_interes = i.id_interes
           WHERE id_empleado = ? ";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(1, $this->id_empleado);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->nombre = $row['nombre'];
    $this->categoria = $row['categoria'];
    $this->accion = $row['accion'];
    $this->interes = $row['interes'];
	   $this->saldo = $row['saldo'];
}

function actualizaEmpleado(){
    $query = "UPDATE
                " . $this->table_name . "
            SET
                nombre = :nombre,
                id_categoria = :categoria,
                valor_accion = :accion,
                id_interes=:interes,
                saldo=:saldo
            WHERE
                id_empleado = :id_empleado";
    $stmt = $this->conn->prepare($query);
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));
    $this->categoria=htmlspecialchars(strip_tags($this->categoria));
    $this->valor_accion=htmlspecialchars(strip_tags($this->valor_accion));
    $this->interes =$this->calculaInteres($this->valor_accion);
    $this->interes=htmlspecialchars(strip_tags($this->interes));
	$this->saldo=htmlspecialchars(strip_tags($this->saldo));
    $stmt->bindParam(':nombre', $this->nombre);
    $stmt->bindParam(':categoria', $this->categoria);
    $stmt->bindParam(':accion', $this->valor_accion);
    $stmt->bindParam(':id_empleado', $this->id_empleado);
    $stmt->bindParam(':interes', $this->interes);
	$stmt->bindParam(':saldo', $this->saldo);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

function BorraEmpleado(){
    $query = "DELETE FROM " . $this->table_name . " WHERE id_empleado = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id_empleado);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

function calculaInteres($accion){
    if ($accion > 0){
         return 1;
    }else{
        return 2;
    }
}

function cargaSaldo(){
    $query = "UPDATE
                " . $this->table_name . "
            SET
                saldo=:saldo
            WHERE
                id_empleado = :id_empleado";
    $stmt = $this->conn->prepare($query);
	$this->saldo=htmlspecialchars(strip_tags($this->saldo));
    $this->id_empleado=htmlspecialchars(strip_tags($this->id_empleado));
    $stmt->bindParam(':id_empleado', $this->id_empleado);
	$stmt->bindParam(':saldo', $this->saldo);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

}
?>
