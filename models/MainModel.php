<?php

class MainModel
{
    
    private static   $_instance = null;
    private static $table;
    public static function Instance($tb)
    {
        if(self::$_instance == null)
        {
            
            self::$_instance = new MainModel();
        }
        self::$table = $tb;
        
        return self::$_instance;  
    }
    
    public function findAll()
    {
       
        return Connection::Instance()->newcommend("select * from ".self::$table)
                             ->Query(true,"")->GetResult();
    }
    public function findRow()
    {
         return Connection::Instance()->newcommend("select * from ".self::$table)
                             ->Query(true,"row")->GetResult();
    }
    public function findbycondition($condition)
    {
        return Connection::Instance()->newcommend("select * from ".self::$table." ",array(),$condition)
                             ->Query(true,"")->GetResult();
    }
    
    public function delete($condition)
    {
         Connection::Instance()->newcommend()->Delete(self::$table,$condition);
    }
    public function findByJoin($columns,$join_table,$on,$condition=array())
    {
        if(count($condition) > 0)
             return Connection::Instance()->newcommend()->Select($columns)->From(self::$table)->Join($join_table,$on)->Where($condition)->Build()->Query(true,"")->GetResult();
        else    
        return Connection::Instance()->newcommend()->Select($columns)->From(self::$table)->Join($join_table,$on)->Build()
                             ->Query(true,"")->GetResult();
    }
    
    
    
}


?>