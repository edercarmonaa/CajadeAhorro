<?php
class Prestamo{

    private $conn;
    private $table_name = "prestamo";

    public $id_prestamo;
    public $id_empleado;
    public $monto;
    public $fecha;
    public $plazo;
    public $id_ejercicio;
    public $recibos;


    public function __construct($db){
        $this->conn = $db;
    }

function leePrestamos(){
    $query = "SELECT e.id_empleado, e.nombre ,
    case when sum(p.monto) is null then 0 else sum(p.monto) end as monto,
    case when sum(interes) is null then 0 else sum(interes) end as interes,
    case when sum(recibos) is null then 0 else sum(recibos) end as recibos,
    case when sum((p.monto+interes+recibos)) is null then 0 else sum((p.monto+interes+recibos)) end as total
    FROM
    empleado as e
    left join " . $this->table_name . " as p on e.id_empleado=p.id_empleado
    left join (select * from ejercicio where activo = 1)  as i on p.id_ejercicio= i.id_ejercicio
    group by e.id_empleado
    order by e.id_empleado";
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    return $stmt;
}

function guardaPrestamo(){
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                id_empleado=:id_empleado, monto=:monto, id_ejercicio=:ejercicio, fecha=:fecha, plazo=:plazo, interes=:interes, recibos=:recibos";
    $stmt = $this->conn->prepare($query);

    $this->id_empleado=htmlspecialchars(strip_tags($this->id_empleado));
    $this->monto=htmlspecialchars(strip_tags($this->monto));
    $this->fecha=htmlspecialchars(strip_tags($this->fecha));
    $this->plazo=htmlspecialchars(strip_tags($this->plazo));
    $this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
    $this->interes = $this->calculaInteres($this->id_empleado,$this->monto,$this->plazo);
    $this->recibos = 5;
    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->bindParam(":monto", $this->monto);
    $stmt->bindParam(":fecha", $this->fecha);
    $stmt->bindParam(":plazo", $this->plazo);
    $stmt->bindParam(":ejercicio", $this->id_ejercicio);
    $stmt->bindParam(":interes", $this->interes);
    $stmt->bindParam(":recibos", $this->recibos);
    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
}

function actualizaPrestamo(){
    $query = "UPDATE
                " . $this->table_name . "
            SET
                plazo=:plazo, interes=:interes, fecha=:fecha, monto=:monto WHERE id_prestamo=:id_prestamo ";
    $stmt = $this->conn->prepare($query);

    $this->id_empleado=htmlspecialchars(strip_tags($this->id_empleado));
    $this->id_prestamo=htmlspecialchars(strip_tags($this->id_prestamo));
    $this->plazo=htmlspecialchars(strip_tags($this->plazo));
	  $this->monto=htmlspecialchars(strip_tags($this->monto));
    $this->fecha=htmlspecialchars(strip_tags($this->fecha));
    $this->interes = $this->calculaInteres($this->id_empleado,$this->monto,$this->plazo);
    $stmt->bindParam(":id_prestamo", $this->id_prestamo);
    $stmt->bindParam(":plazo", $this->plazo);
    $stmt->bindParam(":monto", $this->monto);
    $stmt->bindParam(":interes", $this->interes);
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

function calculaInteres($id_empleado, $monto, $plazo){
    $query = "SELECT valor_interes as interes  FROM empleado e
                left join interes i on e.id_interes=i.id_interes
                where id_empleado=:id_empleado";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id_empleado", $id_empleado);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $plazo * ($monto * $row['interes'])/100;
}


function leePrestamosEmpleado(){
    $query = "SELECT id_prestamo, id_empleado, monto, plazo, interes, recibos, fecha, (monto+interes+recibos) as total,
            week(fecha) as semana FROM
                " . $this->table_name . " as p
                left join (select * from ejercicio where activo = 1)  as i on p.id_ejercicio= i.id_ejercicio
            where id_empleado=:id_empleado
            order by semana";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":id_empleado", $this->id_empleado);
    $stmt->execute();
    return $stmt;

    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

function leePrestamo(){
    $query = "SELECT id_prestamo, p.id_empleado, nombre, monto, plazo, fecha
			FROM ".$this->table_name." as p
			left join empleado  as e on p.id_empleado= e.id_empleado
			where id_prestamo=:id_prestamo";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":id_prestamo", $this->id_prestamo);
    $stmt->execute();
    return $stmt;

    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

function leePrestamosSemana($semana){
    $query = "SELECT p.id_empleado, monto,  interes, recibos FROM
                " . $this->table_name . " as p
                left join (select * from ejercicio where activo = 1)  as i on p.id_ejercicio= i.id_ejercicio
where week(fecha)=:semana
group by p.id_empleado
order by p.id_empleado";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":semana", $semana);
    $stmt->execute();
    return $stmt;
}

function resumenPrestamosSemana($semana){
    $query = "select
            case when sum(monto) is null then 0 else sum(monto) end as prestamo,
            case when sum(interes) is null then 0 else sum(interes) end as interes ,
            case when sum(recibos) is null then 0 else sum(recibos) end as recibos,
            (Select case when sum(monto) is null then 0 else sum(case when deuda_ant=0 then monto else 0 end) end  as monto FROM abonos as a
            left join (select * from ejercicio where activo = 1)  as i  on a.id_ejercicio= i.id_ejercicio
            where  WEEK(fecha) =:semana) as abonos,
            (SELECT case when sum(monto) is null then 0 else sum(case when deuda_ant=0 then monto else 0 end) end  as monto
            FROM ahorro as a left join (select * from ejercicio where activo = 1)  as i
            on a.id_ejercicio= i.id_ejercicio where WEEK(fecha)=:semana) as ahorros
            FROM    " . $this->table_name . " as p
               left join (select * from ejercicio where activo = 1)  as i on p.id_ejercicio= i.id_ejercicio
where week(fecha)=:semana  ";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":semana", $semana);
    $stmt->execute();
    return $stmt;
}

function leePrestamosEjercicio(){
    $query = "SELECT e.id_empleado, e.nombre ,
            case when sum(monto) is null then 0 else sum(monto) end as monto
            FROM
			empleado as e
            left join
                " . $this->table_name . " as a
            on e.id_empleado=a.id_empleado
            WHERE id_ejercicio=:id_ejercicio
            group by e.id_empleado
            order by e.id_empleado";
    $this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":id_ejercicio", $this->ejercicio);
    $stmt->execute();
   return $stmt;
}


function borraPrestamo(){
    $query = "DELETE FROM " . $this->table_name . "
    WHERE id_prestamo=:id_prestamo";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id_prestamo", $this->id_prestamo);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}
function borraPrestamoDevolucion(){
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
