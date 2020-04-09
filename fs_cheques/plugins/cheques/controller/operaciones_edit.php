<?php


require_once 'plugins/cheques/extras/fbase_controller.php';

class operaciones_edit extends fbase_controller
{

  
    public $agente;
    public $albaran;
    public $allow_delete_fac;
    
    public $cliente;
    public $cliente_s;
   
    
    public $nuevo_albaran_url;
    
    public $estado;
    public $banco;
   
    public $albarandeta;
    
    public $respaldo;

    public function __construct()
    {
        parent::__construct(__CLASS__, 'Operaciones Edit', 'Movimientos', FALSE, FALSE);
    }

    protected function private_core()
    {
        parent::private_core();

        $this->ppage = $this->page->get('operaciones');
        $this->agente = FALSE;

        
        $albaran = new operaciones_m();
        $albarandeta = new operacion_linea_m();
        $respaldo = new respaldo_m();
        $this->albaran = FALSE;
        $this->albarandeta = FALSE;
        $this->respaldo = FALSE;
        
        $this->cliente = new cliente_m();
        $this->cliente_s = FALSE;
        
        $this->nuevo_albaran_url = FALSE;
        
        $this->estado = new estado_m();
        $this->banco = new banco_m();

        /// ¿El usuario tiene permiso para eliminar la factura?
        $this->allow_delete_fac = $this->user->allow_delete_on('operaciones_edit');

        /**
         * Comprobamos si el usuario tiene acceso a nueva_venta,
         * necesario para poder añadir líneas.
         */
        if ($this->user->have_access_to('nueva_operacion', FALSE)) {
            $nuevoalbp = $this->page->get('nueva_operacion');
            if ($nuevoalbp) {
                $this->nuevo_albaran_url = $nuevoalbp->url();
            }
        }

        if (isset($_POST['idalbaran'])) {
            $this->albaran = $albaran->get($_POST['idalbaran']);
            $this->modificar();
        } else if (isset($_GET['id'])) {
            $this->albaran = $albaran->get($_GET['id']);
            //$this->albarandeta = $albarandeta->get($_GET['id']);
        }

        if ($this->albaran) {
            $this->page->title = $this->albaran->idoperaciones;

            /// cargamos el agente
//            if (!is_null($this->albaran->codagente)) {
//                $agente = new agente();
//                $this->agente = $agente->get($this->albaran->codagente);
//            }

            /// cargamos el cliente
            $this->cliente_s = $this->cliente->get($this->albaran->idcliente);

            /// comprobamos el albarán
           // $this->albaran->full_test();

            if (isset($_REQUEST['facturar']) && isset($_REQUEST['petid'])) {
                if ($this->duplicated_petition($_REQUEST['petid'])) {
                    $this->new_error_msg('Petición duplicada. Evita hacer doble clic sobre los botones.');
                } 
                /*else if (!$this->albaran->ptefactura || !is_null($this->albaran->idfactura)) {
                    $this->new_error_msg('Parece que este ' . FS_ALBARAN . ' ya está facturado.');
                } */
                else {
                    $this->generar_factura();
                }
            }
            //PARA VALIDAR EL PEDIDO (BOUCHER)
            if (isset($_REQUEST['petidvalido'])) {
                //$this->new_error_msg('Validar poner estado en 2');
                 $this->actualiza_pedido_baucher($this->albaran->idalbaran);
            }else if (isset($_POST['codrespaldo'])) { /// aÃ±adir/modificar RESPALDO DE CHEQUE
                $this->edit_respaldo();
            }else if (isset($_GET['eliminarrespaldo'])) { /// eliminar RESPALDO DE CHEQUE
                 $this->elimina_respaldo($_GET['eliminarrespaldo']);
             }else if (isset($_GET['vnrocheque'])) { /// PARA TRAER RESPALDO DE CHEQUE
                  $this->respaldo = $respaldo->all_from_respaldo($_GET['id'], $_GET['vnrocheque']) ;//$albarandeta->get($_GET['id']);
                  $this->albarandeta = $albarandeta->all_from_operaciondetaxcheque($_GET['vnrocheque']);
                 //$this->new_advice("traer respaldo de cheque");
             }
            
        } else {
            $this->new_error_msg("¡Operacion no encontrado!", 'error', FALSE, FALSE);
        }
    }

