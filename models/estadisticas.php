<?php

    class Estadisticas extends Model {
                        
        public static function nuevo() {
            $estadisticas = new self();               
            return $estadisticas;
        }
                
        public function pesoDeTodosLosAlimentosEntre($f1,$f2) {
            
            $sql = "SELECT fecha, SUM(kgs) AS kgs FROM (
                        SELECT turno_entrega.fecha AS fecha, SUM(alimento_pedido.cantidad * detalle_alimento.peso_paquete) AS kgs
                        FROM alimento_pedido
                        INNER JOIN pedido_modelo ON (pedido_modelo.numero = alimento_pedido.pedido_numero)
                        INNER JOIN estado_pedido ON (pedido_modelo.estado_pedido_id = estado_pedido.id)
                        INNER JOIN turno_entrega ON (pedido_modelo.turno_entrega_id = turno_entrega.id)
                        INNER JOIN entidad_receptora ON (entidad_receptora.id = pedido_modelo.entidad_receptora_id) 
                        INNER JOIN detalle_alimento ON (alimento_pedido.detalle_alimento_id = detalle_alimento.id)
                        WHERE (turno_entrega.fecha BETWEEN :f1 AND :f2) AND estado_pedido.entregado = '1'
                    GROUP BY fecha
                    UNION ALL
                     SELECT entrega_directa.fecha AS fecha, SUM(alimento_entrega_directa.cantidad*detalle_alimento.peso_paquete) as kgs
                     FROM alimento_entrega_directa 
                     INNER JOIN entrega_directa ON (alimento_entrega_directa.entrega_directa_id = entrega_directa.id) 
                     INNER JOIN detalle_alimento ON (alimento_entrega_directa.detalle_alimento_id = detalle_alimento.id)
                     WHERE (entrega_directa.fecha BETWEEN :f1 AND :f2)
                     GROUP BY fecha
                   )
                   AS T
                   GROUP BY fecha";
            $query = $this->getDb()->prepare($sql);
            $query->execute(array(":f1" => $f1, ":f2" => $f2)); 
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
    }
        
        public function pesoPorEntidadEntre($f1,$f2) {
            $sql = "SELECT entidad, SUM(kgs) AS kgs FROM (
 
                        SELECT entidad_receptora.razon_social AS entidad, SUM(alimento_pedido.cantidad * detalle_alimento.peso_paquete) AS kgs
                        FROM alimento_pedido
                        INNER JOIN pedido_modelo ON (pedido_modelo.numero = alimento_pedido.pedido_numero)
                        INNER JOIN estado_pedido ON (pedido_modelo.estado_pedido_id = estado_pedido.id)
                        INNER JOIN turno_entrega ON (pedido_modelo.turno_entrega_id = turno_entrega.id)
                        INNER JOIN entidad_receptora ON (entidad_receptora.id = pedido_modelo.entidad_receptora_id) 
                        INNER JOIN detalle_alimento ON (alimento_pedido.detalle_alimento_id = detalle_alimento.id)
                        WHERE (turno_entrega.fecha BETWEEN :f1 AND :f2) AND estado_pedido.entregado = '1'
                        GROUP BY entidad_receptora.razon_social
                        UNION ALL
                         SELECT entidad_receptora.razon_social,   SUM(alimento_entrega_directa.cantidad*detalle_alimento.peso_paquete) as kgs
                         FROM alimento_entrega_directa 
                         INNER JOIN entrega_directa ON (alimento_entrega_directa.entrega_directa_id = entrega_directa.id) 
                         INNER JOIN entidad_receptora ON (entidad_receptora.id = entrega_directa.entidad_receptora_id)  
                         INNER JOIN detalle_alimento ON (alimento_entrega_directa.detalle_alimento_id = detalle_alimento.id)
                         WHERE (entrega_directa.fecha BETWEEN :f1 AND :f2)
                         GROUP BY entidad_receptora.razon_social
                       ) AS T
                       GROUP BY entidad";
            
            $query = $this->getDb()->prepare($sql);
            $query->execute(array(":f1" => $f1, ":f2" => $f2)); 
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            
        }
        
        public function alimentosVencidosSinEntregar() {
            $sql = 'SELECT alimento_codigo, alimento.descripcion, fecha_vencimiento
                    FROM detalle_alimento 
                    INNER JOIN alimento ON (detalle_alimento.alimento_codigo = alimento.codigo)
                    INNER JOIN alimento_pedido ON (detalle_alimento.id = alimento_pedido.detalle_alimento_id)
                    INNER JOIN pedido_modelo ON (alimento_pedido.pedido_numero = pedido_modelo.numero)
                    INNER JOIN estado_pedido ON (pedido_modelo.estado_pedido_id = estado_pedido.id)
                    WHERE (fecha_vencimiento <= CURDATE()) AND (estado_pedido.entregado = 0) AND (detalle_alimento.stock > 0)';
            
            $query = $this->getDb()->prepare($sql);
            $query->execute(); 
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
       
        
    }