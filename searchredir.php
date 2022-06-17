<?php
# get webiste directory
require_once 'websitedir.php';
#get the database access credentials
require_once 'login.php';
#get the utility functions
require_once 'phpscript/utilfuncs.php';

# redirect to the compund information page if there exists compound matching the input smiles or name
# otherwise, redirect to the index page
if (isset($_GET['search'])){
  # check database connection
  $db_server = dbset($db_hostname,$db_username,$db_password,$db_database);
  # get search query
  $content = $_GET['search'];
  # eliminate empty spaces at the beginning and end of search query
  $content = trim($content);
  # create sql two queries 
  $query_smiles = "select cid from Smiles where smiles="."\"".$content."\"";
  $query_name = "select id from Compounds where catn="."\"".$content."\"";
  
  # search if the input string matches any smiles
  $result = mysql_query($query_smiles);
  if(!$result) die("unable to process query: " . mysql_error());
  $rows = mysql_num_rows($result);
  if ($rows > 0){
    # matched compound found
    $row = mysql_fetch_row($result);
    $cid = $row[0];
    echo $cid;
    header("location: $webpath/moldisplay.php?cid=$cid");
    exit;
  }
  
  # search if the input string matches any Catalogue 
  $result = mysql_query($query_name);
  if(!$result) die("unable to process query: " . mysql_error());
  $rows = mysql_num_rows($result);
  if ($rows > 0){
    # matched compound found
    $row = mysql_fetch_row($result);
    $cid = $row[0];
    echo $cid;
    header("location: $webpath/moldisplay.php?cid=$cid");
    exit;
  }

}

# redirect to index page if no matched compound
header("location: $webpath/index.php?noresult=1");
exit;
?>