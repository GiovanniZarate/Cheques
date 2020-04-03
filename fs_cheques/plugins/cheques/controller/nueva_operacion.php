<?php
/*
 
 */

require_once 'plugins/cheques/extras/fbase_controller.php';

class nueva_operacion extends fbase_controller
{

    public $agencia;
    public $agente;
    public $almacen;
    public $articulo;
    public $cliente;
    public $cliente_s;
    public $direccion;
    public $divisa;
   // public $fabricante;
    
    public $forma_pago;
    public $grupo;
    public $impuesto;
    public $nuevocli_setup;
    public $pais;
    public $results;
    public $serie;
    public $tipo;
    
    public $giftcard;
    
    public $banco;
    public $estado;

    public function __construct()
    {
        parent::__construct(__CLASS__, 'Nueva operación...', 'Movimientos', FALSE, FALSE, TRUE);
    }

    protected function private_core()
    {
        parent::private_core();

        //$this->agencia = new agencia_transporte();
        $this->cliente = new cliente_m();
        $this->cliente_s = FALSE;
        $this->direccion = FALSE;
        $this->banco = new banco_m();
        $this->estado = new estado_m();
        
//        $this->familia = new familia();
//        $this->impuesto = new impuesto();
        $this->results = array();
//        $this->grupo = new grupo_clientes();
//        $this->pais = new pais();
        
        
//        $this->giftcard = new giftcard_m();

        /// cargamos la configuración
        $fsvar = new fs_var();
        $this->nuevocli_setup = $fsvar->array_get(
            array(
            'nuevocli_cifnif_req' => 0,
            'nuevocli_direccion' => 0,
            'nuevocli_direccion_req' => 0,
            'nuevocli_codpostal' => 0,
            'nuevocli_codpostal_req' => 0,
            'nuevocli_pais' => 0,
            'nuevocli_pais_req' => 0,
            'nuevocli_provincia' => 0,
            'nuevocli_provincia_req' => 0,
            'nuevocli_ciudad' => 0,
            'nuevocli_ciudad_req' => 0,
            'nuevocli_telefono1' => 0,
            'nuevocli_telefono1_req' => 0,
            'nuevocli_telefono2' => 0,
            'nuevocli_telefono2_req' => 0,
            'nuevocli_email' => 0,
            'nuevocli_email_req' => 0,
            'nuevocli_codgrupo' => '',
            ), FALSE
        );

        if (isset($_REQUEST['tipo'])) {
            $this->tipo = $_REQUEST['tipo'];
        } else {
            foreach ($this->tipos_a_guardar() as $t) {
                $this->tipo = $t['tipo'];
                break;
            }
        }

        if (isset($_REQUEST['buscar_cliente'])) {
            $this->fbase_buscar_cliente($_REQUEST['buscar_cliente']);
        } else if (isset($_REQUEST['datoscliente'])) {
            $this->datos_cliente();
        } else if (isset($_REQUEST['new_articulo'])) {
            $this->new_articulo();
        } else if ($this->query != '') {
            $this->new_search();
        } else if (isset($_POST['referencia4precios'])) {
            $this->get_precios_articulo();
        } else if (isset($_POST['referencia4combi'])) {
            $this->get_combinaciones_articulo();
        }else if (isset($_REQUEST['vnumerotarjeta'])) {
                $this->verificatarjeta($_REQUEST['vnumerotarjeta']);
                
        }  else if (isset($_POST['cliente'])) {
            $this->cliente_s = $this->cliente->get($_POST['cliente']);

            /**
             * Nuevo cliente
             */
            if (isset($_POST['nuevo_cliente']) && $_POST['nuevo_cliente'] != '') {
                $this->cliente_s = FALSE;
                if ($_POST['nuevo_cifnif'] != '') {
                    $this->cliente_s = $this->cliente->get_by_cifnif($_POST['nuevo_cifnif']);
                    if ($this->cliente_s) {
                        $this->new_advice('Ya existe un cliente con ese ' . FS_CIFNIF . '. Se ha seleccionado.');
                    }
                }

                if (!$this->cliente_s) {
                    $this->cliente_s = new cliente_m();
                    //$this->cliente_s->codcliente = $this->cliente_s->get_new_codigo();
                    $this->cliente_s->nombre = $this->cliente_s->razonsocial = $_POST['nuevo_cliente'];
                    //$this->cliente_s->tipoidfiscal = $_POST['nuevo_tipoidfiscal'];
                    $this->cliente_s->ruc = $_POST['nuevo_cifnif'];
                    //$this->cliente_s->personafisica = isset($_POST['personafisica']);
                    $this->cliente_s->direccion = $_POST['nuevo_direccion'];
                    

                    if (isset($_POST['nuevo_telefono1'])) {
                        $this->cliente_s->telefono = $_POST['nuevo_telefono1'];
                    }

                  

                    if ($this->cliente_s->save()) {
                                                                   
                            $this->new_message('Cliente agregado correctamente.');
                        
                    } else {
                        $this->new_error_msg("¡Imposible guardar la dirección del cliente!");
                    }
                }
            }

//            if ($this->cliente_s) {
//                foreach ($this->cliente_s->get_direcciones() as $dir) {
//                    if ($dir->domfacturacion) {
//                        $this->direccion = $dir;
//                        break;
//                    }
//                }
//            }

            if (isset($_POST['codagente'])) {
                $agente = new agente();
                $this->agente = $agente->get($_POST['codagente']);
            } else {
                $this->agente = $this->user->get_agente();
            }

            $this->almacen = new almacen();
            $this->serie = new serie();
            $this->forma_pago = new forma_pago();
            $this->divisa = new divisa();

            if (isset($_POST['tipo'])) {
                //GRABA LA OPERACIÓN
                if ($_POST['tipo'] == 'albaran') {
                    //$this->new_message("entrooo");
                    $this->nuevo_albaran_cliente();
                } 

                /// si el cliente no tiene cifnif nos guardamos el que indique
                /*if ($this->cliente_s->cifnif == '') {
                    $this->cliente_s->cifnif = $_POST['cifnif'];
                    $this->cliente_s->save();
                }*/
                
            }
        }
    }
    
    
    ///VERFICA TARJETA GIOVANNI
    private function verificatarjeta($nrotarjeta)
    {
        /// cambiamos la plantilla HTML

         /// desactivamos la plantilla HTML
        $this->template = FALSE;

        header('Content-Type: application/json');
        echo json_encode($this->giftcard->get_by_tarjeta($nrotarjeta));
    }

