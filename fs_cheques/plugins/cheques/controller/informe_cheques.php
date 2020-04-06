<?php

require_once 'plugins/cheques/extras/fbase_controller.php';
//require_once 'plugins/fs_barberia_facturacion_base/extras/fs_pdf.php';
require_once 'extras/xlsxwriter.class.php';

class informe_cheques extends fbase_controller
{

    public $agente;
    public $almacen;
    public $cliente;
    public $codagente;
    public $codalmacen;
    public $coddivisa;
    public $codpago;
    public $codserie;
    public $desde;
    public $divisa;
    public $forma_pago;
    public $hasta;
    public $proveedor;
    public $serie;
    protected $nombre_docs;
    protected $table_compras;
    protected $table_ventas;
    protected $where_compras;
    protected $where_compras_nf;
    protected $where_ventas;
    protected $where_ventas_nf;
    
    public $desdevto;
    public $hastavto;
    public $ordende;
    public $codbanco;
    public $codestado;
   

    public $banco;
    public $estado;
    
    /**
     * Este controlador lo usaremos de ejemplo en otros, así que debemos permitir usar su constructor.
     * @param system $name
     * @param type $title
     * @param string $folder
     * @param type $admin
     * @param type $shmenu
     * @param type $important
     */
    public function __construct($name = '', $title = 'home', $folder = '', $admin = FALSE, $shmenu = TRUE, $important = FALSE)
    {
        if ($name == '') {
            /**
             * si no se proporciona un $name es que estamos usando este mismo controlador,
             * por lo que establecemos los valores.
             */
            $name = __CLASS__;
            //$title = ucfirst(FS_ALBARANES);
            $title = ucfirst('Cheques');
            $folder = 'informes';
        }

        parent::__construct($name, $title, $folder, $admin, $shmenu, $important);
    }

    protected function private_core()
    {
        parent::private_core();

        /// declaramos los objetos sólo para asegurarnos de que existen las tablas
        new cheque_m();
        //new albaran_proveedor();

        $this->banco = new banco_m();
        $this->estado = new estado_m();
//        $this->divisa = new divisa();
//        $this->forma_pago = new forma_pago();
//        $this->serie = new serie();

        if (!isset($this->nombre_docs)) {
            $this->nombre_docs = "Cheques";
            $this->table_compras = 'cheques';
           // $this->table_ventas = 'albaranescli';
        }

        if (isset($_REQUEST['buscar_cliente'])) {
            $this->fbase_buscar_cliente($_REQUEST['buscar_cliente']);
        }  else {
            $this->ini_filters();
            $this->set_where();

            if (isset($_POST['generar'])) {
                if ($_POST['generar'] == 'pdf_cli') {
                    $this->generar_pdf('venta');
                } else if ($_POST['generar'] == 'xls_cli') {
                   // $this->new_advice("entra");
                    //GENERAR REPORTE DE CHEQUE                                                            
                    $this->generar_xls('compra');
                /*foreach ($this->get_documentos('cheques') as $doc) {

                }*/
                } else if ($_POST['generar'] == 'csv_cli') {
                    $this->generar_csv('venta');
                } else if ($_POST['generar'] == 'pdf_prov') {
                    $this->generar_pdf('compra');
                } else if ($_POST['generar'] == 'xls_prov') {
                    $this->generar_xls('compra');
                } else if ($_POST['generar'] == 'csv_prov') {
                    $this->generar_csv('compra');
                } else {
                    $this->generar_extra();
                }
            }
        }
    }

    protected function generar_extra()
    {
        /// a completar en el informe de facturas
    }

