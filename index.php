<?php
require_once dirname(__FILE__).'/GlobalVariables.php';
GlobalVariables::$configArr = include dirname(__FILE__).'/config.php';
include GlobalVariables::$configArr["db_class_path"]."/Connection.php";
include GlobalVariables::$configArr["db_class_path"]."/Commend.php";


require_once dirname(__FILE__)."/models/MainModel.php";

require_once dirname(__FILE__)."/TemplateView.php";


?>