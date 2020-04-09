<?php

//namespace FacturaScripts\model;

//require_once __DIR__.'/../../extras/documento_venta.php';

/**
 * Albarán de cliente o albarán de venta. Representa la entrega a un cliente
 * de un material que se le ha vendido. Implica la salida de ese material
 * del almacén de la empresa.
 * 
 * @author Carlos García Gómez <neorazorx@gmail.com>
 */
class operaciones_m extends fs_model
{
   // use \documento_venta;

    public $idoperaciones;    
    public $fec_operacion;
    public $idestado;       
    public $idcliente;
    public $observacion;
    
    public $ptefactura;
    public $nombrecliente;
    public $ruccliente;
    public $nombreestado;

    public function __construct($data = FALSE)
    {
        parent::__construct('operaciones');
        if ($data) {
            $this->idoperaciones = $data['idoperaciones'];            
            $this->fec_operacion = NULL;
            if ($data['fec_operacion'] != '') {                
                $this->fec_operacion = Date('d-m-Y', strtotime($data['fec_operacion']));
            }            
            $this->idestado = $data['idestado'];
            $this->idcliente = $data['idcliente'];
            $this->observacion = $data['observacion'];
           
            $this->ptefactura = $this->str2bool($data['ptefactura']);
            $this->nombrecliente = $data['nombrecliente'];
            $this->ruccliente = $data['ruccliente'];
            $this->nombreestado = $data['nombreestado'];
            
        } else {
            $this->idoperaciones = 0;            
            $this->fec_operacion = Date('d-m-Y');  //$this->fec_emision = date('d-m-Y H:i:s');             
            $this->observacion = '';
            $this->idcliente = 0;
            $this->idestado = 0;     
            
            $this->ptefactura = TRUE;
            
            $this->nombrecliente = '';
            $this->ruccliente = '';
            $this->nombreestado = '';
            
        }
    }

    protected function install()
    {
        /// nos aseguramos de que se comprueba la tabla de facturas antes
        new \operaciones_m();

        return '';
    }

    public function url()
    {
        if (is_null($this->idoperaciones)) {
            return 'index.php?page=operaciones';
        }

        return 'index.php?page=operaciones_edit&id=' . $this->idoperaciones;
    }

    public function factura_url()
    {
        if (is_null($this->idfactura)) {
            return '#';
        }

        return 'index.php?page=ventas_factura&id=' . $this->idfactura;
    }

    /**
     * Devuelve las líneas del albarán.
     * @return \linea_operaciones_m
     */
    public function get_lineas()
    {
        $linea = new \operacion_linea_m();
        return $linea->all_from_albaran($this->idoperaciones);
    }

    /**
     * Devuelve el albarán solicitado o false si no se encuentra.
     * @param string $id
     * @return \operaciones_m|boolean
     */
    public function get($id)
    {
        
       /*  $sql = "SELECT *,c.nombre nombrecliente,c.ruc ruccliente,e.nombre nombreestado "
                . "FROM " . $this->table_name . " o "
                . "join clientes c on o.idcliente=c.idcliente "
                . "join estado e on o.idestado=e.idestado "
                . "ORDER BY " . $order;*/
        $data = $this->db->select("SELECT *,c.nombre nombrecliente,c.ruc ruccliente,e.nombre nombreestado "
                . "FROM " . $this->table_name . " o  "
                . "join clientes c on o.idcliente=c.idcliente "
                . "join estado e on o.idestado=e.idestado "
                . "WHERE idoperaciones = " . $this->var2str($id) . ";");
        if ($data) {
            return new \operaciones_m($data[0]);
        }

        return FALSE;
    }

    public function get_by_codigo($dataod)
    {
        $data = $this->db->select("SELECT * FROM " . $this->table_name . " "
                . "WHERE idoperaciones = " . strtoupper($this->var2str($dataod)) . ";");
        if ($data) {
            return new \operaciones_m($data[0]);
        }

        return FALSE;
    }

    public function exists()
    {
        if (is_null($this->idoperaciones)) {
            return FALSE;
        }

        return $this->db->select("SELECT * FROM " . $this->table_name . " "
                . "WHERE idoperaciones = " . $this->var2str($this->idoperaciones) . ";");
    }