    /**
     * Obtenemos los valores de los filtros del formulario.
     */
    protected function ini_filters()
    {
        $this->desde = Date('01-m-Y');
        if (isset($_REQUEST['desde'])) {
            $this->desde = $_REQUEST['desde'];
        }

        $this->hasta = Date('t-m-Y');
        if (isset($_REQUEST['hasta'])) {
            $this->hasta = $_REQUEST['hasta'];
        }
        
        //$this->desdevto = Date('01-m-Y', strtotime('-14 months'));
        if (isset($_REQUEST['desdevto'])) {
            $this->desdevto = $_REQUEST['desdevto'];
        }

        //$this->hastavto = Date('t-m-Y');
        if (isset($_REQUEST['hastavto'])) {
            $this->hastavto = $_REQUEST['hastavto'];
        }
        
        $this->codbanco = FALSE;
        if (isset($_REQUEST['codbanco'])) {
            $this->codbanco = $_REQUEST['codbanco'];
        }
        
        $this->cliente = FALSE;
        if (isset($_REQUEST['codcliente'])) {
            if ($_REQUEST['codcliente'] != '') {
                $cli0 = new cliente_m();
                $this->cliente = $cli0->get($_REQUEST['codcliente']);
            }
        }

        $this->ordende = FALSE;
        if (isset($_REQUEST['ordende'])) {
            $this->ordende = $_REQUEST['ordende'];
        }

        $this->codestado = FALSE;
        if (isset($_REQUEST['codestado'])) {
            $this->codestado = $_REQUEST['codestado'];
        }
               
//        $this->proveedor = FALSE;
//        if (isset($_REQUEST['codproveedor'])) {
//            if ($_REQUEST['codproveedor'] != '') {
//                $prov0 = new proveedor();
//                $this->proveedor = $prov0->get($_REQUEST['codproveedor']);
//            }
//        }
    }

    /**
     * Contruimos sentencias where para las consultas sql.
     */
    protected function set_where()
    {
        $this->where_compras = " WHERE fec_emision >= " . $this->empresa->var2str($this->desde)
            . " AND fec_emision <= " . $this->empresa->var2str($this->hasta);

        /// nos guardamos un where sin fechas
        //$this->where_compras_nf = " WHERE 1 = 1";
        if ($this->hastavto) {
            $this->where_compras .= " AND fec_pago >=  " . $this->empresa->var2str($this->desdevto)
                    ." AND fec_pago <= " . $this->empresa->var2str($this->hastavto);
            //$this->where_compras_nf .= "fec_emision <= = " . $this->empresa->var2str($this->codserie);
        }
        
        if ($this->codbanco) {
            $this->where_compras .= " AND cheques_codbanco = " . $this->empresa->var2str($this->codbanco);
            $this->where_compras_nf .= " AND cheques_codbanco = " . $this->empresa->var2str($this->codbanco);
        }
              
        $this->where_ventas = $this->where_compras;
        $this->where_ventas_nf = $this->where_compras_nf;

        if ($this->cliente) {
            $this->where_compras .= " AND cli.idcliente = " . $this->empresa->var2str($this->cliente->idcliente);
            $this->where_compras_nf .= " AND cli.idcliente = " . $this->empresa->var2str($this->cliente->idcliente);
        }

        if ($this->ordende) {
            $this->where_compras .= " AND aordende like " . "'%".$this->ordende."%'";
            $this->where_compras_nf .= " AND aordende like  " . "'%".$this->ordende."%'";
        }
        
        if ($this->codestado) {
            $this->where_compras .= " AND e.idestado = " . $this->empresa->var2str($this->codestado);
            $this->where_compras_nf .= " AND e.idestado = " . $this->empresa->var2str($this->codestado);
        }

    }
    
