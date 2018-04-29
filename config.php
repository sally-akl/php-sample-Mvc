<?php

return array(

    "db_class_path"=>dirname(__FILE__)."/ORM/mysql/",
    "db_enable_key"=>"db", 
    "db"=>array(
          "class"=>"Connection",
          "Host"=>"localhost",
          "Username"=>"root",
          "Password"=>"",
          "Dbname"=>""
    ),
    "mainpath"=>"http://$_SERVER[HTTP_HOST]/caseTest",
)


?>