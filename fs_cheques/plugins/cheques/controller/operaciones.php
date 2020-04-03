<?php


require_once 'plugins/cheques/extras/fbase_controller.php';

class operaciones extends fbase_controller
{

    public $agente;
    public $almacenes;
    public $articulo;
    public $buscar_lineas;
    public $cliente;
    public $codagente;
    public $codalmacen;
    public $codgrupo;
    public $codpago;
    public $codestado;
    public $desde;
    public $forma_pago;
    public $grupo;
    public $hasta;
    public $lineas;
    public $mostrar;
    public $num_resultados;
    public $offset;
    public $order;
    public $resultados;
    public $serie;
    public $total_resultados;
    public $total_resultados_txt;

    
     public $estado;
     
    public function __construct()
    {
        parent::__construct(__CLASS__,'Operaciones' , 'Movimientos'); //ucfirst(FS_ALBARANES)
    }

    protected function private_core()
    {
        parent::private_core();

        $albaran = new operaciones_m();
        $this->agente = new agente();
        
        $this->estado = new estado_m();

        $this->mostrar = 'todo';
        if (isset($_GET['mostrar'])) {
            $this->mostrar = $_GET['mostrar'];
            setcookie('ventas_alb_mostrar', $this->mostrar, time() + FS_COOKIES_EXPIRE);
        } else if (isset($_COOKIE['ventas_alb_mostrar'])) {
            $this->mostrar = $_COOKIE['ventas_alb_mostrar'];
        }

        $this->offset = 0;
        if (isset($_REQUEST['offset'])) {
            $this->offset = intval($_REQUEST['offset']);
        }

        $this->order = 'fec_operacion DESC';
        if (isset($_GET['order'])) {
            $orden_l = $this->orden();
            if (isset($orden_l[$_GET['order']])) {
                $this->order = $orden_l[$_GET['order']]['orden'];
            }

            setcookie('ventas_alb_order', $this->order, time() + FS_COOKIES_EXPIRE);
        } else if (isset($_COOKIE['ventas_alb_order'])) {
            $this->order = $_COOKIE['ventas_alb_order'];
        }

        if (isset($_POST['buscar_lineas'])) {
            $this->buscar_lineas();
        } else if (isset($_REQUEST['buscar_cliente'])) {
            $this->fbase_buscar_cliente($_REQUEST['buscar_cliente']);
        } else if (isset($_GET['ref'])) {
            $this->template = 'extension/ventas_albaranes_articulo';

            $articulo = new articulo();
            $this->articulo = $articulo->get($_GET['ref']);

            $linea = new linea_albaran_cliente();
            $this->resultados = $linea->all_from_articulo($_GET['ref'], $this->offset);
        } else {
            $this->share_extension();
            $this->cliente = FALSE;
            $this->codagente = '';
            $this->codalmacen = '';
            $this->codgrupo = '';
            $this->codpago = '';
            $this->codestado = '';
            $this->desde = '';
            $this->hasta = '';
            $this->num_resultados = '';
            $this->total_resultados = array();
            $this->total_resultados_txt = '';

            if (isset($_POST['delete'])) {
                $this->delete_albaran();
            } else {
                if (!isset($_GET['mostrar']) && ( $this->query != '' || isset($_REQUEST['codagente']) || isset($_REQUEST['codcliente']) || isset($_REQUEST['codestado']))) {
                    /**
                     * si obtenermos un codagente, un codcliente o un codestado pasamos direcatemente
                     * a la pestaña de búsqueda, a menos que tengamos un mostrar, que
                     * entonces nos indica donde tenemos que estar.
                     */
                    $this->mostrar = 'buscar';
                }

                if (isset($_REQUEST['codcliente']) && $_REQUEST['codcliente'] != '') {
                    $cli0 = new cliente_m();
                    $this->cliente = $cli0->get($_REQUEST['codcliente']);
                }

                if (isset($_REQUEST['codagente'])) {
                    $this->codagente = $_REQUEST['codagente'];
                }

                if (isset($_REQUEST['codalmacen'])) {
                    $this->codalmacen = $_REQUEST['codalmacen'];
                }

                if (isset($_REQUEST['codgrupo'])) {
                    $this->codgrupo = $_REQUEST['codgrupo'];
                }

                if (isset($_REQUEST['codpago'])) {
                    $this->codpago = $_REQUEST['codpago'];
                }

                if (isset($_REQUEST['codestado'])) {
                    $this->codestado = $_REQUEST['codestado'];
                }

                if (isset($_REQUEST['desde'])) {
                    $this->desde = $_REQUEST['desde'];
                    $this->hasta = $_REQUEST['hasta'];
                }
            }

            /// añadimos segundo nivel de ordenación
            $order2 = '';
            if ($this->order == 'fecha DESC') {
                $order2 = ', hora DESC';
            } else if ($this->order == 'fecha ASC') {
                $order2 = ', hora ASC';
            }

            if ($this->mostrar == 'pendientes') {
                $this->resultados = $albaran->all_ptefactura($this->offset, $this->order . $order2);

                if ($this->offset == 0) {
                    /// calculamos el total, pero desglosando por divisa
                    $this->total_resultados = array();
                    $this->total_resultados_txt = 'Suma total de esta página:';
                    /*foreach ($this->resultados as $alb) {
                        if (!isset($this->total_resultados[$alb->coddivisa])) {
                            $this->total_resultados[$alb->coddivisa] = array(
                                'coddivisa' => $alb->coddivisa,
                                'total' => 0
                            );
                        }

                        $this->total_resultados[$alb->coddivisa]['total'] += $alb->total;
                    }*/
                }
            } else if ($this->mostrar == 'buscar') {
                $this->buscar($order2);
            } else {
                //LA PRIMERTA PESTAÑA DONDE TRAE TODOS LOS DATOS
                $this->resultados = $albaran->all($this->offset, $this->order . $order2);
            }
        }
    }

