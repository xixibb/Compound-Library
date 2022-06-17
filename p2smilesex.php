<?php include "mainheader.php"?>

<?php
# get maximum and minimum value for four atom related properties in the library
# mansel is used to restrict the compounds to come from selected suppliers
$db_server = dbset($db_hostname,$db_username,$db_password,$db_database);  
$smask = $_SESSION['supmask'];
$mansel = get_mansel($smask);
$tgarray = array("natm", "ncar", "nnit", "noxy");
$maxarr = array();
$minarr = array();
get_max_min($smask, $tgarray, $maxarr, $minarr);
?>

<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>

<?php
# Create Form 
#There are four propertices in total can be specificed 
#natm, ncr, nnt, nox
?>

<div class="div-center" style="text-align:center; margin-bottom:40px;">
<h2> Retrieve Catalogue By Atoms</h2>
<i> The maximum and minimum values of each property among compounds in currently selected suppliers are listed below.</i>
<br><br>
<i> Only top 50 compounds are displayed. </i>
</div>
<div class="div-center">
   <form action="p2smilesex.php" method="post" style="font-size:16px; font-weight:bold;color:#05386B;">
     <div class="div-grid-4">
       <div style="text-align:left"> Max Atoms </div>
       <div> <input type="number" step="any" name="natmax" max=<?php echo $maxarr[0];?> placeholder=<?php echo "\"Maximum: ".$maxarr[0]."\""; ?>/>  </div>
       <div style="text-align:left"> Min Atoms </div> 
       <div> <input type="number" step="any" name="natmin" min=<?php echo $minarr[0];?> placeholder=<?php echo "\"Minimum: ".$minarr[0]."\""; ?>/> </div>
     </div>
      
       <div class="div-grid-4">
         <div style="text-align:left"> Max Carbons </div> 
         <div> <input type="number" step="any" name="ncrmax" max=<?php echo $maxarr[1];?> placeholder=<?php echo "\"Maximum: ".$maxarr[1]."\""; ?>/> </div> 
         <div style="text-align:left"> Min Carbons </div>
         <div> <input type="number" step="any" name="ncrmin" min=<?php echo $minarr[1];?> placeholder=<?php echo "\"Minimum: ".$minarr[1]."\""; ?>/></div>
       </div>

       <div class="div-grid-4">
       <div style="text-align:left"> Max Nitrogens </div>
       <div> <input type="number" step="any" name="nntmax" max=<?php echo $maxarr[2];?> placeholder=<?php echo "\"Maximum: ".$maxarr[2]."\""; ?>/> </div>  
       <div style="text-align:left"> Min Nitrogens </div>
       <div> <input type="number" step="any" name="nntmin" min=<?php echo $minarr[2];?> placeholder=<?php echo "\"Minimum: ".$minarr[2]."\""; ?>/> </div>
       </div>
       
       <div class="div-grid-4">
       <div style="text-align:left"> Max Oxygens </div>
       <div> <input type="number" step="any" name="noxmax" max=<?php echo $maxarr[3];?> placeholder=<?php echo "\"Maximum: ".$maxarr[3]."\""; ?>/> </div>
       <div style="text-align:left"> Min Oxygens </div> 
       <div> <input type="number" step="any" name="noxmin" min=<?php echo $minarr[3];?> placeholder=<?php echo "\"Minimum: ".$minarr[3]."\""; ?>/> </div>
       </div>
    <button class="button-hoverable" style="margin-top:20px" type="submit" value="list"> list </button>
</form>
</div>

<?php
# get input parameters to construct sql query
# if there is no user input value, then sql query will retreive compounds with supplier constraint only
$setpar = isset($_POST['natmax']); 

if($setpar) {
# if user gives valid inputs then change $firstsl = true
# also set $compsel by filling in user inputs
$compsel = "select catn,id,ManuID from Compounds ";
$firstsl = False;
  $compsel = $compsel." where (";
  if (($_POST['natmax'] != "") && ($_POST['natmin']!="")) {
    $compsel = $compsel."(natm >= ".get_post('natmin')." and  natm <= ".get_post('natmax').")";
    $firstsl = True;
  }
  if (($_POST['ncrmax']!="") && ($_POST['ncrmin']!="")) {
    if($firstsl) $compsel = $compsel." and ";
    $compsel = $compsel."(ncar >= ".get_post('ncrmin')." and  ncar <= ".get_post('ncrmax').")";
    $firstsl = True;
  }
  if (($_POST['nntmax']!="") && ($_POST['nntmin']!="")) {
    if($firstsl) $compsel = $compsel." and ";
    $compsel = $compsel."(nnit >= ".get_post('nntmin')." and  nnit <= ".get_post('nntmax').")";
    $firstsl = True;
  }
  if (($_POST['noxmax']!="") && ($_POST['noxmin']!="")) {
    if($firstsl) $compsel = $compsel." and ";
    $compsel = $compsel."(noxy >= ".get_post('noxmin')." and  noxy <= ".get_post('noxmax').")";
    $firstsl = True;
  }


if($firstsl) {
# if user gives valid inputs
  $compsel = $compsel.") and ".$mansel." LIMIT 50";
}else{
# if user doesn't give valid inputs
  $compsel = $compsel.$mansel." ) LIMIT 50";
}
   $result = mysql_query($compsel);
   if(!$result) die("unable to process query: " . mysql_error());
   $rows = mysql_num_rows($result);
     
   # create result table     
     echo <<<_TABLESET
<div class="div-table">
<table id="manutable"  id="tableCanSort">
<thead>
<tr>
<th> </th>
<th>Cataloge ID</th>
<th>manufacturer</th>
<th>Smiles String</th>
<th>Structure</th>
</tr>
</thead>
<tbody>
_TABLESET;
     for($j = 0 ; $j < $rows ; ++$j)
       {
	 $row = mysql_fetch_row($result);
         $cid = $row[1];
         # get smiles from database
         $compselsmi = "select smiles from Smiles where cid = ".$cid; 
         $resultsmi = mysql_query($compselsmi);
         $smilesrow = mysql_fetch_row($resultsmi);
          # get 2D image from cactus.nci.nih.gov
         $convurl = "https://cactus.nci.nih.gov/chemical/structure/".urlencode($smilesrow[0])."/image";
         $convstr = base64_encode(file_get_contents($convurl));
	 printf("<tr><td>%s</td><td><a href=moldisplay.php?cid=%s>%s</a></td><td>%s</td><td>%s</td><td><img  src=\"data:image/gif;base64,%s\"></img></td></tr>\n",$j+1, $cid, $row[0],$snm[$row[2]-1],$smilesrow[0],$convstr);

       }
     echo "</tbody></table>\n</div>\n";
}
?>

<?php include_once "footer.php"; ?>
</body>

</html>