    /**
     * Devuelve los tipos de documentos a guardar,
     * así para añadir tipos no hay que tocar la vista.
     * @return type
     */
    public function tipos_a_guardar()
    {
        $tipos = array();

        

        if ($this->user->have_access_to('ventas_albaran')) {
            $tipos[] = array('tipo' => 'albaran', 'nombre' => ucfirst(FS_ALBARAN) . ' de cliente');
        }

       
        return $tipos;
    }

    public function url()
    {
        return 'index.php?page=' . __CLASS__ . '&tipo=' . $this->tipo;
    }

    private function datos_cliente()
    {
        /// desactivamos la plantilla HTML
        $this->template = FALSE;

        header('Content-Type: application/json');
        echo json_encode($this->cliente->get($_REQUEST['datoscliente']));
    }
    //AGREGAR NUEVO CHEQUE
    private function new_articulo()
    {
        /// desactivamos la plantilla HTML
        $this->template = FALSE;

        $art0 = new cheque_m();
        if ($_REQUEST['nrocheque'] != '') {
            $art0->nrocheque = $_REQUEST['nrocheque'];
        } else {
            //$art0->referencia = $art0->get_new_referencia();
        }
        if ($art0->exists()) {
            $this->results[] = $art0->get($art0->nrocheque);
        } else {
            $art0->idbanco = $_REQUEST['vbanco'];
            $art0->nrocuenta = $_REQUEST['vnrocuenta'];
            $art0->fec_emision = $_REQUEST['fechaemision'];
            $art0->fec_pago = $_REQUEST['vfechapago'];

            $art0->importe = $_REQUEST['vimporte'];
            $art0->aordende = $_REQUEST['vaordende'];
            $art0->idcliente = $_REQUEST['vcliente'];
            $art0->tipo = $_REQUEST['vtipo'];
            
           // $art0->banconombre = 'BANCO';
           // $art0->clientenombre = 'BELINDA';

            

            if ($art0->save()) {
                //$this->results[] = $art0;
                //AGREGADO PARA MOSTRAR NOMBRE DE BANCO Y NOMBRE DE CLIENTE
               $this->results[] = $art0->get($art0->nrocheque);
            }
        }

        header('Content-Type: application/json');
        echo json_encode($this->results);
    }

