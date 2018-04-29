<?php

class TemplateView
{
  
    public function render($render_file , $var_arr = array())
    {
	   
        
        if(!isset(GlobalVariables::$configArr["theme"]))
               throw new Exception('must enter theme key in config file');
               
               if(count($var_arr) > 0) extract($var_arr);       
               $content = $render_file;
               require_once GlobalVariables::$configArr["theme"];
        
    }
    
}


?>