     private function edit_respaldo()
    {        
        $respaldo = new respaldo_m();
        //$this->contingencia = NULL;
        if ($_POST['codrespaldo'] != '') {
           // $this->conyuge = $estudio->get($_POST['codestudio']); //$dir->get($_POST['codconyuge']);
        }
        if( isset($_POST['vnrocheque'])){                              
            $respaldo->idoperacion = $this->albaran->idoperaciones;
            $respaldo->nrocheque = $_POST['vnrocheque'];
            $respaldo->idbanco = $_POST['vidbanco'];
            $respaldo->importe = $_POST['vimporte'];
            
           
            if ($respaldo->save()) {
                //PARA RECARGAR LOS DATOS DE RESPALDO NUEVO
                $this->respaldo = $respaldo->all_from_respaldo($_GET['id'], $respaldo->nrocheque) ;
                $this->new_message("Respaldo  guardado correctamente.");
            } else {
                $this->new_message("¡Imposible guardar Respaldo!");
            }
       }  else {
           $this->new_message("NO SE ENCONTRO LA VARIABLE Respaldo");
       }
         //$this->new_message("AGREGAR RESPALDO.");
         
    }
    
     private function elimina_respaldo($cod){
        
         if ($this->albaran->elimina_respaldo($cod)) {
                $this->new_message('Respaldo Nro. '. $cod .' ha sido de Eliminado correctamente.');
            } else {
                    $this->new_error_msg('Imposible Eliminaar el Contingencia.');
            }
          //$this->new_error_msg('ELIMINA RESPALDO.');
     }
    //ACTUALIZA ESTADO ptefatura=2
   /* private function actualiza_pedido_baucher($nroalbaran)
    {        
        if ($this->albaran->actualiza_pedido_baucher($nroalbaran)) {
                //$this->new_message('Conyuge con CI Nro. '. $ci .' ha sido de Baja correctamente.');
        } else {
                //$this->new_error_msg('Imposible dar de Baja el conyuge.');
        }
         
    }*/
    public function url()
    {
        if (!isset($this->albaran)) {
            return parent::url();
        } else if ($this->albaran) {
            return $this->albaran->url();
        }

        return $this->page->url();
    }

