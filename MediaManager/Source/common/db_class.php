<?
/***
 *  Media Manager
 *  Bootstrap based web interface for managing CouchPotato, SickBeard and more.
 *
 *  @author Webbe <Webbe@WebbeDesign.net>
 *  @copyright Copyright (c) 2014, Webbe Design
 *  @category Configuration
 *  @version 0.01
 ***/
require_once("../MM-Config.php");
mysqli_report(MYSQLI_REPORT_STRICT);
class db{
    global $MySQL_Host, $MySQL_Database, $MySQL_User, $MySQL_Password;
    private $method = "mysqli";
    private $host = $MySQL_Host;
    private $db = $MySQL_Database;
    private $user = $MySQL_User;
    private $password = $MySQL_Password;
    private $handler = null;
    public $connected = false;
    
    public function db_Connect(){
        if(is_null($this->handler)){
            if($this->method = "mysqli"){
                try{
                    $this->handler = new mysqli($this->host, $this->user, $this->password, $this->db);
                    $this->connected = true;
                } catch (Exception $e ){
                    $this->connected = false;
                    /**
                     * @todo Handle errors.
                     */
                }
            }else{//use legacy mysql connect}
                try{
                    Exception()
                    $this->handler = mysql_connect($this->host,$this->user,$this->password) or throw new Exception(mysql_error($this->handler), mysql_errno($this->handler));
                    mysql_selectdb($this->db,$this->handler) or throw new Expection(mysql_error($this->handler), mysql_errno($this->handler));
                    $this->connected = true;
                }catch (Exception $e){
                    $this->connected = false;
                }
            }
        }else{
            //Already Connected
            /**
             * @todo log double connect
             */
        }
    }
    
    public function db_Query($query,$type,$from = null){
        if(!$this->connected){
            $this->db_Connect();
        }
        if($this->method = "mysqli"){
            try{
            $query = $this->handler->real_escape_string($query);
            $res = $this->handler->query($query);
            
            /**
             * @todo Log query
             */
            
            return $res;
            }catch (Exception $e){
                /**
                 * @todo Handle Errors/
                 */
            }
        }else{//use legacy mysql functions
            
        }
    }
    
    public function db_Select($tbl,$fields = "*",$where = "1"){
        $query = "SELECT $fields FROM $tbl WHERE $where";
        $from = $
        return $this->db_Query($query,$type)
        if(MMDebugLevel >= MMDebugMySql){
            $from = debug_backtrace();
        }else{
            $from = null;
        }
        return $this->db_Query($query,__FUNCTION__,$from);
    }
    
    public function db_Insert($tbl,$fields,$values){
        //figure out if $fields is an array
        if(is_array($fields)){
            //create the string version...
            foreach($fields as $k=>$v){
                $fields[$k] .= "`".$f."`";
            }
            $fields = implode(", ",$fields);
            
        }//either way, add the parentheses
        //decipher $values
        //it's either a string (goes right to $query)
        // its an array of strings (implode the values into (val,val,val) format)
        // it's an array of arrays (each 2nd level array needs to be imploded,then implode into (val,val,val),(val,val,val) format)
        if(is_array($values)){ // $values is an array
            $tmp = '';
            foreach($values as $k=>$v){
                if(is_array($v)){//$values[n] is an array
                    $v = implode('"',$v);
                    $v = '"'.$v.'"';
                    $values[$k] = $v;
                }
                //This makes $values an array of strings
            }
            $values = implode("),(",$values);// this will make $values a string "val,val,val),(val,val,val"
        }
        
        $query = "INSERT INTO $tbl ($fields) VALUES ($values)";
        if(MMDebugLevel >= MMDebugMySql){
            $from = debug_backtrace();
        }else{
            $from = null;
        }
        return $this->db_Query($query,__FUNCTION__,$from);
        
    }
 
}
?>