<?php
class AbonoEfectivo{
    private $conn;
    private $table_name = "abonos_efec";

    public $id_abono;
    public $id_empleado;
    public $monto;
    public $fecha;
    public $id_ejercicio;
    public $deuda_ant;


    public function __construct($db){
        $this->conn = $db;
    }
function leeAbonos(){
    $query =   $query = "(SELECT e.id_empleado, e.nombre ,
  		case when sum(monto) is null then 0 else sum(case when deuda_ant=0 then monto else 0 end) end  as monto
  			FROM empleado as e left join  " . $this->table_name . " as a
  			on e.id_empleado=a.id_empleado
  			left join (select * from ejercicio where activo = 1)  as i
  			on a.id_ejercicio= i.id_ejercicio
  			inner join (SELECT p.id_empleado FROM  prestamo as p
  			left join (select * from ejercicio where activo = 1)
  			as i on p.id_ejercicio= i.id_ejercicio) as p on e.id_empleado = p.id_empleado
  			group by e.id_empleado
  			order by e.id_empleado)
  			union
  			(SELECT e.id_empleado, e.nombre ,
  			0 as monto
  			FROM empleado as e left join   " . $this->table_name . " as a on e.id_empleado=a.id_empleado
  			left join (select * from ejercicio where activo = 1)  as i
  			on a.id_ejercicio= i.id_ejercicio
  			where saldo < 0
  			group by e.id_empleado
  			order by e.id_empleado)
  			order by id_empleado";
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    return $stmt;
}

function leeAbonosEmpleado(){
    $query = "SELECT e.id_empleado, e.nombre ,
            case when monto is null then 0 else monto end as monto,
            fecha, WEEK(fecha) as semana
            FROM empleado as e left join
                " . $this->table_name . " as a
           on e.id_empleado=a.id_empleado
            left join (select * from ejercicio where activo = 1)  as i
            on a.id_ejercicio= i.id_ejercicio
            where e.id_empleado=:id_empleado
            and deuda_ant=0
            order by fecha";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->execute();
    return $stmt;
}

function leeAbonosSemana($semana){
    $query = "Select a.id_empleado,
            case when sum(monto) is null then 0 else sum(monto) end as monto
            FROM " . $this->table_name . " as a
           left join (select * from ejercicio where activo = 1)  as i
            on a.id_ejercicio= i.id_ejercicio
            where  WEEK(fecha) =:semana
            and deuda_ant=0
            group by a.id_empleado
            order by a.id_empleado";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":semana", $semana);
    $stmt->execute();
    return $stmt;
}

function leeAbonosEjercicio(){
    $query = "SELECT e.id_empleado, e.nombre ,
            case when sum(monto) is null then 0 else sum(monto) end as monto
            FROM
			empleado as e
            left join
                " . $this->table_name . " as a
            on e.id_empleado=a.id_empleado
            WHERE id_ejercicio=:id_ejercicio
            and deuda_ant=0
            group by e.id_empleado
            order by e.id_empleado";
    $this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":id_ejercicio", $this->ejercicio);
    $stmt->execute();
   return $stmt;
}

function guardaAbono(){
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                id_empleado=:id_empleado, monto=:monto, id_ejercicio=:ejercicio, fecha=:fecha, deuda_ant=:deuda_ant";
    $stmt = $this->conn->prepare($query);
    $this->id_empleado=htmlspecialchars(strip_tags($this->id_empleado));
    $this->monto=htmlspecialchars(strip_tags($this->monto));
    $this->fecha=htmlspecialchars(strip_tags($this->fecha));
    $this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
    $this->deuda_ant=htmlspecialchars(strip_tags($this->deuda_ant));

    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->bindParam(":monto", $this->monto);
    $stmt->bindParam(":fecha", $this->fecha);
    $stmt->bindParam(":ejercicio", $this->id_ejercicio);
    $stmt->bindParam(":deuda_ant", $this->deuda_ant);

    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
}

function calculaEjercicio($year){
    $query = "SELECT id_ejercicio FROM ejercicio where year=:year";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":year", $year);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['id_ejercicio'];

}

function borraAbono(){
    $query = "DELETE FROM " . $this->table_name . "
    WHERE id_empleado=:id_empleado
    and fecha=:fecha
    and monto=:monto";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->bindParam(":monto", $this->monto);
    $stmt->bindParam(":fecha", $this->fecha);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

function borraAbonoDevolucion(){
    $query = "DELETE FROM " . $this->table_name . "
    WHERE id_empleado=:id_empleado
    and id_ejercicio=:id_ejercicio";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->bindParam(":id_ejercicio", $this->id_ejercicio);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

}
?>
