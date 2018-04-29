<?php


 class Commend
{
    
    private $_connection= null;
    private $_query = "";
    private $_result = array();
    private $_return = null;
    private $_columns = array();
    private $_columval = array();
    private $_condition  = array();
    private $_buildquery = array();
    
    public function __construct($conn, $query , $columval , $condition)
    {
       if($conn == null) throw new Exception('Connection is null'); 
       $this->_connection = $conn;
       $this->_query = $query;
       $this->_columval = $columval;
       $this->_condition = $condition;
       $this->_result = array();
       $this->_columns = array();
        
    }
    

    /**
	 * Executes the Non query SQL statement such insert , update , create , delete
	 */
    
    public function NonQuery()
    {
       $this->BuildQuery();
       if(!empty($this->_query)) $this->QueryDb();
    }
    
    /**
	 * Executes the SQL statement and returns result according to type
     * $is_associative if true will return result as key=>value , key is table column and value result of that column
     * $type if row return first row of result and if empty will return all result
	 */
    
    
    public function Query($is_associative = true , $type)
    {
        if(!empty($this->_query))
        {
           $this->BuildQuery();
           $this->QueryDb();
           $this->QueryInternal($type,$is_associative);
         
        }
        return $this;  
    }
    
    
    public function GetResult()
    {
        return $this->_result;
    }
    
    public function Insert($table , $columnvalue)
    {
        if(!is_array($columnvalue))
        {
            throw new Exception('Please enter column value array');
        }
        
        
        $this->_query="";
        $values=array();
		$names=array();
        foreach($columnvalue as $name=>$value)
        {
            $names[] = $name;
            $values[] = "'" .mysql_real_escape_string($value). "'";
        }
        
        $this->_query='INSERT INTO ' .$table.' '
			. ' (' . implode(', ',$names) . ') VALUES ('
			. implode(', ', $values) . ')';
        
        echo $this->_query;
        $this->NonQuery();
    }
    public function Update($table , $columnvalue, $condition=array())
    {
        if(!is_array($columnvalue) || count($columnvalue) == 0 )
        {
            throw new Exception('Please enter column value array');
        }
        $this->_query='update '.$table.' set ';
        $values = array();
        foreach($columnvalue as $col=>$val)
        {
            $values[] = $col."="."'$val'";
            
        }
        $this->_query .=implode(',', $values);
        
        $this->_columval = array();
        $this->_condition = $condition;
        $this->NonQuery();
        
    }
    public function Delete($table, $condition=array())
    {
        $this->_query='delete from  '.$table." ";
        $this->_condition = $condition;
        $this->NonQuery();        
    }
    
    public function Select($columns)
    {
        if(is_array($columns))
        {
            $columns = implode(",",$columns);
        }
        
        $this->_buildquery["select"] = $columns;
        return $this;
        
    }
    public function From($tables)
    {
        if(is_array($tables))
        {
            $tables = implode(",",$tables);
        }
        
        $this->_buildquery["from"] = $tables;
        return $this;
    }
    public function Where($conditions)
    {
        if(is_array($conditions))   
        {
            $this->_query="";
            $this->_condition = $conditions;
            $this->BuildQuery();
            $conditions = str_replace("where","",$this->_query);
            $this->_condition = array();
        }
        $this->_buildquery["where"] =  $conditions;
        return $this;
    }
    public function Group($columns)
    {
        if(is_array($columns))
        {
            $columns = implode(",",$columns);
        }
        $this->_buildquery["group"] =  $columns;
        return $this;
    }
    
    public function Order($columns)
    {
        if(is_array($columns))
        {
            throw new Exception('Column is string'); 
        }
         $this->_buildquery["order"] =  $columns;
        return $this;

    }
    
    public function Distinct($columns)
    {
        if(empty($columns))
        {
            throw new Exception('Fill Columns'); 
        }
        
         $this->_buildquery["is_distinct"] = true;
         return $this->Select($columns);
        
    }
     
    public function Join($jointable , $on) 
    {
       if(empty($jointable) || empty($on) )
           throw new Exception('Enter join and on of table'); 
           
           
           $this->_buildquery["join"] = $jointable." on".$on." ";
           return $this;
    }
     
    
    
}




?>