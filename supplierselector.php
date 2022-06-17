<?php
#get session
include_once 'session.php';
#get the database access credentials
require_once 'login.php';
#get utilies functions
require_once 'phpscript/utilfuncs.php';
?>

<?php 
# wrapper everything into a div file 
?>

<div style="display:block; background-color: #05386B; text-align: center; height: 50px; min-width: 1000px; overflow: auto" id="supselectorbar">
<style>
/* Set up style  */
.leadtext{
  display: inline-block;
  float: left;
  margin: 10px auto auto 16px;
  background-color: #05386B;
  color:  #F64C72;
  font-size: 16px;
  font-weight: bold;
  text-align: center;
  overflow: auto;
}

.supplierbanner{
  margin-left: 50px;
  display: inline-block;
  float: left;
  overflow: auto;
}

.welcomeInfo{
  margin-top: 10px;
  margin-right: 25px;
  height:40px;
  font-size: 15px;
  font-weight: bold;
  text-align: center;
  color: #EDF5E1;
  display: inline-block;
  float:right;
  overflow: auto;
}

.input-checkbox{
  display: inline-block;
  margin-top: 15px;
  margin-bottom: 15px;
  margin-left: 2px;
  float: left;
}
.input-checkbox input[type=checkbox] {
    display: none;
  }
  input[type=checkbox] + label {
    background-color: #EDF5E1;
    color: #8EE4AF;
    font-weight: bold;
    height: 45px;
    padding: 14px 16px;
    font-size: 14px;
    text-decoration: none;
    text-align: center;
    opacity: 0.5;
  }
.input-checkbox input[type=checkbox] + label:hover {
    border: 1px solid #F64C72;
    color: #F64C72;
  }
.input-checkbox input[type=checkbox]:checked + label {
    border: none;
    background-color: #F64C72;
    color: #EDF5E1;
    opacity: 1;
  }
</style>

<div class="leadtext"> Currently Selected Suppliers </div>
<div class="supplierbanner">

<?php
#connect to thm mysql database
$db_server = dbset($db_hostname,$db_username,$db_password,$db_database);     
# get the selected suppliers by supmask
$query = "select * from Manufacturers";
$result = mysql_query($query);
if(!$result) die("unable to process query: " . mysql_error());
$rows = mysql_num_rows($result);
$smask = $_SESSION['supmask'];
for($j = 0 ; $j < $rows ; ++$j)
  {
    $row = mysql_fetch_row($result);
    $sid[$j] = $row[0];
    $snm[$j] = $row[1];
    $sact[$j] = 0;
    $tvl = 1 << ($sid[$j] - 1);
    if($tvl == ($tvl & $smask)) {
	$sact[$j] = 1;
      }
  }

# the following get POST is not redundant
# this allows the information bar refreshes after selecting suppliers in p1.php at the same time
# because this php script is included in p1.php
# by send POST to p1.php, this script can also access to POST information
if(isset($_POST['supplier'])) {
     $supplier = $_POST['supplier'];
     $nele = sizeof($supplier);
     for($k = 0; $k <$rows; ++$k) {
     $sact[$k] = 0;
     for($j = 0 ; $j < $nele ; ++$j) {
	 if(strcmp($supplier[$j],$snm[$k]) == 0) $sact[$k] = 1;
       }
     }
     $smask = 0;
  for($j = 0 ; $j < $rows ; ++$j) {
	   if($sact[$j] == 1) {
	     $smask = $smask + (1 << ($sid[$j] - 1));
	   }
  }
     $_SESSION['supmask'] = $smask;
}
# mark selected suppliers with different colors
# here each supplier has a single checkbox
# however due to the time limit, these checkboxes are only for displaying information
# there is no interactive functionality currently
# these checkboxes were designed to allow users select suppliers directly without the need to visit supplier select page  
    for($j = 0 ; $j < $rows ; ++$j)
      {
     echo "<div class='input-checkbox'><input type='checkbox' name='supplier[]' value='$snm[$j]' id='supplier$j' disabled='disabled'";
     
     if($sact[$j] == 1) {
       echo "checked";
     } else{
       echo "";
     }
     
     
     echo "/>";
 echo "<label for='supplier$j'> $snm[$j] </label>";
  echo '</div>';
        
	echo"\n";
      }



echo "</div>";
# create the welcome information on the top-right
echo "<div class='welcomeInfo'>";
if(!(isset($_SESSION['forname']) &&
     isset($_SESSION['surname'])))
  {
  echo "Please login";
  
}
else{
$fn = $_SESSION['forname'];

echo "<i> Welcome </i>  $fn ";
}
?>

</div>
</div>


