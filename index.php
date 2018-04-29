<?php
require_once dirname(__FILE__).'/GlobalVariables.php';
GlobalVariables::$configArr = include dirname(__FILE__).'/config.php';
include GlobalVariables::$configArr["db_class_path"]."/Connection.php";
include GlobalVariables::$configArr["db_class_path"]."/Commend.php";

require_once dirname(__FILE__)."/Validation/Validation.php";
require_once dirname(__FILE__)."/Validation/RequiredValidation.php";
require_once dirname(__FILE__)."/Validation/PatternValidation.php";
require_once dirname(__FILE__)."/Validation/LengthValidation.php";
require_once dirname(__FILE__)."/Validation/Validator.php";


require_once dirname(__FILE__)."/models/MainModel.php";
require_once dirname(__FILE__)."/TemplateView.php";

if(isset($_GET["controller"]) && !empty($_GET["controller"]))
{
    $controller = filter_input(INPUT_GET, 'controller', FILTER_SANITIZE_SPECIAL_CHARS);
    require_once dirname(__FILE__)."/Controllers/".$controller.".php";
    if(isset($_GET["method"]))
    {
        $method = filter_input(INPUT_GET, 'method', FILTER_SANITIZE_SPECIAL_CHARS);
        $active_controller = new $controller();
        if(method_exists($active_controller,$method))
        {
             parse_str($_SERVER['QUERY_STRING'] , $outargs);
           
             call_user_func_array(array($active_controller, $method),  $outargs);
            
            
        
        }
        else throw new Exception('method not exist in controller');
        
        
    }
    
    
}





?>