    public function url($busqueda = FALSE)
    {
        if ($busqueda) {
            $codcliente = '';
            if ($this->cliente) {
                $codcliente = $this->cliente->idcliente;
            }

            $url = parent::url() . "&mostrar=" . $this->mostrar
                . "&query=" . $this->query
                . "&codagente=" . $this->codagente
                . "&codalmacen=" . $this->codalmacen
                . "&codcliente=" . $codcliente
                . "&codgrupo=" . $this->codgrupo
                . "&codpago=" . $this->codpago
                . "&codestado=" . $this->codestado
                . "&desde=" . $this->desde
                . "&hasta=" . $this->hasta;

            return $url;
        }

        return parent::url();
    }

    public function paginas()
    {
        if ($this->mostrar == 'pendientes') {
            $total = $this->total_pendientes();
        } else if ($this->mostrar == 'buscar') {
            $total = $this->num_resultados;
        } else {
            $total = $this->total_registros();
        }

        return $this->fbase_paginas($this->url(TRUE), $total, $this->offset);
    }

    public function buscar_lineas()
    {
        /// cambiamos la plantilla HTML
        $this->template = 'ajax/operaciones_lineas';

        $this->buscar_lineas = $_POST['buscar_lineas'];
        $linea = new linea_albaran_cliente();

        if (isset($_POST['codcliente'])) {
            $this->lineas = $linea->search_from_cliente2($_POST['codcliente'], $this->buscar_lineas, $_POST['buscar_lineas_o'], $this->offset);
        } else {
            $this->lineas = $linea->search($this->buscar_lineas, $this->offset);
        }
    }

    private function delete_albaran()
    {
        $alb = new operaciones_m();
        $alb1 = $alb->get($_POST['delete']);
        if ($alb1) {            
            if ($alb1->delete()) {
                $this->clean_last_changes();
            } else {
                $this->new_error_msg("¡Imposible eliminar la operacion!");
            }
        } else {
            $this->new_error_msg("¡Operacion no encontrado!");
        }
    }