    /**
     * Genera un nuevo código y número para este albarán
     */
    public function new_codigo()
    {
        //$this->numero = fs_documento_new_numero($this->db, $this->table_name, $this->codejercicio, $this->codserie, 'nalbarancli');
        //$this->codigo = fs_documento_new_codigo(FS_ALBARAN, $this->codejercicio, $this->codserie, $this->numero);
    }

    /**
     * Comprueba los datos del albarán, devuelve TRUE si son correctos
     * @return boolean
     */
    public function test()
    {
        /*if ($this->idfactura) {
            $this->ptefactura = FALSE;
        }

        return $this->test_trait();*/
    }

    /**
     * Comprobaciones extra del albarán, devuelve TRUE si está todo correcto
     * @param boolean $duplicados
     * @return boolean
     */
   /* public function full_test($duplicados = TRUE)
    {
        $status = $this->full_test_trait(FS_ALBARAN);
        
        if ($this->total != 0) {
            /// comprobamos las facturas asociadas
            $linea_factura = new \linea_factura_cliente();
            $facturas = $linea_factura->facturas_from_albaran($this->idoperaciones);
            if (!empty($facturas)) {
                if (count($facturas) > 1) {
                    $msg = "Este " . FS_ALBARAN . " esta asociado a las siguientes facturas (y no debería):";
                    foreach ($facturas as $f) {
                        $msg .= " <a href='" . $f->url() . "'>" . $f->codigo . "</a>";
                    }
                    $this->new_error_msg($msg);
                    $status = FALSE;
                } else if ($facturas[0]->idfactura != $this->idfactura) {
                    $this->new_error_msg("Este " . FS_ALBARAN . " esta asociado a una <a href='" . $this->factura_url() .
                        "'>factura</a> incorrecta. La correcta es <a href='" . $facturas[0]->url() . "'>esta</a>.");
                    $status = FALSE;
                }
            } else if (isset($this->idfactura)) {
                $this->new_error_msg("Este " . FS_ALBARAN . " esta asociado a una <a href='" . $this->factura_url()
                    . "'>factura</a> que ya no existe.");
                $this->idfactura = NULL;
                $this->save();

                $status = FALSE;
            }
        }

        if ($status && $duplicados) {
            /// comprobamos si es un duplicado
            $data = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE fecha = " . $this->var2str($this->fecha)
                . " AND codcliente = " . $this->var2str($this->codcliente)
                . " AND total = " . $this->var2str($this->total)
                . " AND codagente = " . $this->var2str($this->codagente)
                . " AND numero2 = " . $this->var2str($this->numero2)
                . " AND observaciones = " . $this->var2str($this->observaciones)
                . " AND idoperaciones != " . $this->var2str($this->idoperaciones) . ";");
            if ($data) {
                foreach ($data as $alb) {
                    /// comprobamos las líneas
                    $aux = $this->db->select("SELECT referencia FROM lineasalbaranescli WHERE
                  idoperaciones = " . $this->var2str($this->idoperaciones) . "
                  AND referencia NOT IN (SELECT referencia FROM lineasalbaranescli
                  WHERE idoperaciones = " . $this->var2str($alb['idoperaciones']) . ");");
                    if (!$aux) {
                        $this->new_error_msg("Este " . FS_ALBARAN . " es un posible duplicado de
                     <a href='index.php?page=ventas_albaran&id=" . $alb['idoperaciones'] . "'>este otro</a>.
                     Si no lo es, para evitar este mensaje, simplemente modifica las observaciones.");
                        $status = FALSE;
                    }
                }
            }
        }

        return $status;
    }*/

