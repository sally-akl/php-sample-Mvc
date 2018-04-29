<?php

 class Validation
{
    private $keys = array(
        
        "isrequired"=>"RequiredValidation",
        "pattern"=>"PatternValidation",
        "length"=>"LengthValidation",
 
     );
    
    private $rules = array();
    private static $errors = array();
    private $formfields = array();
        
    public function __construct()
    {
        
    }
 
    public function validate()
    {
        
        if(count($this->rules) == 0)
             throw new Exception('Rules array is empty');
        if(count($this->formfields) == 0)
              throw new Exception('Fill Field array');
        
        
         $valid_keys = array_keys($this->keys);
         foreach($this->rules as $fields)
         {
             if(!in_array($fields[1], $valid_keys))
             {
                 throw new Exception('Validator key not exist');
                 break;
             }
             
             if(!is_array($fields[0]))
             {
                 throw new Exception('Fields must be array');
                 break;
             }
             
             if(class_exists($this->keys[$fields[1]]))
             {
                 
                 $cls = new $this->keys[$fields[1]]();
                 if(method_exists($cls,'check'))
                 {
                    $cls->setFormField($this->formfields)->check($fields); 
                
                 }
             }
             
             
         }
        
    }
    
    public function setRules($rules)
    {
       $this->rules = array_merge($rules,$this->rules);
    }
    
    public function addRule($rule)
    {
        if(!is_array($rule))
            throw new Exception('Rule must be array');
        
        $this->rules[]=$rule;
        return $this;
    }
    
    public function addError($error)
    {
        if(is_array($error))
             self::$errors = array_merge($error,$this->errors);
        else
            self::$errors[] = $error;
            
    }
    public function getErrors()
    {
        return self::$errors;
        
    }
    public function getFormFields()
    {
        return $this->formfields;
    }
    public function setFormField($f_field)
    {
        $this->formfields = $f_field;
        return $this;
    }
    
    
    
}


?>