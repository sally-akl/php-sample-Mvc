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
    
    
}




?>