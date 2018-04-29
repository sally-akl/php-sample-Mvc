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
     
    
    public function Build()
    {
         $this->_query = (isset($this->_buildquery["is_distinct"]) && $this->_buildquery["is_distinct"]==true)?'select DISTINCT ':'select ';
        
        $this->_query .= (isset($this->_buildquery["select"]) && !empty($this->_buildquery["select"]))?$this->_buildquery["select"]:'*';
        
        if(isset($this->_buildquery["from"]) && !empty($this->_buildquery["from"]))
           $this->_query .=" from ".$this->_buildquery["from"];
        
        
         if(isset($this->_buildquery["join"]) && !empty($this->_buildquery["join"]))
           $this->_query .="  join ".$this->_buildquery["join"];
        
        
        if(isset($this->_buildquery["where"]) && !empty($this->_buildquery["where"]))
           $this->_query .="   where ".$this->_buildquery["where"];
        
        if(isset($this->_buildquery["group"]) && !empty($this->_buildquery["group"]))
           $this->_query .="  group by ".$this->_buildquery["group"];
        
        if(isset($this->_buildquery["order"]) && !empty($this->_buildquery["order"]))
           $this->_query .=" order by ".$this->_buildquery["order"];
        
       
        return $this;
        
        
    }
    
    
    private function QueryInternal($type , $associative)
    {
        if($this->_return != null)
        {
            
            $column_num = mysql_num_fields($this->_return);
            for ($i=0; $i < $column_num; $i++)
            {
                $this->_columns[] = mysql_field_name($this->_return, $i);
            }
            
           while($row = mysql_fetch_array($this->_return, MYSQL_ASSOC)) 
           {
              $sub_arr = array();  
              foreach($this->_columns as $column) 
              {
                  if($associative) $sub_arr[$column] = $row[$column];
                  else $sub_arr[] = $row[$column];
              }
              
              $this->_result[] = $sub_arr;
              if($type == "row") break;
            
            }
            
        }  
        
    }
    
    /* build query according to type (query , non-query)
    *
    */
    private function BuildQuery()
    {
         if(is_array($this->_columval) && count($this->_columval) >0)
         {
              foreach($this->_columval as $key=>$val)
              {
                  $val = mysql_real_escape_string($val);
                  $this->_query = str_replace($key, $val, $this->_query);
                        
              }
                    
          }
        
        if(is_array($this->_condition) && count($this->_condition) > 0)
        {
            
             
            $this->_query.="where ";
            foreach($this->_condition as $key=>$valarr)
            {
                foreach($valarr as $k=>$v)
                {
                    $i=0;
                    foreach($v as $column=>$colval)
                    {
                        $colval = mysql_real_escape_string($colval);
                        if(!empty($this->ConditionSymbol($k)))
                        {
                            if($i == count($v)-1) $this->_query.= " ".$column.$this->ConditionSymbol($k)."'$colval'"." ";
                            else $this->_query.= " ".$column.$this->ConditionSymbol($k)."'$colval'"." ".$key." ";
                            
                        }
                        
                       $i++;  
                    }
                    
                    
                    
                }
            }
            
            
            
        }
        
  
    }
    
    private function ConditionSymbol($symbol)
    {
        switch($symbol)
        {
                
             case "gt": 
                return ">";
                break;
             case "ls": 
                return "<";
                break;
             case "lseq": 
                return "<=";
                break;
             case "gteq": 
                return ">=";
                break;
              case "eq": 
                return "=";
                break;  
        
        }
        
        return "";
        
        
    }
	
    private function QueryDb()
    {
        
        $this->_return = mysql_query( $this->_query , $this->_connection ); 
        if(!$this->_return) {
            throw new Exception('Error In query' . mysql_error());
        }
        
    } 
}




?>