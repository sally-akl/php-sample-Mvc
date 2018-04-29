<?php

 class MainController
{
     
    public $View;
    public $submit_fields;
    public function __construct()
    {
       $this->View = new TemplateView();
       $this->submit_fields = array();
       Connection::Instance()->connect();
    }
   
}


?>