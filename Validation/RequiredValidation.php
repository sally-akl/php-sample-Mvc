<?php

 class RequiredValidation extends Validation
{
   
    
    public function check($check_arr)
    {
        
        foreach($check_arr[0] as $k=>$fieldname)
        {
            
            
            if(isset($this->getFormFields()[$fieldname]) && empty($this->getFormFields()[$fieldname]))
            {
                
                if(isset($check_arr[count($check_arr)-1]) && is_array($check_arr[count($check_arr)-1]))
                {
                    
                    if(count($check_arr[count($check_arr)-1]) > 0)
                    {
                        $this->addError($check_arr[count($check_arr)-1][$k]);
                    }
                    else
                    {
                        $this->addError($fieldname." is require");
                    }
                    
                    
                    
                }
                
            }
        }
        
       
        
        
        
    }
    
    
    
}


?>