    private function new_search()
    {
        /// desactivamos la plantilla HTML
        $this->template = FALSE;

        $articulo = new cheque_m();
        $codbanco = '';
        if (isset($_REQUEST['codbanco'])) {
            $codbanco = $_REQUEST['codbanco'];
        }
        $codtipo = '';
        if (isset($_REQUEST['codtipo'])) {
            $codtipo = $_REQUEST['codtipo'];
        }
        $con_stock = isset($_REQUEST['con_stock']);
        $this->results = $articulo->search($this->query, 0, $codbanco,  $codtipo);

        /// añadimos la busqueda, el descuento, la cantidad, etc...
       // $stock = new stock();
        /*foreach ($this->results as $i => $value) {
            $this->results[$i]->query = $this->query;
            $this->results[$i]->dtopor = 0;
            $this->results[$i]->cantidad = 1;
            $this->results[$i]->coddivisa = $this->empresa->coddivisa;

            /// añadimos el stock del almacén y el general
            $this->results[$i]->stockalm = $this->results[$i]->stockfis;
            if ($this->multi_almacen && isset($_REQUEST['codalmacen'])) {
                $this->results[$i]->stockalm = $stock->total_from_articulo($this->results[$i]->referencia, $_REQUEST['codalmacen']);
            }
        }*/

        /// ejecutamos las funciones de las extensiones
        foreach ($this->extensions as $ext) {
            if ($ext->type == 'function' && $ext->params == 'new_search') {
                $name = $ext->text;
                $name($this->db, $this->results);
            }
        }

        /// buscamos el grupo de clientes y la tarifa
     /*   if (isset($_REQUEST['codcliente'])) {
            $cliente = $this->cliente->get($_REQUEST['codcliente']);
           // $tarifa0 = new tarifa();

            if ($cliente && $cliente->codtarifa) {
                $tarifa = $tarifa0->get($cliente->codtarifa);
                if ($tarifa) {
                    $tarifa->set_precios($this->results);
                }
            } else if ($cliente && $cliente->codgrupo) {
                $grupo0 = new grupo_clientes();

                $grupo = $grupo0->get($cliente->codgrupo);
                if ($grupo) {
                    $tarifa = $tarifa0->get($grupo->codtarifa);
                    if ($tarifa) {
                        $tarifa->set_precios($this->results);
                    }
                }
            }
        }*/

        /// convertimos la divisa
       /* if (isset($_REQUEST['coddivisa']) && $_REQUEST['coddivisa'] != $this->empresa->coddivisa) {
            foreach ($this->results as $i => $value) {
                $this->results[$i]->coddivisa = $_REQUEST['coddivisa'];
                $this->results[$i]->pvp = $this->divisa_convert($value->pvp, $this->empresa->coddivisa, $_REQUEST['coddivisa']);
            }
        }*/

        header('Content-Type: application/json');
        echo json_encode($this->results);
    }

    private function get_precios_articulo()
    {
        /// cambiamos la plantilla HTML
        $this->template = 'ajax/nueva_venta_precios';

        $articulo = new articulo();
        $this->articulo = $articulo->get($_POST['referencia4precios']);
    }

   

  

