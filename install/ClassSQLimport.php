<?php

class sqlImport {

    function is_comment($text){
        if ($text != ""){
            $fL = $text[0];
            $sL = $text[1];
            switch($fL){
                case "#":
                    $text = "";
                    break;
                case "/":
                    if ($sL == "*")
                        $text = "";
                    break;
                case "-":
                    if ($sL == "-")
                        $text = "";
                    break;
                    
            }
        }
        return $text;
    }
    
    	
	//recogemos las variables
	function sqlImport ($host, $user,$pass,$dbname,$ArchivoSql) {
	$this -> host = $host;
	$this -> user = $user;
	$this -> pass = $pass;
    $this -> dbname = $dbname; 
	$this -> ArchivoSql = $ArchivoSql;
    $this->dbConnect();
	}

	//Conexion a la base de datos
	function dbConnect () {
	$con = mysql_connect($this -> host, $this -> user, $this -> pass);
    mysql_select_db($this->dbname,$con);
	}
	
	//Volcamos los datos
	function import () 
	{   
	
   		if ($this -> con !== false) 
   		{
          
     	$f = @gzopen($this -> ArchivoSql,"r");
if($f){ 
$sqlFile = "";
while ($line = gzgets($f,1024)) { 
$sqlFile .= $line;
}
@gzclose($f);


          //  $sqlFile = gzread($f, gzfilesize($this -> ArchivoSql)); 
            
                                   
            $sqlFile = str_replace("\r","%BR%",$sqlFile);
            $sqlFile = str_replace("\n","%BR%",$sqlFile);
            $sqlFile = str_replace("%BR%%BR%","%BR%",$sqlFile);
             
            $sqlArray = explode('%BR%', $sqlFile);
            $sqlArrayToExecute;
            foreach ($sqlArray as $stmt) 
            {
                $stmt = $this->is_comment($stmt);
                if ($stmt != '')
                    $sqlArrayToExecute[] = $stmt;
            }
            $sqlFile = implode("%BR%",$sqlArrayToExecute);
            unset($sqlArrayToExecute);
          
             
            $sqlArray = explode(';%BR%', $sqlFile);
            unset($sqlFile);
            
        
             
             
           if(is_array($sqlArray)){
     		foreach ($sqlArray as $stmt) 
     		{
       			  $stmt = str_replace("%BR%"," ",$stmt);
                $stmt = trim($stmt);
                
              //   print $stmt."<br>" ;
                  
            		$result = mysql_query($stmt);
             	 	if (!$result)
             	 	{
                 	$this ->CodigoError = mysql_errno();
                 	$this ->TextoError = mysql_error();
                	 break;
              		}
                  
        		
        
     	 	}
           }else{
                 $this ->CodigoError = 111;
            $this ->TextoError = "Not valid SQL File ".$this -> ArchivoSql;
           }
        }else{
            $this ->CodigoError = 111;
            $this ->TextoError = "Unable to open file ".$this -> ArchivoSql;
        }
     	 }else{
              $this ->CodigoError = 1;
            $this ->TextoError = "Unable to connect";
         }
     	 
	}//Fin de Dump
	
	//controlamos y mostramos los posibles errores en el proceso
	function ShowErr () 
	{	
  	 	if ($this -> CodigoError == 0)
   		{
   		$Salida ["exito"] =  1;
		}else{
		$Salida ["exito"] =  0;   		
		$Salida ["errorCode"] = $this -> CodigoError;
		$Salida ["errorText"] =  $this -> TextoError;
   		}

	return $Salida;
	}

} 
   
?> 