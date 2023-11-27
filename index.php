<?php 

include_once $_SERVER['DOCUMENT_ROOT'] . '/path/to/login/(optional)/';

loginRedirect();


$title = "URL Shortener - Your Website";
$page_title = "URL Shortener";
include_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/head.php';
  

  
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shorten URL</title>
</head>
<body>
<form action="shorten.php" method="post">
    <input type="text" name="url_to_shorten" placeholder="Enter the URL to shorten">
    <input type="submit" value="Shorten">
</form>
</body>
</html>

              
               ©2023 Patryk Namyslak
               
               
               Author: Patryk Namyslak