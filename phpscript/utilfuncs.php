<?php
# database connector
function dbset($db_hostname,$db_username,$db_password,$db_database)
{
  $db_server = mysql_connect($db_hostname,$db_username,$db_password);
  if(!$db_server) die("Unable to connect to database: " . mysql_error());
  mysql_select_db($db_database,$db_server) or die ("Unable to select database: " . mysql_error());
  return $db_server;
}

# get sql clause for selected suppliers
function get_mansel($mymask)
{
$query = "select * from Manufacturers";
$result = mysql_query($query);
if(!$result) die("unable to process query: " . mysql_error());
$rows = mysql_num_rows($result);
$mansel = "(";
$firstmn = False;			
for($j = 0 ; $j < $rows ; ++$j)
  {
    $row = mysql_fetch_row($result);
    $sid[$j] = $row[0];
    $snm[$j] = $row[1];
    $sact[$j] = 0;
    $tvl = 1 << ($sid[$j] - 1);

    if($tvl == ($tvl & $mymask)) {
        $sact[$j] = 1;
        if($firstmn) $mansel = $mansel." or ";
        $firstmn = True;
        $mansel = $mansel." (ManuID = ".$sid[$j].")";
      }
  }
$mansel = $mansel.")";
return $mansel;
}

# get post information
function get_post($var)
{
  return mysql_real_escape_string($_POST[$var]);
}

# set up manarray and manid to database manufacturer information
function get_manarray(&$manarray,&$manid)
{
	$query = "select * from Manufacturers";
	$result = mysql_query($query);
	if(!$result) die("unable to process query: " . mysql_error());
	$manrows = mysql_num_rows($result);
	for($j = 0 ; $j < $manrows ; ++$j)
	  {
	    $row = mysql_fetch_row($result);
            $manid[$j] = $row[0];
	    $manarray[$j] = $row[1];
	  }
         return;
}

# get chosen property
function get_chosen($tgval ,&$dbfs){
$chosen = -1;
 for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
       if(strcmp($dbfs[$j],$tgval) == 0) $chosen = $j; 
     } 
     
 return $chosen;
}

# get max and min values for the input dbfs columns
# also mask is used to filter compounds by suppliers
function get_max_min($mask, &$dbfs, &$maxarray, &$minarray){
  $mansel = get_mansel($mask);
  
  $query = "select ";
  for ($j=0; $j<sizeof($dbfs); ++$j){
    $query = $query."MAX(".$dbfs[$j]."), ";
    $query = $query."MIN(".$dbfs[$j].")";
    if ($j != sizeof($dbfs)-1) $query = $query.", ";
  }
  $query = $query." from Compounds where ".$mansel;
  
  
  $result = mysql_query($query);
  
  if(!$result) die("unable to process query: " . mysql_error());
  $rows = mysql_num_rows($result);
  $singlerow = mysql_fetch_row($result);
  
  for ($j=0; $j<sizeof($dbfs); ++$j){
    $maxarray[$j] = $singlerow[2*$j];
    $minarray[$j] = $singlerow[2*$j+1]; 
  }
  
  return;
}

# get the url of the given manufacturer
function get_manu_url($manu_name){
  $query = "select contact from Manufacturers where name=\"".$manu_name."\"";
  $result = mysql_query($query);
  
  if(!$result) die("unable to process query: " . mysql_error());
  $row = mysql_fetch_row($result);
  
  return $row[0];
}


?>