    /**
     * Guarda los datos en la base de datos
     * @return boolean
     */
    public function save()
    {
        //if ($this->test()) {  x esto no entrabaaaaaaaaa
            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " "
                        . "SET fec_operacion = " . $this->var2str($this->fec_operacion)
                    . ", idestado = " . $this->var2str($this->idestado)
                    . ", idcliente = " . $this->var2str($this->idcliente)
                    . ", observacion = " . $this->var2str($this->observacion)                    
                    . ", ptefactura = " . $this->var2str($this->ptefactura)               
                    . "  WHERE idoperaciones = " . $this->var2str($this->idoperaciones) . ";";

                return $this->db->exec($sql);
            }
             //$this->new_advice("OK");
            //$this->new_codigo();
            $sql = "INSERT INTO " . $this->table_name . " 
                (fec_operacion,idestado,idcliente,observacion) VALUES "
                . "(" . $this->var2str($this->fec_operacion)
                . "," . $this->var2str($this->idestado)
                . "," . $this->var2str($this->idcliente)
                . "," . $this->var2str($this->observacion) . ");";

            if ($this->db->exec($sql)) {
                $this->idoperaciones = $this->db->lastval();
                return TRUE;
            }
       // }

        return FALSE;
    }

    /**
     * Elimina el albarán de la base de datos.
     * Devuelve FALSE en caso de fallo.
     * @return boolean
     */
    public function delete()
    {
        if ($this->db->exec("DELETE FROM " . $this->table_name . " "
                . "WHERE idoperaciones = " . $this->var2str($this->idoperaciones) . ";")) {
//            if ($this->idfactura) {
//                /**
//                 * Delegamos la eliminación de la factura en la clase correspondiente,
//                 * que tendrá que hacer más cosas.
//                 */
//                $factura = new \factura_cliente();
//                $factura0 = $factura->get($this->idfactura);
//                if ($factura0) {
//                    $factura0->delete();
//                }
//            }

            $this->new_message("Operación " . $this->idoperaciones . " eliminado correctamente.");
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Devuelve un array con los últimos albaranes de venta
     * @param integer $offset
     * @param string $order
     * @return \operaciones_m
     */
    public function all($offset = 0, $order = 'fec_operacion DESC', $limit = FS_ITEM_LIMIT)
    {
        $sql = "SELECT *,c.nombre nombrecliente,c.ruc ruccliente,e.nombre nombreestado "
                . "FROM " . $this->table_name . " o "
                . "join clientes c on o.idcliente=c.idcliente "
                . "join estado e on o.idestado=e.idestado "
                . "ORDER BY " . $order;

        $data = $this->db->select_limit($sql, $limit, $offset);
        return $this->all_from_data($data);
    }

    /**
     * Devuelve un array con los albaranes pendientes.
     * @param integer $offset
     * @param string $order
     * @return \operaciones_m
     */
    public function all_ptefactura($offset = 0, $order = 'fec_operacion ASC', $limit = FS_ITEM_LIMIT)
    {
        $sql = "SELECT * ,c.nombre nombrecliente,c.ruc ruccliente,e.nombre nombreestado "
                . "FROM " . $this->table_name . " o "
                . "join clientes c on o.idcliente=c.idcliente "
                . "join estado e on o.idestado=e.idestado "
                . " WHERE ptefactura = true ORDER BY " . $order;
        $data = $this->db->select_limit($sql, $limit, $offset);
        return $this->all_from_data($data);
    }

    /**
     * Devuelve un array con los albaranes del cliente $dataodcliente.
     * @param string $dataodcliente
     * @param integer $offset
     * @return \operaciones_m
     */
    public function all_from_cliente($dataodcliente, $offset = 0)
    {
        $sql = "SELECT * FROM " . $this->table_name . " "
                . "WHERE idcliente = " . $this->var2str($dataodcliente)
            . " ORDER BY nombre DESC";

        $data = $this->db->select_limit($sql, FS_ITEM_LIMIT, $offset);
        return $this->all_from_data($data);
    }

    /**
     * Devuelve un array con los albaranes del agente/empleado
     * @param string $dataodagente
     * @param integer $offset
     * @return \operaciones_m
     */
    public function all_from_agente($dataodagente, $offset = 0)
    {
        $sql = "SELECT * FROM " . $this->table_name . " "
                . "WHERE codagente = " . $this->var2str($dataodagente)
            . " ORDER BY fecha DESC, codigo DESC";

        $data = $this->db->select_limit($sql, FS_ITEM_LIMIT, $offset);
        return $this->all_from_data($data);
    }

    /**
     * Devuelve todos los albaranes relacionados con la factura.
     * @param string $id
     * @return \operaciones_m
     */
   /* public function all_from_factura($id)
    {
        $sql = "SELECT * FROM " . $this->table_name . " "
                . "WHERE idfactura = " . $this->var2str($id)
            . " ORDER BY fecha DESC, codigo DESC;";

        $data = $this->db->select($sql);
        return $this->all_from_data($data);
    }
*/
    /**
     * Devuelve un array con los albaranes comprendidos entre $desde y $hasta
     * @param string $desde
     * @param string $hasta
     * @return \operaciones_m
     */
    public function all_desde($desde, $hasta)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE fec_operacion >= " . $this->var2str($desde)
            . " AND fec_operacion <= " . $this->var2str($hasta) . " ORDER BY idoperaciones ASC;";

        $data = $this->db->select($sql);
        return $this->all_from_data($data);
    }

    /**
     * Devuelve un array con los albaranes que coinciden con $query
     * @param string $query
     * @param integer $offset
     * @return \operaciones_m
     */
    public function search($query, $offset = 0)
    {
        $query = mb_strtolower($this->no_html($query), 'UTF8');

        $dataonsulta = "SELECT * FROM " . $this->table_name . " WHERE ";
        if (is_numeric($query)) {
            $dataonsulta .= "idoperaciones LIKE '%" . $query . "%'  OR observacion LIKE '%" . $query . "%'";
        } else {
            $dataonsulta .= "idoperaciones LIKE '%" . $query . "%' "
                . "OR lower(observacion) LIKE '%" . str_replace(' ', '%', $query) . "%'";
        }
        $dataonsulta .= " ORDER BY fec_operacion DESC, idoperaciones DESC";

        $data = $this->db->select_limit($dataonsulta, FS_ITEM_LIMIT, $offset);
        return $this->all_from_data($data);
    }

    /**
     * Devuelve un array con los albaranes del cliente $dataodcliente que coincidan
     * con los filtros.
     * @param string $dataodcliente
     * @param string $desde
     * @param string $hasta
     * @param string $dataodserie
     * @param string $obs
     * @param string $dataoddivisa
     * @return \operaciones_m
     */
    public function search_from_cliente($dataodcliente, $desde, $hasta, $dataodserie = '', $obs = '', $dataoddivisa = '')
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE idcliente = " . $this->var2str($dataodcliente)
            . " AND fec_operacion BETWEEN " . $this->var2str($desde) . " AND " . $this->var2str($hasta);

        

        if ($obs) {
            $sql .= " AND lower(observacion) = " . $this->var2str(mb_strtolower($obs, 'UTF8'));
        }

       

        $sql .= " ORDER BY fec_operacion ASC, idoperaciones ASC;";

        $data = $this->db->select($sql);
        return $this->all_from_data($data);
    }

    private function all_from_data(&$data)
    {
        $albalist = array();
        if ($data) {
            foreach ($data as $a) {
                $albalist[] = new \operaciones_m($a);
            }
        }

        return $albalist;
    }

    public function cron_job()
    {
        /**
         * Ponemos a NULL todos los idfactura que no están en facturascli.
         * ¿Por qué? Porque muchos usuarios se dedican a tocar la base de datos.
         */
        $this->db->exec("UPDATE " . $this->table_name . " SET idfactura = NULL WHERE idfactura IS NOT NULL"
            . " AND idfactura NOT IN (SELECT idfactura FROM facturascli);");
        /// asignamos netosindto a neto a todos los que estén a 0
        $this->db->exec("UPDATE " . $this->table_name . " SET netosindto = neto WHERE netosindto = 0;");
    }
    
   
    
    public function elimina_respaldo($ciconyuge)
    {
        $bc= new \respaldo_m;
        return $bc->elimina_respaldo($ciconyuge);//$dir->all_from_conyuge($this->cedula_titular);
    }
    /* public function actualiza_pedido_baucher($nro){        
        $sql = "UPDATE " . $this->table_name . " SET " .                                            
                 " ptefactura = 2, ".  
                " observaciones = 'VOUCHER' ".  
                "  WHERE idoperaciones = " . $nro. ";";
         return $this->db->exec($sql);
    }*/
    
}
