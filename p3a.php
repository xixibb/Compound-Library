<?php include "mainheader.php"?>
<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>

<div class="div-center">
<h2> View Histogram for Selected Property</h2>
</div>

<?php
# get chosen property
$chosen = -1;
if(isset($_POST['tgval'])) {
$tgval = $_POST['tgval'];
$chosen = get_chosen($tgval, $dbfs);
}

echo '<div class="div-center" style="margin-top: 35px;">';
echo '<form action="p3a.php" method="post" id="selectProperty"><select name="tgval" style="height: 30px;" required><option style="text-align:center" value="">---- Select Property ----</option>';
for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
$ifchoose = "";
if ($chosen==$j) {$ifchoose="selected";}
printf('<option value="%s" %s>%15s</option>',$dbfs[$j],  $ifchoose, $nms[$j]);
  echo "\n";
} 
echo '</select><br><button class="button-hoverable" type="submit" style="margin-top:20px;">';
echo 'OK </button>';
echo '</form></div>';

# use python histog.py to generate histogram picture
if(isset($_POST['tgval'])) 
   {
     $chosen = 0;
     $tgval = $_POST['tgval'];
     for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
       if(strcmp($dbfs[$j],$tgval) == 0) $chosen = $j; 
     } 
     $db_server = dbset($db_hostname,$db_username,$db_password,$db_database);
     $query = "select * from Manufacturers";
     $result = mysql_query($query);
     if(!$result) die("unable to process query: " . mysql_error());
     $rows = mysql_num_rows($result);
     $smask = $_SESSION['supmask'];
     $firstmn = False;
     $mansel = "(";
     for($j = 0 ; $j < $rows ; ++$j) {
      $row = mysql_fetch_row($result);
      $sid[$j] = $row[0];
      $snm[$j] = $row[1];
      $sact[$j] = 0;
      $tvl = 1 << ($sid[$j] - 1);
      if($tvl == ($tvl & $smask)) {
        $sact[$j] = 1;
        if($firstmn) $mansel = $mansel." or ";
        $firstmn = True;
        $mansel = $mansel." (ManuID = ".$sid[$j].")";
      }
     }
     $chosennms = $nms[$chosen];
     $mansel = $mansel.")";
     $comtodo = "./pyscript/histog.py ".$dbfs[$chosen]." \"".$nms[$chosen]."\" \"".$mansel."\"";
     $output = base64_encode(shell_exec($comtodo));
     echo <<<_imgput
     <div class="div-center" style="text-align:center">
     <img style="display:inline-block" src="data:image/png;base64,$output" alt="Histogram of $chosennms"/>
     </div>
_imgput;
   }
?>


<?php include_once "footer.php"; ?>
</body>
</html>

