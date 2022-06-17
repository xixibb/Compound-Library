<?php include "mainheader.php"?>
<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>

<div class="div-center">
  <h2> View Correlation for Selected Pair of Properties</h2>
</div>

<?php
# get two chosen property
# default value for either chosen option is -1
$chosen = -1; 
$chosenb = -1;
if(isset($_POST['tgval']) && isset($_POST['tgvalb'])) 
   {
     $tgval = $_POST['tgval'];
     $tgvalb = $_POST['tgvalb'];
     $chosen = get_chosen($tgval, $dbfs);
     $chosenb = get_chosen($tgvalb, $dbfs);
     }
?>

<!-- Create first select form -->

<div class="div-center" style="margin-top: 20px;">
<form action="p4.php" method="post">
<div class="div-grid-2" style="margin-top: 10px;margin-bottom: 0px">
<div>
<select name="tgval" style="height: 30px;  float:right; margin-right:15px;" required>
<option style="text-align:center" value="">---- Select First Property ----</option>

<?php

# get options from dbfs array
for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
$ifchoose = "";
if ($chosen==$j) {
$ifchoose="selected";
}
printf('<option value="%s" %s>%15s</option>',$dbfs[$j],  $ifchoose, $nms[$j]);
  echo "\n";
}
?> 

<!-- Create second select form -->

</select>
</div>
<div>
<select name="tgvalb" style="height: 30px; float:left; margin-left:15px;" required>
<option style="text-align:center" value="">---- Select Second Property ----</option>


<?php

# get options from dbfs array
for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
$ifchoose = "";
if ($chosenb==$j) {
$ifchoose="selected";
}
printf('<option value="%s" %s>%15s</option>',$dbfs[$j],  $ifchoose, $nms[$j]);
  echo "\n";
} 
?>

</select></div>
</div>

<br>
<button class="button-hoverable" type="submit">
OK </button>
</form></div>


<?php
if(isset($_POST['tgval']) && isset($_POST['tgvalb'])) 
   {
     # set up database connection
     $db_server = dbset($db_hostname,$db_username,$db_password,$db_database);
     
     $smask = $_SESSION['supmask'];
     $mansel = get_mansel($smask); # get where clause for selected suppliers
     
   # use python to get correlation
    $comtodo = "./pyscript/correlate3.py ".$dbfs[$chosen]." ".$dbfs[$chosenb]." \"".$mansel."\"";
    # build table
    echo <<< _TABLE
    <div class="div-table">
      <table id="manutable">
       <thead>
       <tr>
         <th> Correlation </th>
         <th> p-value </th>
         <th> Total Compounds </th>
       </tr>
       </thead>
      <tbody>
_TABLE;
    # call python 
    $rescor = shell_exec($comtodo);
    echo $rescor;
    echo "</tbody></table></div>";
    # call another python script to obtain correlation picture
    $comtodo ="./pyscript/joinplotfortwo.py ".$dbfs[$chosen]." \"".$nms[$chosen]."\" ".$dbfs[$chosenb]." \"".$nms[$chosenb]."\" \"".$mansel."\"";
    $output = base64_encode(shell_exec($comtodo));
    $chosennms = $nms[$chosen];
    $chosennmsb = $nms[$chosenb];
    # output picture
     echo <<<_imgput
     <div class="div-center" style="text-align:center">
     <img style="display:inline-block; max-width:800px; height:auto" src="data:image/png;base64,$output" alt="Correlation between $chosennms and $chosennmsb"/>
     </div>
_imgput;
    printf("\n");
   }
?>


<?php include_once "footer.php"; ?>
</body>
</html>


