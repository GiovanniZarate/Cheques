<?php


/**
 * Description of sys_distrito_edit
 *
 * @author GIOVANNI ZARATE 28/03/2018
 */
require_model('estado_m.php');

class estado_edit extends fs_controller{
    public $estado;
    public $allow_delete;
    //PARA EL COMBO
    public $departamento;
    
    public function __construct() {
        parent::__construct(__CLASS__, 'Editar Estado', '');
    }
    
    protected function private_core() {
        $this->ppage = $this->page->get('estado');
        
        /// ¿El usuario tiene permiso para eliminar en esta página?
        $this->allow_delete = $this->user->allow_delete_on(__CLASS__);

        $this->estado = FALSE;
    
        
        //SE SE PASA POR EL METODO _GET UN VALOR cod 
        if ( isset($_GET['cod'])){ 
          //una instancia nueva de empleado_m
          $distrito = new estado_m();
          //CARGA LA VARIABLE PUBLICA empleado con los datos del empleado con el codigo enviado
          $this->estado = $distrito->get(trim($_GET['cod']));
        }
        
        //SI CARGA LOS DATOS 
          if ($this->estado){
               $this->page->title .= ' ' . $this->estado->idestado;
               //SI VIENE UNA VARIABLE DEFINIDA DE CARGA
               if ( isset($_POST['vnombre'])){
                    // $this->profesion->pn_codigo = $_POST['pn_codigo'];
                     $this->estado->nombre = $_POST['vnombre'];                     
                     //SI GUARDA LOS DATOS MUESTRA MENSAJE SATISFACTORIO
                     if( $this->estado->save() )
                     {
                        $this->new_message("Datos de Estado guardados correctamente.");
                     }
                     else
                        $this->new_error_msg("¡Imposible guardar los datos!");
               }
            }else{
               $this->new_error_msg("Estado no encontrado.", 'error', FALSE, FALSE);
            }
        
    }
    
    public function url()
   {
      if( !isset($this->estado) )
      {
         return parent::url();
      }
      else if($this->estado)
      {
         return $this->estado->url();
      }
      else
         return $this->page->url();
   }
    
    
}
