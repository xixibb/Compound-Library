<?php include "mainheader.php" ?>
<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>
<?php
#connect to thm mysql database
$db_server = dbset($db_hostname,$db_username,$db_password, $db_database);

#get manufacturer information together with the number of compounds each has in the library
$query = "select M.*, stat.cnum from Manufacturers M join (select ManuID, count(*) as cnum  from Compounds group by ManuID) stat on M.id = stat.ManuID";
$result = mysql_query($query);
if(!$result) die("unable to process query: " . mysql_error());
$rows = mysql_num_rows($result);

# get currently chosen suppliers from smask
$smask = $_SESSION['supmask'];
for($j = 0 ; $j < $rows ; ++$j)
  {
    $row = mysql_fetch_row($result);
    $sid[$j] = $row[0];
    $snm[$j] = $row[1];
    $sweb[$j] = $row[2];
    $scount[$j]  = $row[3];
    $sact[$j] = 0;
    $tvl = 1 << ($sid[$j] - 1);
    if($tvl == ($tvl & $smask)) {
	$sact[$j] = 1;
      }
  }
  
# get POST information and update supplier selections
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
   
   # create the form for choosing suppliers
   echo "<h2> Select Suppliers </h2>";
   echo "<div class='div-table'>"; 
   echo "<table id='manutable'>";
   echo "<tr><th> </th><th>Manufacturer</th><th>Website</th><th>#Compound in library </th></tr>\n"; 
   for($j = 0 ; $j < $rows ; ++$j) {
    
     echo "<tr>";
     echo "<td><input type='checkbox' name='supplier[]' value='$snm[$j]' form='supplierselectform'" ;
     if ($sact[$j] == 1){
       echo "checked";
     }
     echo"> </td>";
     echo "<td> $snm[$j] </td>";
     echo "<td> <a href='$sweb[$j]'> $sweb[$j] </a></td>";
     echo "<td> $scount[$j] </td>";
     echo "</tr>";
   }
   echo "</table></div>";
?>

<?php
# there is a validator to guarantee that at least one supplier has been chosen
?>
<form action="p1.php" method="post" style="text-align:center" id="supplierselectform" onsubmit="return supplierValidation()">
 <button class="button-hoverable" type="submit" value=""> Select! </button>
</form>
<script>
function supplierValidation(){
  var supsel = document.forms['supplierselectform'];
  var nsup = supsel.length - 1;
  var atleastone = false;
  
  for (let i = 0; i < nsup; i++) {
    if (supsel[i].checked) {
      atleastone = true;
      break;
    }
  }
  
  if (!atleastone) {
    alert("Please select at least one supplier");
    return false;
  } 
}

</script>

<?php include_once "footer.php"; ?>

</body>
</html>
