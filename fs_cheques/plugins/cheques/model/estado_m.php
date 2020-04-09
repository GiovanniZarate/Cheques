<?php

/**
 * Description of estado_m
 *
 * @author GIOVANNI ZARATE : Fecha: 31/05/2019
 */
class estado_m extends fs_model{
    public $idestado;
    public $nombre;
    
    
    public function __construct($c=FALSE) {
        parent::__construct('estado');
        if($c){
            $this->idestado = $c['idestado'];
            $this->nombre = $c['nombre'];            
          }else{
            $this->idestado=0;
            $this->nombre='';            
          }          
    }

    protected function install() {
        $this->clean_cache();
     }
     
    public function delete() {
        $this->clean_cache();
        return $this->db->exec("DELETE FROM ".$this->table_name." WHERE idestado = ".$this->var2str($this->idestado).";");
    }

    public function exists() {
        if( is_null($this->idestado) )
        {
           return FALSE;
        }
        else
           return $this->db->select("SELECT * FROM ".$this->table_name." WHERE idestado = ".$this->var2str($this->idestado).";");
    }

    public function save() {
        if( $this->test() ){      
            $this->clean_cache();
               if( $this->exists() )
               {
                  $sql = "UPDATE ".$this->table_name." SET nombre = ".$this->var2str($this->nombre). ""                                             
                          . "WHERE idestado = ".$this->var2str($this->idestado).";";
               }
               else
               {
                  $sql = "INSERT INTO ".$this->table_name." (nombre) VALUES
                           (".$this->var2str($this->nombre).");";
               }

               return $this->db->exec($sql);
           }  else {
              return FALSE;
           }
    }
    
    
    /**
    * Limpiamos la caché
    */
   private function clean_cache()
   {
      $this->cache->delete('estado_all');
   }
   
   /**
    * Comprueba los datos del pais, devuelve TRUE si son correctos
    * @return boolean
    */
   public function test(){
      $status = FALSE;
      
      //$this->iddistrito = trim($this->iddistrito);
      $this->nombre = $this->no_html($this->nombre);      
      
      if( strlen($this->nombre) < 1 OR strlen($this->nombre) > 60 )
      {
         $this->new_error_msg("Nombre no válido.");
      }      
      else
         $status = TRUE;
      
      return $status;
   }
  
   /**
    * Devuelve la url donde se pueden ver/modificar estos datos
    * @return string
    */
   public function url()
   {
      if( is_null($this->idestado) )
      {
         return "index.php?page=estado";
      }
      else
         return "index.php?page=estado_edit&cod=".$this->idestado;
   }
   
   /**
    * Devuelve un array con profesiones.
    * @return \
    */
   public function all()
   {
      /// leemos esta lista de la caché
      $lista = $this->cache->get_array('estado_all');
      if(!$lista)
      {
         /// si no está en caché, leemos de la base de datos
         $data = $this->db->select("SELECT idestado,nombre FROM ".$this->table_name."  ORDER BY idestado ASC;");
         
         if($data)
         {
            foreach($data as $a)
            {
               $lista[] = new estado_m($a);
            }
         }
         
         /// guardamos la lista en caché
         $this->cache->set('estado_all', $lista);
      }
      
      return $lista;
   }
   
   /**
    * Devuelve el empleado/agente con codagente = $cod
    * @param type $cod
    * @return \agente|boolean
    */
   public function get($cod)
   {
      $a = $this->db->select("SELECT idestado,trim(nombre) nombre "
              . "FROM ".$this->table_name." WHERE idestado = ".$this->var2str($cod).";");
      if($a)
      {
         return new estado_m($a[0]);
      }
      else
         return FALSE;
   }
   
   /**
    * Genera un nuevo codigo de empleado
    * @return int
    */
   public function get_new_codigo()
   {
      $sql = "SELECT MAX(".$this->db->sql_to_int('idestado').") as cod FROM ".$this->table_name.";";
      $cod = $this->db->select($sql);
      if($cod)
      {
         return 1 + intval($cod[0]['cod']);
      }
      else
         return 1;
   }
 
    //PARA EL COMBO POR DEFECTO
   public function is_default(){
      return FALSE;
   }

}