    private function share_extension()
    {
        /// añadimos las extensiones para clientes, agentes y artículos
        $extensiones = array(
            array(
                'name' => 'albaranes_cliente',
                'page_from' => __CLASS__,
                'page_to' => 'ventas_cliente',
                'type' => 'button',
                'text' => '<span class="glyphicon glyphicon-list" aria-hidden="true"></span> &nbsp; ' . ucfirst(FS_ALBARANES),
                'params' => ''
            ),
            array(
                'name' => 'albaranes_agente',
                'page_from' => __CLASS__,
                'page_to' => 'admin_agente',
                'type' => 'button',
                'text' => '<span class="glyphicon glyphicon-list" aria-hidden="true"></span> &nbsp; ' . ucfirst(FS_ALBARANES) . ' de cliente',
                'params' => ''
            ),
            array(
                'name' => 'albaranes_articulo',
                'page_from' => __CLASS__,
                'page_to' => 'ventas_articulo',
                'type' => 'tab_button',
                'text' => '<span class="glyphicon glyphicon-list" aria-hidden="true"></span> &nbsp; ' . ucfirst(FS_ALBARANES) . ' de cliente',
                'params' => ''
            ),
        );
        foreach ($extensiones as $ext) {
            $fsext0 = new fs_extension($ext);
            if (!$fsext0->save()) {
                $this->new_error_msg('Imposible guardar los datos de la extensión ' . $ext['name'] . '.');
            }
        }
    }

    public function total_pendientes()
    {
        return $this->fbase_sql_total('operaciones', 'idoperaciones', 'WHERE ptefactura = true');
    }

    private function total_registros()
    {
        return $this->fbase_sql_total('operaciones', 'idoperaciones');
    }

    private function buscar($order2)
    {
        $this->resultados = array();
        $this->num_resultados = 0;
        $sql = " FROM operaciones o  "
                 . "join clientes c on o.idcliente=c.idcliente "
                 . "join estado e on o.idestado=e.idestado ";
        $where = 'WHERE ';

        if ($this->query) {
            $query = $this->agente->no_html(mb_strtolower($this->query, 'UTF8'));
            $sql .= $where;
            if (is_numeric($query)) {
                $sql .= "(idoperaciones LIKE '%" . $query . "%' OR observacion LIKE '%" . $query . "%')";
            } else {
                $sql .= "(idoperaciones LIKE '%" . $query . "%' OR lower(observacion) LIKE '%" . str_replace(' ', '%', $query) . "%')";
            }
            $where = ' AND ';
        }

        if ($this->cliente) {
            $sql .= $where . " o.idcliente = " . $this->agente->var2str($this->cliente->idcliente);
            $where = ' AND ';
        }

        if ($this->codagente != '') {
            $sql .= $where . "codagente = " . $this->agente->var2str($this->codagente);
            $where = ' AND ';
        }
                      

        if ($this->codestado != '') {
            $sql .= $where . "o.idestado = " . $this->agente->var2str($this->codestado);
            $where = ' AND ';
        }

        if ($this->desde) {
            $sql .= $where . "fec_operacion >= " . $this->agente->var2str($this->desde);
            $where = ' AND ';
        }

        if ($this->hasta) {
            $sql .= $where . "fec_operacion <= " . $this->agente->var2str($this->hasta);
            $where = ' AND ';
        }

        $data = $this->db->select("SELECT COUNT(idoperaciones) as total" . $sql);
        if ($data) {
            $this->num_resultados = intval($data[0]['total']);

            $data2 = $this->db->select_limit("SELECT *,c.nombre nombrecliente,c.ruc ruccliente,e.nombre nombreestado "
                    . "" . $sql . " ORDER BY " . $this->order . $order2, FS_ITEM_LIMIT, $this->offset);
            if ($data2) {
                foreach ($data2 as $d) {
                    $this->resultados[] = new operaciones_m($d);
                }
            }

           /* $data2 = $this->db->select("SELECT coddivisa,SUM(total) as total" . $sql . " GROUP BY coddivisa");
            if ($data2) {
                $this->total_resultados_txt = 'Suma total de los resultados:';

                foreach ($data2 as $d) {
                    $this->total_resultados[] = array(
                        'coddivisa' => $d['coddivisa'],
                        'total' => floatval($d['total'])
                    );
                }
            }*/
        }
    }

    public function orden()
    {
        return array(
            'fecha_desc' => array(
                'icono' => '<span class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>',
                'texto' => 'Fecha',
                'orden' => 'fec_operacion DESC'
            ),
            'fecha_asc' => array(
                'icono' => '<span class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>',
                'texto' => 'Fecha',
                'orden' => 'fec_operacion ASC'
            ),
            'codigo_desc' => array(
                'icono' => '<span class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>',
                'texto' => 'Código',
                'orden' => 'idoperaciones DESC'
            ),
            'codigo_asc' => array(
                'icono' => '<span class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>',
                'texto' => 'Código',
                'orden' => 'idoperaciones ASC'
            )
        );
    }
}
