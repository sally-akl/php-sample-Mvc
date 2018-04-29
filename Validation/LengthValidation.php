<?php

 class LengthValidation extends Validation
{
   
    /*
    *  array(
          array("f1","f2"),
          "length",
          array(
            "condition"=>"gt",
            "value"=>"100"
          ),
          array("message_error1","message_error2")
    
    
    
         )
    */
    public function check($check_arr)
    {
        foreach($check_arr[0] as $k=>$fieldname)
        {
            if(isset($this->getFormFields()[$fieldname]) && isset($check_arr[2]) && isset($check_arr[2]["condition"]) && isset($check_arr[2]["value"]))
            {
                if(!$this->ComparisonSymbol($check_arr[2]["condition"] , $this->getFormFields()[$fieldname], $check_arr[2]["value"] ))                
                {
                    
                    if(isset($check_arr[count($check_arr)-1]) && is_array($check_arr[count($check_arr)-1]))
                    {
                        if(count($check_arr[count($check_arr)-1]) > 0)
                        {
                            $this->addError($check_arr[count($check_arr)-1][$k]);
                        }
                        else
                        {
                            $this->addError($fieldname."length must be".$check_arr[2]["condition"]." ".$check_arr[2]["value"]);
                        }



                    }
                    
                }
                
                
                
            }
            else
            {
                throw new Exception('Error in validation');
                break;
            }
            
            
        }
        
        
    }
    
    private function ComparisonSymbol($symbol ,$compare_val ,  $val )
    {
        $len = strlen($compare_val);
         switch($symbol)
        {
                
             case "gt": 
                return $len > $val;
                break;
             case "ls": 
                return $len < $val;
                break;
             case "lseq": 
                return $len <= $val;
                break;
             case "gteq": 
                return $len >= $val;
                break;
              case "eq": 
                return $len = $val;
                break;  
        
        }
        
        return "";
    }
    
    
    
    
    
    
}


?>