<?php

 class Connection
{
    
    private static   $_instance = null;
    private $_connection= null;
    private static $_connectionDetailsArr = null;
    
    
    public static function Instance()
    {
        if(self::$_instance == null)
        {
            self::$_connectionDetailsArr = GlobalVariables::$configArr[GlobalVariables::$configArr["db_enable_key"]];
            
            self::$_instance = new Connection();
        }
        
        return self::$_instance;  
    }
    
    private function __construct()
    {
       
    }
    
	public  function connect()
    {
        if($this->getconnection() == null)
        {
            
            if(empty(self::$_connectionDetailsArr["Host"]) || empty(self::$_connectionDetailsArr["Username"]) || empty(self::$_connectionDetailsArr["Dbname"]) )  
            {
                throw new Exception('Connection Host or Username or Database name is empty');
            }
            
            
           $this->_connection =  mysql_connect(self::$_connectionDetailsArr["Host"], self::$_connectionDetailsArr["Username"],   self::$_connectionDetailsArr["Password"]);
           if(!$this->_connection) 
           {
               throw new Exception('Connection Failed'. mysql_error());
           }
            
           if(!mysql_select_db(self::$_connectionDetailsArr["Dbname"]))  
           {
              throw new Exception('Database do not exist'); 
           }
            
            
        }
        
        
        
    }
	
	public function close()
    {
        mysql_close($this->_connection);
        $this->_connection= null;
    }
 
    public function getconnection()
    {
        return $this->_connection;
    }
	 /*
    * create new command query 
    * $query is query of database
    * $columval is key=>value array contains array of where condition and will concat with and
    * $condition is array of condition and its structure like 
         array(

            "and"=>array(
                          "gt"=>array(
                              "columnname"=>"value"

                          ),
                          "gteq"=>array(),
                          "ls"=>array(),
                           "lseq"=>array()


                        )



            )
    */
    
    public function newcommend($query = "" , $columval=array() ,$condition=array())
    {
        return new Commend($this-> getconnection()  , $query , $columval , $condition);
    }
    

    
}




?>