    private function nuevo_albaran_cliente()
    {
        $continuar = TRUE;

        $cliente = $this->cliente->get($_POST['cliente']);
        if (!$cliente) {
            $this->new_error_msg('Cliente no encontrado.');
            $continuar = FALSE;
        }
                                      

       // $art0 = new cheque_m();
        $albaran = new operaciones_m();
       // $stock0 = new stock();

        if ($this->duplicated_petition($_POST['petition_id'])) {
            $this->new_error_msg('Petición duplicada. Has hecho doble clic sobre el botón guardar
               y se han enviado dos peticiones. Mira en <a href="' . $albaran->url() . '"> OPERACIONES </a>
               para ver si el Operaciones se ha guardado correctamente.');
            $continuar = FALSE;
        }

        if ($continuar) {
            $this->nuevo_documento($albaran);
            
            
            /// función auxiliar para implementar en los plugins que lo necesiten
            /*if (!fs_generar_numero2($albaran)) {
                $albaran->numero2 = $_POST['numero2'];
            }*/
             
            if ($albaran->save()) {
               // $trazabilidad = FALSE;
              
                $n = floatval($_POST['numlineas']);
                for ($i = 0; $i <= $n; $i++) {
                    if (isset($_POST['nrocheque_' . $i])) {
                        $linea = new operacion_linea_m();
                        $linea->idoperaciones = $albaran->idoperaciones;
                       // $this->nueva_linea($linea, $i, $serie, $cliente);
                       
                        $linea->cheques_nrocheque = $_POST['nrocheque_' . $i];
                        $linea->cheques_codbanco = $_POST['idbanco_' . $i];

                        if ($linea->save()) {
                            
                        } else {
                            $this->new_error_msg("¡Imposible guardar la linea con Nro. Cheque: " . $linea->cheques_nrocheque);
                            $continuar = FALSE;
                        }
                    }
                }

                if ($continuar) {                                            
                        if ($albaran->save()) {
                        /// Función de ejecución de tareas post guardado correcto del albaran
                        //fs_documento_post_save($albaran);

                        $this->new_message("<a href='" . $albaran->url() . "'>Operacion</a> guardado correctamente.");
                        $this->new_change(ucfirst(FS_ALBARAN) . ' Cliente ' . $albaran->idcliente, $albaran->url(), TRUE);

                        if ($_POST['redir'] == 'TRUE') {
                            header('Location: ' . $albaran->url());
                        }
                    } else {
                        $this->new_error_msg("¡Imposible actualizar el <a href='" . $albaran->url() . "'>Operacion</a>!");
                    }
                } else {
                    
                    if (!$albaran->delete()) {
                        $this->new_error_msg("¡Imposible eliminar el <a href='" . $albaran->url() . "'>Operacion</a>!");
                    }
                }
            } else {
                $this->new_error_msg("¡Imposible guardar la Operacion!!!");
            }
        }
    }

   

    
    //ACTUALIZA SALDO DE GIFT CARD
    private function actualiza_saldo_tarjeta($nrogif,$montocargado)
    {        
        if ($this->giftcard->actualiza_saldo_tarjeta($nrogif,$montocargado)) {
                //$this->new_message('Conyuge con CI Nro. '. $ci .' ha sido de Baja correctamente.');
        } else {
                //$this->new_error_msg('Imposible dar de Baja el conyuge.');
        }
         
    }
    
    
    private function nuevo_documento(&$documento)
    {
        $documento->fec_operacion = $_POST['fecha'];
        //$documento->hora = $_POST['hora'];
        $documento->idestado = $_POST['vestado'];
        $documento->idcliente = $_POST['cliente'];                     
        //$documento->codagente = $this->agente->codagente;
        $documento->observacion = $_POST['observaciones'];
       

       /* $albaran->fec_operacion = $_POST['fecha'];
            //$documento->hora = $_POST['hora'];
            $albaran->idestado = $_POST['vestado'];
            $albaran->idcliente = $_POST['cliente'];                     
            //$documento->codagente = $this->agente->codagente;
            $albaran->observacion = $_POST['observaciones'];*/
       
    }

   /* private function nueva_linea(&$linea, $i, $serie, $cliente)
    {
        $linea->descripcion = $_POST['desc_' . $i];

        if (!$serie->siniva && $cliente->regimeniva != 'Exento') {
            $imp0 = $this->impuesto->get_by_iva($_POST['iva_' . $i]);
            if ($imp0) {
                $linea->codimpuesto = $imp0->codimpuesto;
                $linea->iva = floatval($_POST['iva_' . $i]);
                $linea->recargo = floatval(fs_filter_input_post('recargo_' . $i, 0));
            } else {
                $linea->iva = floatval($_POST['iva_' . $i]);
                $linea->recargo = floatval(fs_filter_input_post('recargo_' . $i, 0));
            }
        }

        $linea->irpf = floatval(fs_filter_input_post('irpf_' . $i, 0));
        $linea->pvpunitario = floatval($_POST['pvp_' . $i]);
        $linea->cantidad = floatval($_POST['cantidad_' . $i]);
        $linea->dtopor = floatval(fs_filter_input_post('dto_' . $i, 0));
        $linea->dtopor2 = floatval(fs_filter_input_post('dto2_' . $i, 0));
        $linea->dtopor3 = floatval(fs_filter_input_post('dto3_' . $i, 0));
        $linea->dtopor4 = floatval(fs_filter_input_post('dto4_' . $i, 0));
        $linea->pvpsindto = $linea->pvpunitario * $linea->cantidad;

        // Descuento Unificado Equivalente
        $due_linea = $this->fbase_calc_due(array($linea->dtopor, $linea->dtopor2, $linea->dtopor3, $linea->dtopor4));
        $linea->pvptotal = $linea->cantidad * $linea->pvpunitario * $due_linea;
    }*/
}
