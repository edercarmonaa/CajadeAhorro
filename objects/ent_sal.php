<?php 
class Ent_Sal{ 
     
    private $conn; 
    private $table_name = "ent_sal"; 
 
    public $id_ent_sal; 
    public $fecha; 
    public $importe; 
    public $concepto; 
    public $id_tipo;
    public $id_ejercicio;
 
     
    public function __construct($db){ 
        $this->conn = $db;
    }

function leeMovimientos(){
    $query = "SELECT id_ent_sal, fecha, week(fecha) as semana, monto, concepto, t.descripcion as tipo
                FROM " . $this->table_name . " as e 
                left join tipo_operacion as t on t.id_tipo=e.id_tipo
                left join (select * from ejercicio where activo = 1)  as i  
                on e.id_ejercicio= i.id_ejercicio
                order by fecha";
   $stmt = $this->conn->prepare( $query );
   $stmt->execute();
   return $stmt;
}

function creaMovimiento(){
    $query = "INSERT INTO 
                " . $this->table_name . "
            SET 
                fecha=:fecha, monto=:importe, id_tipo=:id_tipo, concepto=:concepto, id_ejercicio=:id_ejercicio";
    $stmt = $this->conn->prepare($query);
    
    $this->fecha=htmlspecialchars(strip_tags($this->fecha));
    $this->importe=htmlspecialchars(strip_tags($this->importe));
    $this->concepto=htmlspecialchars(strip_tags($this->concepto));
    $this->id_ejercicio=htmlspecialchars(strip_tags($this->id_ejercicio));
    $this->id_tipo=htmlspecialchars(strip_tags($this->id_tipo));
    

    $stmt->bindParam(":fecha", $this->fecha);
    $stmt->bindParam(":importe", $this->importe);
    $stmt->bindParam(":concepto", $this->concepto);
    $stmt->bindParam(":id_ejercicio", $this->id_ejercicio);
    $stmt->bindParam(":id_tipo", $this->id_tipo);
    
    if($stmt->execute()){
        return true;
    }else{
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
        return false;
    }
}

function borraMovimiento(){
    $query = "DELETE FROM " . $this->table_name . " WHERE id_ent_sal = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id_ent_sal);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}

function leeMovimientosSemana($semana){
    $query = "select sum(entradas) as entradas , sum(salidas) as salidas, sum(abonos_teso) as abonos_teso, 
            sum(ahorros_teso) as ahorro_teso, sum(abonos_efect) as abonos_efect, sum(gastos) as gastos
            from 
            (SELECT 
            id_tipo,
            case when id_tipo=1 then sum(monto) else 0 end as entradas,
            case when id_tipo=2 then sum(monto) else 0 end as salidas,
            case when id_tipo=3 then sum(monto) else 0 end as abonos_teso,
            case when id_tipo=4 then sum(monto) else 0 end as ahorros_teso,
            case when id_tipo=5 then sum(monto) else 0 end as abonos_efect,
            case when id_tipo=6 then sum(monto) else 0 end as gastos
            FROM ".$this->table_name." as e
            left join (select * from ejercicio where activo = 1)  as i  
            on e.id_ejercicio= i.id_ejercicio
            where week(fecha)=:semana
            group by id_tipo
            order by id_tipo ) as tbl";
    $stmt = $this->conn->prepare( $query );
    $stmt->bindParam(":semana", $semana);
    $stmt->execute();
    return $stmt;
}

function leeMovimientosEjercicio(){
    $query = "select sum(entradas) as entradas , sum(salidas) as salidas, sum(abonos_teso) as abonos_teso, 
            sum(ahorros_teso) as ahorro_teso, sum(abonos_efect) as abonos_efect, sum(gastos) as gastos
            from 
            (SELECT 
            id_tipo,
            case when id_tipo=1 then sum(monto) else 0 end as entradas,
            case when id_tipo=2 then sum(monto) else 0 end as salidas,
            case when id_tipo=3 then sum(monto) else 0 end as abonos_teso,
            case when id_tipo=4 then sum(monto) else 0 end as ahorros_teso,
            case when id_tipo=5 then sum(monto) else 0 end as abonos_efect,
            case when id_tipo=6 then sum(monto) else 0 end as gastos
            FROM ".$this->table_name." as e
            left join (select * from ejercicio where activo = 1)  as i  
            on e.id_ejercicio= i.id_ejercicio
            group by id_tipo
            order by id_tipo ) as tbl";
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    return $stmt;
}


}
?>