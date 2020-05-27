<?php
/**
 * Description of sys_dpto
 *
 * @author GIOVANNI ZARATE : Fecha:31/05/2019
 */
require_model('cliente_m.php');

class cliente extends fs_controller{
    
     public $instanciamodel;
     public $total_resultados;
     public $resultados;
    
    public function __construct() {
        parent::__construct(__CLASS__, 'Clientes', 'Definiciones');
    }
    
    protected function private_core() {
        //INSTANCIA EL MODELO 
         $this->instanciamodel = new cliente_m();
         
        //PREGUNTA SI SE ENVIO UN CODIGO POR EL METODO POST
      if( isset($_POST['vnombre'])){     
        //CREA UNA NUEVA INSTANCIA DEL MODELO 
        $instancianuevamodel = new cliente_m();
               
        //$instancianuevamodel->idcliente = $_POST['vcodigo'];
        
        $instancianuevamodel->idcliente = $instancianuevamodel->get_new_codigo();
        $instancianuevamodel->nombre = $_POST['vnombre'];
        $instancianuevamodel->direccion = $_POST['vdireccion'];
        $instancianuevamodel->ruc = $_POST['vruc'];
        $instancianuevamodel->telefono = $_POST['vtelefono'];
        
        
        ///GUARDA LOS DATOS SI NO DA ERROR
         if( $instancianuevamodel->save() )
         {
            $this->new_message("Cliente ".$instancianuevamodel->nombre." guardado correctamente.");
         }
         else
            $this->new_error_msg("Imposible guardar!");
         
       }else if( isset($_GET['delete']) ){
            
            $instancianuevamodel = $this->instanciamodel->get($_GET['delete']);
            if( $instancianuevamodel )
            {
               if( $instancianuevamodel->delete() )
               {
                  $this->new_message("Cliente ".$instancianuevamodel->nombre." eliminado correctamente.");
               }
               else
                  $this->new_error_msg("Imposible eliminar!");
            }
            else
               $this->new_error_msg("Cliente no encontrado!");
       }
       
       //PARA HACER LA PAGINACION
      $this->offset = 0;
      if( isset($_GET['offset']) )
      {
         $this->offset = intval($_GET['offset']);
      }
      //PARA ORDENAR DE ACUERDO A LOS PARAMETROS
      $this->orden = 'nombre ASC';
      if( isset($_REQUEST['orden']) )
      {
         $this->orden = $_REQUEST['orden'];
      }
      
      $this->buscar();
    }
    
    //METODO PARA BUSCAR DATOS
  private function buscar()
   {
      $this->total_resultados = 0;
      $query = mb_strtolower( $this->empresa->no_html($this->query), 'UTF8' );
      $sql = " FROM clientes ";
      $and = ' WHERE ';
     //is_numeric = Comprueba si una variable es un número o un string numérico
      if( is_numeric($query) )
      {
         $sql .= $and."(idcliente LIKE '%".$query."%'"
                 . " OR nombre LIKE '%".$query."%')";
         $and = ' AND ';
      }
      else
      {
         $buscar = str_replace(' ', '%', $query);
         $sql .= $and."(lower(nombre) LIKE '%".$buscar."%')";
         $and = ' AND ';
      }
        
      $data = $this->db->select("SELECT COUNT(idcliente) as total".$sql.';');
      if($data)
      {
         $this->total_resultados = intval($data[0]['total']);
         
         $data2 = $this->db->select_limit("SELECT *".$sql." ORDER BY ".$this->orden, FS_ITEM_LIMIT, $this->offset);
         if($data2)
         {
            foreach($data2 as $d)
            {
               $this->resultados[] = new cliente_m($d);
            }
         }
      }
   }
   
   //PARA HACER LA PAGINACION
   public function paginas(){
      $url = $this->url()."&query=".$this->query
                 //."&idpais=".$this->pais     
                 ."&orden=".$this->orden;
      
      $paginas = array();
      $i = 0;
      $num = 0;
      $actual = 1;
      
      /// añadimos todas la página
      while($num < $this->total_resultados)
      {
         $paginas[$i] = array(
             'url' => $url."&offset=".($i*FS_ITEM_LIMIT),
             'num' => $i + 1,
             'actual' => ($num == $this->offset)
         );
         
         if($num == $this->offset)
         {
            $actual = $i;
         }
         
         $i++;
         $num += FS_ITEM_LIMIT;
      }
      
      /// ahora descartamos
      foreach($paginas as $j => $value)
      {
         $enmedio = intval($i/2);
         
         /**
          * descartamos todo excepto la primera, la última, la de enmedio,
          * la actual, las 5 anteriores y las 5 siguientes
          */
         if( ($j>1 AND $j<$actual-5 AND $j!=$enmedio) OR ($j>$actual+5 AND $j<$i-1 AND $j!=$enmedio) )
         {
            unset($paginas[$j]);
         }
      }
      
      if( count($paginas) > 1 )
      {
         return $paginas;
      }
      else
      {
         return array();
      }
   }
    
}
