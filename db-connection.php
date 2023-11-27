<?php

session_start();

function CreateDatabaseConnection(){

  $user = "authorized-user-for-given-db";

  $pass = "Password-to-db";

  return new PDO('mysql:host=localhost;dbname=database', $user, $pass);

}

?>
              
               ©2023 Patryk Namyslak
               
               
               Author: Patryk Namyslak