    private function modificar()
    {
        $this->albaran->observacion = $_POST['observaciones'];

        /// ¿Es editable o ya ha sido facturado?
        if ($this->albaran->ptefactura) {
            /*$eje0 = $this->ejercicio->get_by_fecha($_POST['fecha'], FALSE);
            if ($eje0) {
                $this->albaran->fecha = $_POST['fecha'];
                $this->albaran->hora = $_POST['hora'];

                if ($this->albaran->codejercicio != $eje0->codejercicio) {
                    $this->albaran->codejercicio = $eje0->codejercicio;
                    $this->albaran->new_codigo();
                }
            } else {
                $this->new_error_msg('Ningún ejercicio encontrado.');
            }*/
            
             $this->albaran->fec_operacion = $_POST['fecha'];

             $this->albaran->idestado =  $_POST['codestado'];
             //SI ES DIFERENTE A ESTADO: PENDIENTO - CODIGO 1 ACTUALIZA CAMPO ptefactura de tabla operaciones para no editar mas
            if($this->albaran->idestado!=1){
                $this->albaran->ptefactura = 0; 
            }
            /// ¿cambiamos el cliente?
       
             if ($_POST['cliente'] != $this->albaran->idcliente) {
                $cliente = $this->cliente->get($_POST['cliente']);
                if ($cliente) {
                    $this->albaran->idcliente = $cliente->idcliente;                    
                    $this->albaran->nombrecliente = $cliente->nombre;  
                     $this->albaran->ruccliente = $cliente->ruc;  
                } else {
                    $this->albaran->idcliente = NULL;
                    //$this->albaran->nombrecliente = $_POST['nombrecliente'];
                    //$this->albaran->cifnif = $_POST['cifnif'];
                    
                }
            }
            //$serie = $this->serie->get($this->albaran->codserie);

            /// ¿cambiamos la serie?
            
           // $this->albaran->codpago = $_POST['forma_pago'];

            /// ¿Cambiamos la divisa?
         

            if (isset($_POST['numlineas'])) {
                $numlineas = intval($_POST['numlineas']);               

                $lineas = $this->albaran->get_lineas();
               // $articulo = new articulo();

                /// eliminamos las líneas que no encontremos en el $_POST
                foreach ($lineas as $l) {
                    $encontrada = FALSE;
                    for ($num = 0; $num <= $numlineas; $num++) {
                        if (isset($_POST['idlinea_' . $num]) && $l->idcheque_operacion == intval($_POST['idlinea_' . $num])) {
                            $encontrada = TRUE;
                            break;
                        }
                    }
                    if (!$encontrada) {
                        if ($l->delete()) {
                            /*/// actualizamos el stock
                            $art0 = $articulo->get($l->referencia);
                            if ($art0) {
                                $art0->sum_stock($this->albaran->codalmacen, $l->cantidad, FALSE, $l->codcombinacion);
                            }*/
                        } else {
                            $this->new_error_msg("¡Imposible eliminar la línea de la operacion " . $l->cheque_nrocheque . "!");
                        }
                    }
                }               

                /// modificamos y/o añadimos las demás líneas
                for ($num = 0; $num <= $numlineas; $num++) {
                    $encontrada = FALSE;
                    if (isset($_POST['idlinea_' . $num])) {
                        foreach ($lineas as $k => $value) {
                            /// modificamos la línea
                            if ($value->idcheque_operacion == intval($_POST['idlinea_' . $num])) {
                                $encontrada = TRUE;
                                //$cantidad_old = $value->cantidad;
                                $lineas[$k]->cheques_nrocheque = $_POST['nrocheque_' . $num];
                                $lineas[$k]->cheques_codbanco = $_POST['idbanco_' . $num];
                                

                               

                                if ($lineas[$k]->save()) {                                    
                                } else {
                                    $this->new_error_msg("¡Imposible modificar la línea de la operacion " . $value->cheques_nrocheque . "!");
                                }

                                break;
                            }
                        }

                        /// añadimos la línea
                        if (!$encontrada && intval($_POST['idlinea_' . $num]) == -1 && isset($_POST['nrocheque_' . $num])) {
                            $linea = new operacion_linea_m();
                            $linea->idoperaciones = $this->albaran->idoperaciones;
                            $linea->cheques_nrocheque = $_POST['nrocheque_' . $num];

                           

                            $linea->cheques_codbanco = $_POST['idbanco_' . $num];
                            
                            
                           

                            if ($linea->save()) {
                                /*if ($art0) {
                                    /// actualizamos el stock
                                    $art0->sum_stock($this->albaran->codalmacen, 0 - $linea->cantidad, FALSE, $linea->codcombinacion);
                                }

                                if ($linea->irpf > $this->albaran->irpf) {
                                    $this->albaran->irpf = $linea->irpf;
                                }*/
                            } else {
                                $this->new_error_msg("¡Imposible guardar la línea de la operacion " . $linea->cheques_nrocheque . "!");
                            }
                        }
                    }
                }                                              
            }
        }

        /// función auxiliar para implementar en los plugins que lo necesiten
//        if (!fs_generar_numero2($this->albaran)) {
//            $this->albaran->numero2 = $_POST['numero2'];
//        }

        if ($this->albaran->save()) {
            /// Función de ejecución de tareas post guardado correcto del albarán
            //fs_documento_post_save($this->albaran);

            $this->new_message("Operacion modificado correctamente.");
            //$this->propagar_cifnif();
            $this->new_change('Operacion Cliente ' . $this->albaran->idoperaciones, $this->albaran->url());
        } else {
            $this->new_error_msg("¡Imposible modificar la Operacion!");
        }
    }

    /**
     * Actualizamos el cif/nif en el cliente y los albaranes de este cliente que no tenga cif/nif
     */
   /* private function propagar_cifnif()
    {
        if ($this->albaran->cifnif) {
            /// buscamos el cliente
            $cliente = $this->cliente->get($this->albaran->codcliente);
            if ($cliente && !$cliente->cifnif) {
                /// actualizamos el cliente
                $cliente->cifnif = $this->albaran->cifnif;
                if ($cliente->save()) {
                    /// actualizamos albaranes
                    $sql = "UPDATE albaranescli SET cifnif = " . $cliente->var2str($this->albaran->cifnif)
                        . " WHERE codcliente = " . $cliente->var2str($this->albaran->codcliente)
                        . " AND cifnif = '' AND fecha >= " . $cliente->var2str(date('01-01-Y')) . ";";
                    $this->db->exec($sql);

                    /// actualizamos facturas
                    $sql = "UPDATE facturascli SET cifnif = " . $cliente->var2str($this->albaran->cifnif)
                        . " WHERE codcliente = " . $cliente->var2str($this->albaran->codcliente)
                        . " AND cifnif = '' AND fecha >= " . $cliente->var2str(date('01-01-Y')) . ";";
                    $this->db->exec($sql);
                }
            }
        }
    }*/

    private function generar_factura()
    {
        $this->fbase_facturar_albaran_cliente([$this->albaran], $_REQUEST['facturar'], $_REQUEST['codpago']);
    }
    
    
    
}
