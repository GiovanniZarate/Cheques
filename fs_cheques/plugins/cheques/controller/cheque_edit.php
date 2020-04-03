<?php


/**
 * Description of 
 *
 * @author GIOVANNI ZARATE
 * FECHA : 03/06/2019
 */


//require_model('chequerecarga_m.php');

class cheque_edit extends fs_controller{
    public $cheque;
   
   
    public $chequerecarga;
   
     public $banco;
     public $cliente;
     
    public function __construct() {
        parent::__construct(__CLASS__, 'Editar Cheque', 'Definiciones', FALSE, FALSE, FALSE);
    }
    
    protected function private_core() {
        $this->ppage = $this->page->get('cheque');
        $this->cheque = new cheque_m();               
        
        $this->allow_delete = $this->user->allow_delete_on(__CLASS__);

        $this->cheque = FALSE;
        
        $this->banco = new banco_m();
        $this->cliente = new cliente_m();
       // $this->chequerecarga = new chequerecarga_m();
        
 
         //SE SE PASA POR EL METODO _GET UN VALOR cod 
        if (isset($_GET['cod'])) {
            //una instancia nueva de empleado_m
            $cheque = new cheque_m();
            //CARGA LA VARIABLE PUBLICA empleado con los datos del empleado con el codigo enviado
            $this->cheque = $cheque->get($_GET['cod']);
        }
        
         //SI CARGA LOS DATOS 
        if ($this->cheque) {
            $this->page->title .= ' ' . $this->cheque->nrocheque;                                        
            if (isset($_POST['vmodifica1'])) {
                $this->modificar();
            }
            else if(isset($_POST['codnrorecarga'])) { /// añadir/modificar ESTACIONAMIENTO
                $this->edit_recarga();
             }else if (isset($_GET['eliminarcontingencia'])) { /// eliminar estacionamiento
                 $this->elimina_contingencia($_GET['eliminarcontingencia']);
             }
                                                        
           
        }else {
            $this->new_error_msg("Cheque no encontrado.", 'error', FALSE, FALSE);
        }
        
    }
    private function modificar()
    {
         if (isset($_POST['vnumero'])) {
                $this->cheque->nrocheque = $_POST['vnumero'];
                $this->cheque->idbanco = $_POST['vbanco'];
                $this->cheque->fec_emision = $_POST['vfechaemision'];
                $this->cheque->fec_pago = $_POST['vfechapago'];
                $this->cheque->nrocuenta = $_POST['vnrocuenta'];
                $this->cheque->importe = $_POST['vimporte'];
                $this->cheque->aordende = $_POST['vaordende'];
                $this->cheque->idcliente = $_POST['vcliente'];
                $this->cheque->tipo = $_POST['vtipo'];                                              
                //GRABAR LOS OTROS DATOS
                //$this->cheque->introduccion = $_POST['vintroduccion'];                

                //SI GUARDA LOS DATOS MUESTRA MENSAJE SATISFACTORIO
                if ($this->cheque->save()) {
                    $this->new_message("Datos de Cheque Modificado correctamente.");
                } else
                    $this->new_error_msg("¡Imposible guardar los datos!");
        }
    }
    
  
    
    
   /* 
    private function edit_recarga()
    {        
        $estudio = new chequerecarga_m();
        $this->chequerecarga = NULL;
        if ($_POST['codnrorecarga'] != '') {
           // $this->conyuge = $estudio->get($_POST['codestudio']); //$dir->get($_POST['codconyuge']);
        }
        if( isset($_POST['vnumero1'])){                              
            $estudio->nrocheque = $this->cheque->nrocheque;
            //$estudio->nrorecarga = $reser->get_new_codigo( $_POST['codturno']);
            $estudio->nrorecarga = $estudio->get_new_codigo( $_POST['codcheque']);
            $estudio->numero = $_POST['vnumero1'];
            $estudio->fechacarga = $_POST['vfechacarga'].' '. $_POST['vhoracarga'];
            $estudio->nrofactura = $_POST['vnrofactura1'];
            $estudio->monto = $_POST['vmonto1'];
            
           
            if ($estudio->save()) {
                 $this->actualiza_ultima_recarga($this->cheque->nrocheque,$_POST['vmonto1']);
                $this->new_message("Recarga guardado correctamente.");
            } else {
                $this->new_message("¡Imposible guardar Recarga!");
            }
       }  else {
           $this->new_message("NO SE ENCONTRO LA VARIABLE Recarga");
       }
    }
    */
    /*private function actualiza_ultima_recarga($nrogif,$montocargado)
    {        
        if ($this->cheque->actualiza_ultima_recarga($nrogif,$montocargado)) {
                //$this->new_message('Conyuge con CI Nro. '. $ci .' ha sido de Baja correctamente.');
        } else {
                //$this->new_error_msg('Imposible dar de Baja el conyuge.');
        }
         
    }
   */
    

/*
      private function elimina_contingencia($cod){
        
         if ($this->cheque->elimina_contingencia($cod)) {
                $this->new_message('Contingencia Nro. '. $cod .' ha sido de Eliminado correctamente.');
            } else {
                    $this->new_error_msg('Imposible Eliminaar el Contingencia.');
            }
     }
     
  */   
     
     
     
     
            
     
    public function url() {
        if (!isset($this->cheque)) {
            return parent::url();
        } else if ($this->cheque) {
            return $this->cheque->url();
        } else
            return $this->page->url();
    }

 
 
}
