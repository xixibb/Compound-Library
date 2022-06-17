<?php
# get website directory
require_once 'websitedir.php';

#check if session has user information
if(!(isset($_SESSION['forname']) &&
     isset($_SESSION['surname'])))
  {
  header("location: $webpath/complib.php");
  }
?>