    //CONSULTA PARA TRAER LOS DATOS DEL CHEQUE Y DE LA OPERACION GIO 06/04/2020
  protected function get_documentos($tabla)
    {
        $doclist = array();

        $where = $this->where_compras;
        /*if ($tabla == $this->table_ventas) {
            $where = $this->where_ventas;
        }*/

        /*$sql = "select * from " . $tabla . $where . " order by fecha asc, codigo asc;";*/ //ANTERIOR
        $sql = "select co.cheques_nrocheque nrocheque,b.nombre banconombre,c.nrocuenta,c.fec_emision,c.fec_pago,c.importe,
                c.aordende,o.idoperaciones nrooperacion,e.nombre estado,
                cli.nombre clientenombre,c.idcliente,cheques_codbanco idbanco,c.tipo
                from operaciones o 
                join cheque_operacion co on o.idoperaciones=co.idoperaciones
                join " .$tabla. " c on co.cheques_nrocheque=c.nrocheque
                join clientes cli on o.idcliente=cli.idcliente
                join bancos b on co.cheques_codbanco=b.idbanco
                join estado e on o.idestado=e.idestado "
                . $where . " "
                . " order by c.fec_emision asc, o.idoperaciones asc;";
        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $d) {
                if ($tabla == $this->table_compras) {
                    $doclist[] = new cheque_m($d);
                } else {
                    //COMENTE GIO 23/03/2020 XQ NO SE USA
                   // $doclist[] = new albaran_proveedor($d);
                }
            }
        }

        return $doclist;
    }
    
 protected function generar_xls($tipo = 'compra')
    {
        /// desactivamos el motor de plantillas
        $this->template = FALSE;

        header("Content-Disposition: attachment; filename=\"informe_" . $this->nombre_docs . "_" . time() . ".xlsx\"");
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $header = array(
            'nrocheque' => 'string',
            'banco' => 'string',
            'nrocuenta' => 'string',
            'fec.emision' => 'string',
            'fec.pago' => 'string',            
            'importe' => '#,##0.00;[RED]-#,##0.00',           
            'ordende' => 'string',
            'clientetitular' => 'string',
            'nrooperacion' => 'string',
            'estado' => 'string',
        );

        if ($tipo == 'compra') {
            $tabla = $this->table_compras;
            //unset($header['num2']);
           // unset($header['cliente']);
        } else {
            //$tabla = $this->table_ventas;
           // unset($header['num.proveedor']);
           // unset($header['proveedor']);
        }

        $writter = new XLSXWriter();
        $writter->setAuthor('FacturaScripts');
        $writter->writeSheetHeader($this->nombre_docs, $header);

        foreach ($this->get_documentos($tabla) as $doc) {
            $linea = array(                
                'nrocheque' => $doc->nrocheque,
                'banco' => $doc->banconombre,
                'nrocuenta' => $doc->nrocuenta,
                'fec.emision' => $doc->fec_emision,                               
                'fec.pago' => $doc->fec_pago,
                'importe' => $doc->importe,
                'ordende' => $doc->aordende,
                'clientetitular' => $doc->clientenombre,
                'nrooperacion' => $doc->nrooperacion,
                'estado' => $doc->estado,               
            );

            if ($tipo == 'compra') {
//                $linea['num.proveedor'] = $doc->numproveedor;
//                $linea['proveedor'] = $doc->nombre;
//                unset($linea['num2']);
//                unset($linea['cliente']);
            } else {
//                $linea['num2'] = $doc->numero2;
//                $linea['cliente'] = $doc->nombrecliente;
//                unset($linea['num.proveedor']);
//                unset($linea['proveedor']);
            }

            $writter->writeSheetRow($this->nombre_docs, $linea);
        }

        $writter->writeToStdOut();
    }

    /**
     * Devuelve un array con las compras y ventas por día en el plazo de un mes desde hoy.
     * @return type
     */
    public function stats_last_30_days()
    {
        $stats = array();
        $stats_cli = $this->stats_last_30_days_aux($this->table_ventas);
        $stats_pro = $this->stats_last_30_days_aux($this->table_compras);
        $meses = array(
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic'
        );

        foreach ($stats_cli as $i => $value) {
            $mes = $meses[intval(date('m', strtotime($value['day'])))];

            $stats[$i] = array(
                'day' => date('d', strtotime($value['day'])) . $mes, /// el identificado day será dia + mes
                'total_cli' => round($value['total'], FS_NF0),
                'total_pro' => 0
            );
        }

        foreach ($stats_pro as $i => $value) {
            $stats[$i]['total_pro'] = round($value['total'], FS_NF0);
        }

        return $stats;
    }

    /**
     * Función auxiliar para obtener las compras o ventas por día en el plazo de un mes desde hoy.
     * @param type $table_name
     * @return type
     */
    protected function stats_last_30_days_aux($table_name)
    {
        $stats = array();
        $hasta = date('d-m-Y');
        $desde = date('d-m-Y', strtotime($hasta . '-1 month'));

        /// inicializamos los resultados
        foreach ($this->date_range($desde, $hasta) as $date) {
            $i = strtotime($date);
            $stats[$i] = array(
                'day' => date('d-m-Y', $i),
                'total' => 0
            );
        }

        $where = $this->where_compras_nf;
        if ($table_name == $this->table_ventas) {
            $where = $this->where_ventas_nf;
        }

        $sql = "SELECT fecha as dia, SUM(neto) as total FROM " . $table_name . $where
            . " AND fecha >= " . $this->empresa->var2str($desde)
            . " AND fecha <= " . $this->empresa->var2str($hasta)
            . " GROUP BY dia ORDER BY dia ASC;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $d) {
                $i = strtotime($d['dia']);
                $stats[$i]['total'] = floatval($d['total']);
            }
        }

        return $stats;
    }

    /**
     * Devuelve un array con las compras y ventas agrupadas por mes.
     * @return type
     */
    public function stats_months()
    {
        $stats = array();
        $stats_cli = $this->stats_months_aux($this->table_ventas);
        $stats_pro = $this->stats_months_aux($this->table_compras);
        $meses = array(
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic'
        );

        foreach ($stats_cli as $i => $value) {
            $stats[$i] = array(
                'month' => $meses[intval($value['month'])] . $value['year'], /// el identificador del mes es mes+año
                'total_cli' => round($value['total'], FS_NF0),
                'total_pro' => 0
            );
        }

        foreach ($stats_pro as $i => $value) {
            $stats[$i]['total_pro'] = round($value['total'], FS_NF0);
        }

        return $stats;
    }

    /**
     * Función auxiliar para obtener las compras o ventas agrupadas por mes.
     * @param type $table_name
     * @return type
     */
    protected function stats_months_aux($table_name)
    {
        $stats = array();

        /// inicializamos los resultados
        foreach ($this->date_range($this->desde, $this->hasta, '+1 month') as $date) {
            $i = intval(date('my', strtotime($date)));
            $stats[$i] = array(
                'month' => date('m', strtotime($date)),
                'year' => date('y', strtotime($date)),
                'total' => 0
            );
        }

        if (strtolower(FS_DB_TYPE) == 'postgresql') {
            $sql_aux = "to_char(fecha,'FMMMYY')";
        } else {
            $sql_aux = "DATE_FORMAT(fecha, '%m%y')";
        }

        $where = $this->where_compras;
        if ($table_name == $this->table_ventas) {
            $where = $this->where_ventas;
        }

        $sql = "SELECT " . $sql_aux . " as mes, SUM(neto) as total FROM " . $table_name
            . $where . " GROUP BY " . $sql_aux . " ORDER BY mes ASC;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $d) {
                $i = intval($d['mes']);
                $stats[$i]['total'] = floatval($d['total']);
            }
        }

        return $stats;
    }

    /**
     * Devuelve un array con las compras y ventas agrupadas por año.
     * @return type
     */
    public function stats_years()
    {
        $stats = array();
        $stats_cli = $this->stats_years_aux($this->table_ventas);
        $stats_pro = $this->stats_years_aux($this->table_compras);

        foreach ($stats_cli as $i => $value) {
            $stats[$i] = array(
                'year' => $value['year'],
                'total_cli' => round($value['total'], FS_NF0),
                'total_pro' => 0
            );
        }

        foreach ($stats_pro as $i => $value) {
            $stats[$i]['total_pro'] = round($value['total'], FS_NF0);
        }

        return $stats;
    }

    /**
     * Función auxiliar para obtener las compras o ventas agrupadas por año.
     * @param type $table_name
     * @param type $num
     * @return type
     */
    protected function stats_years_aux($table_name, $num = 4)
    {
        $stats = array();

        /// inicializamos los resultados
        $desde = date('1-1-Y', strtotime($this->desde));
        foreach ($this->date_range($desde, $this->hasta, '+1 year', 'Y') as $date) {
            $i = intval($date);
            $stats[$i] = array('year' => $i, 'total' => 0);
        }

        if (strtolower(FS_DB_TYPE) == 'postgresql') {
            $sql_aux = "to_char(fecha,'FMYYYY')";
        } else
            $sql_aux = "DATE_FORMAT(fecha, '%Y')";

        $where = $this->where_compras;
        if ($table_name == $this->table_ventas) {
            $where = $this->where_ventas;
        }

        $data = $this->db->select("SELECT " . $sql_aux . " as ano, sum(neto) as total FROM " . $table_name
            . $where . " GROUP BY " . $sql_aux . " ORDER BY ano ASC;");

        if ($data) {
            foreach ($data as $d) {
                $i = intval($d['ano']);
                $stats[$i]['total'] = floatval($d['total']);
            }
        }

        return $stats;
    }

    protected function date_range($first, $last, $step = '+1 day', $format = 'd-m-Y')
    {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);

        while ($current <= $last) {
            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    public function stats_series($tabla)
    {
        $stats = array();

        $where = $this->where_compras;
        if ($tabla == $this->table_ventas) {
            $where = $this->where_ventas;
        }

        $sql = "select codserie,sum(neto) as total from " . $tabla;
        $sql .= $where;
        $sql .= " group by codserie order by total desc;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $d) {
                $serie = $this->serie->get($d['codserie']);
                if ($serie) {
                    $stats[] = array(
                        'txt' => $serie->descripcion,
                        'total' => round(floatval($d['total']), FS_NF0)
                    );
                } else {
                    $stats[] = array(
                        'txt' => $d['codserie'],
                        'total' => round(floatval($d['total']), FS_NF0)
                    );
                }
            }
        }

        return $stats;
    }

    public function stats_agentes($tabla)
    {
        $stats = array();

        $where = $this->where_compras;
        if ($tabla == $this->table_ventas) {
            $where = $this->where_ventas;
        }

        $sql = "select codagente,sum(neto) as total from " . $tabla;
        $sql .= $where;
        $sql .= " group by codagente order by total desc;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $d) {
                if (is_null($d['codagente'])) {
                    $stats[] = array(
                        'txt' => 'Ninguno',
                        'total' => round(floatval($d['total']), FS_NF0)
                    );
                } else {
                    $agente = $this->agente->get($d['codagente']);
                    if ($agente) {
                        $stats[] = array(
                            'txt' => $agente->get_fullname(),
                            'total' => round(floatval($d['total']), FS_NF0)
                        );
                    } else {
                        $stats[] = array(
                            'txt' => $d['codagente'],
                            'total' => round(floatval($d['total']), FS_NF0)
                        );
                    }
                }
            }
        }

        return $stats;
    }

    public function stats_almacenes($tabla)
    {
        $stats = array();

        $where = $this->where_compras;
        if ($tabla == $this->table_ventas) {
            $where = $this->where_ventas;
        }

        $sql = "select codalmacen,sum(neto) as total from " . $tabla;
        $sql .= $where;
        $sql .= " group by codalmacen order by total desc;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $d) {
                $alma = $this->almacen->get($d['codalmacen']);
                if ($alma) {
                    $stats[] = array(
                        'txt' => $alma->nombre,
                        'total' => round(floatval($d['total']), FS_NF0)
                    );
                } else {
                    $stats[] = array(
                        'txt' => $d['codalmacen'],
                        'total' => round(floatval($d['total']), FS_NF0)
                    );
                }
            }
        }

        return $stats;
    }

    public function stats_formas_pago($tabla)
    {
        $stats = array();

        $where = $this->where_compras;
        if ($tabla == $this->table_ventas) {
            $where = $this->where_ventas;
        }

        $sql = "select codpago,sum(neto) as total from " . $tabla;
        $sql .= $where;
        $sql .= " group by codpago order by total desc;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $d) {
                $formap = $this->forma_pago->get($d['codpago']);
                if ($formap) {
                    $stats[] = array(
                        'txt' => $formap->descripcion,
                        'total' => round(floatval($d['total']), FS_NF0)
                    );
                } else {
                    $stats[] = array(
                        'txt' => $d['codpago'],
                        'total' => round(floatval($d['total']), FS_NF0)
                    );
                }
            }
        }

        return $stats;
    }

    public function stats_estados($tabla)
    {
        $stats = array();

        $where = $this->where_compras;
        if ($tabla == $this->table_ventas) {
            $where = $this->where_ventas;
        }

        /// aprobados
        $sql = "select sum(neto) as total from " . $tabla;
        $sql .= $where;
        $sql .= " and idfactura is not null order by total desc;";

        $data = $this->db->select($sql);
        if ($data) {
            if (floatval($data[0]['total'])) {
                $stats[] = array(
                    'txt' => 'facturado',
                    'total' => round(floatval($data[0]['total']), FS_NF0)
                );
            }
        }

        /// pendientes
        $sql = "select sum(neto) as total from " . $tabla;
        $sql .= $where;
        $sql .= " and idfactura is null order by total desc;";

        $data = $this->db->select($sql);
        if ($data) {
            if (floatval($data[0]['total'])) {
                $stats[] = array(
                    'txt' => 'no facturado',
                    'total' => round(floatval($data[0]['total']), FS_NF0)
                );
            }
        }

        return $stats;
    }

    /**
     * Esta función sirve para generar el javascript necesario para que la vista genere
     * las gráficas, ahorrando mucho código.
     * @param type $data
     * @param type $chart_id
     * @return string
     */
    public function generar_chart_pie_js(&$data, $chart_id)
    {
        $js_txt = '';

        if ($data) {
            echo "var " . $chart_id . "_labels = [];\n";
            echo "var " . $chart_id . "_data = [];\n";

            foreach ($data as $d) {
                echo $chart_id . '_labels.push("' . $d['txt'] . '"); ';
                echo $chart_id . '_data.push("' . abs($d['total']) . '");' . "\n";
            }

            /// hacemos el apaño para evitar el problema de charts.js con tabs en boostrap
            echo "var " . $chart_id . "_ctx = document.getElementById('" . $chart_id . "').getContext('2d');\n";
            echo $chart_id . "_ctx.canvas.height = 100;\n";

            echo "var " . $chart_id . "_chart = new Chart(" . $chart_id . "_ctx, {
            type: 'pie',
            data: {
               labels: " . $chart_id . "_labels,
               datasets: [
                  {
                     backgroundColor: default_colors,
                     data: " . $chart_id . "_data
                  }
               ]
            }
         });";
        }

        return $js_txt;
    }



    protected function generar_pdf($tipo = 'compra')
    {
        /// desactivamos el motor de plantillas
        $this->template = FALSE;

        $pdf_doc = new fs_pdf('a4', 'landscape', 'Courier');
        $pdf_doc->pdf->addInfo('Title', $this->nombre_docs . ' de ' . $tipo . ' del ' . $this->desde . ' al ' . $this->hasta);
        $pdf_doc->pdf->addInfo('Subject', $this->nombre_docs . ' de ' . $tipo . ' del ' . $this->desde . ' al ' . $this->hasta);
        $pdf_doc->pdf->addInfo('Author', fs_fix_html($this->empresa->nombre));

        $cliente = 'Proveedor';
        $num2 = 'Num. proveedor';
        $tabla = $this->table_compras;
        if ($tipo == 'venta') {
            $cliente = 'Cliente';
            $num2 = FS_NUMERO2;
            $tabla = $this->table_ventas;
        }

        $encabezado = fs_fix_html($this->empresa->nombre) . ' - ' . $this->nombre_docs
            . ' de ' . $tipo . ' del ' . $this->desde . ' al ' . $this->hasta;

        if ($this->codagente) {
            $encabezado .= ', empleado: ' . $this->codagente;
        }

        if ($this->codserie) {
            $encabezado .= ', serie: ' . $this->codserie;
        }

        if ($this->coddivisa) {
            $encabezado .= ', divisa: ' . $this->coddivisa;
        }

        if ($this->codpago) {
            $encabezado .= ', forma de pago: ' . $this->codpago;
        }

        if ($this->codalmacen) {
            $encabezado .= ', almacén ' . $this->codalmacen;
        }

        $documentos = $this->get_documentos($tabla);
        if (!empty($documentos)) {
            $total_lineas = count($documentos);
            $linea_actual = 0;
            $lppag = 72;
            $pagina = 1;
            $neto = $totaliva = $totalre = $totalirpf = $total = 0;

            while ($linea_actual < $total_lineas) {
                if ($linea_actual > 0) {
                    $pdf_doc->pdf->ezNewPage();
                    $pagina++;
                }

                /// encabezado
                $pdf_doc->pdf->ezText($encabezado . ".\n\n");

                /// tabla principal
                $pdf_doc->new_table();
                $pdf_doc->add_table_header(
                    array(
                        'serie' => '<b>' . strtoupper(FS_SERIE) . '</b>',
                        'doc' => '<b>Documento</b>',
                        'num2' => '<b>' . $num2 . '</b>',
                        'fecha' => '<b>Fecha</b>',
                        'cliente' => '<b>' . $cliente . '</b>',
                        'cifnif' => '<b>' . FS_CIFNIF . '</b>',
                        'neto' => '<b>Neto</b>',
                        'iva' => '<b>' . FS_IVA . '</b>',
                        're' => '<b>RE</b>',
                        'irpf' => '<b>' . FS_IRPF . '</b>',
                        'total' => '<b>Total</b>'
                    )
                );

                for ($i = 0; $i < $lppag && $linea_actual < $total_lineas; $i++) {
                    $linea = array(
                        'serie' => $documentos[$linea_actual]->codserie,
                        'doc' => $documentos[$linea_actual]->codigo,
                        'num2' => '',
                        'fecha' => $documentos[$linea_actual]->fecha,
                        'cliente' => '',
                        'cifnif' => $documentos[$linea_actual]->cifnif,
                        'neto' => $this->show_numero($documentos[$linea_actual]->neto),
                        'iva' => $this->show_numero($documentos[$linea_actual]->totaliva),
                        're' => $this->show_numero($documentos[$linea_actual]->totalrecargo),
                        'irpf' => $this->show_numero($documentos[$linea_actual]->totalirpf),
                        'total' => $this->show_numero($documentos[$linea_actual]->total),
                    );

                    if ($tipo == 'compra') {
                        $linea['num2'] = fs_fix_html($documentos[$linea_actual]->numproveedor);
                        $linea['cliente'] = fs_fix_html($documentos[$linea_actual]->nombre);
                    } else {
                        $linea['num2'] = fs_fix_html($documentos[$linea_actual]->numero2);
                        $linea['cliente'] = fs_fix_html($documentos[$linea_actual]->nombrecliente);
                    }

                    $pdf_doc->add_table_row($linea);

                    $neto += $documentos[$linea_actual]->neto;
                    $totaliva += $documentos[$linea_actual]->totaliva;
                    $totalre += $documentos[$linea_actual]->totalrecargo;
                    $totalirpf += $documentos[$linea_actual]->totalirpf;
                    $total += $documentos[$linea_actual]->total;
                    $i++;
                    $linea_actual++;
                }

                /// añadimos el subtotal
                $linea = array(
                    'serie' => '',
                    'doc' => '',
                    'num2' => '',
                    'fecha' => '',
                    'cliente' => '',
                    'cifnif' => '',
                    'neto' => '<b>' . $this->show_numero($neto) . '</b>',
                    'iva' => '<b>' . $this->show_numero($totaliva) . '</b>',
                    're' => '<b>' . $this->show_numero($totalre) . '</b>',
                    'irpf' => '<b>' . $this->show_numero($totalirpf) . '</b>',
                    'total' => '<b>' . $this->show_numero($total) . '</b>',
                );
                $pdf_doc->add_table_row($linea);

                $pdf_doc->save_table(
                    array(
                        'fontSize' => 8,
                        'cols' => array(
                            'neto' => array('justification' => 'right'),
                            'iva' => array('justification' => 'right'),
                            're' => array('justification' => 'right'),
                            'irpf' => array('justification' => 'right'),
                            'total' => array('justification' => 'right')
                        ),
                        'shaded' => 0,
                        'width' => 780
                    )
                );
            }

            $this->desglose_impuestos_pdf($pdf_doc, $tipo);
        } else {
            $pdf_doc->pdf->ezText($encabezado . '.');
            $pdf_doc->pdf->ezText("\nSin resultados.", 14);
        }

        $pdf_doc->show();
    }

    /**
     * Añade el desglose de impuestos al documento PDF.
     * @param fs_pdf $pdf_doc
     * @param type $tipo
     */
    protected function desglose_impuestos_pdf(&$pdf_doc, $tipo)
    {
        /// a completar en el informe de facturas
    }

  

    protected function generar_csv($tipo = 'compra')
    {
        /// desactivamos el motor de plantillas
        $this->template = FALSE;

        header("content-type:application/csv;charset=UTF-8");
        header("Content-Disposition: attachment; filename=\"informe_" . $this->nombre_docs . "_" . time() . ".csv\"");

        if ($tipo == 'compra') {
            $tabla = $this->table_compras;
            echo "serie,documento,num.proveedor,fecha,proveedor," . FS_CIFNIF . ",neto," . FS_IVA . ",re," . FS_IRPF . ",total\n";
        } else {
            $tabla = $this->table_ventas;
            echo "serie,documento," . FS_NUMERO2 . ",fecha,cliente," . FS_CIFNIF . ",neto," . FS_IVA . ",re," . FS_IRPF . ",total\n";
        }

        foreach ($this->get_documentos($tabla) as $doc) {
            $linea = array(
                'serie' => $doc->codserie,
                'doc' => $doc->codigo,
                'num2' => '',
                'fecha' => $doc->fecha,
                'cliente' => '',
                'cifnif' => $doc->cifnif,
                'neto' => $doc->neto,
                'iva' => $doc->totaliva,
                're' => $doc->totalrecargo,
                'irpf' => $doc->totalirpf,
                'total' => $doc->total,
            );

            if ($tipo == 'compra') {
                $linea['num2'] = $doc->numproveedor;
                $linea['cliente'] = fs_fix_html($doc->nombre);
            } else {
                $linea['num2'] = $doc->numero2;
                $linea['cliente'] = fs_fix_html($doc->nombrecliente);
            }

            echo '"' . join('","', $linea) . "\"\n";
        }
    }
}
