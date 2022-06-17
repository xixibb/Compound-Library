<?php include "mainheader.php"?>

<?php
$chosen = -1;
if(isset($_POST['tgval'])) {
$tgval = $_POST['tgval'];
$chosen = get_chosen($tgval, $dbfs);
} ?>

<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>

<div class="div-center">
<h2> View Statistics for Selected Property</h2>
</div>
<div class="div-center" style="margin-top: 35px;">
  <form action="p3.php" method="post" id="selectProperty">
    <select name="tgval" style="height: 30px;" required>
      <option style="text-align:center" value="">---- Select Property ----</option>

<!-- display options in the select list -->
<?php
for($j = 0 ; $j <sizeof($dbfs) ; ++$j) {
$ifchoose = "";
if ($chosen==$j) {$ifchoose="selected";}
printf('<option value="%s" %s>%15s</option>',$dbfs[$j],  $ifchoose, $nms[$j]);
  echo "\n";
} 
?>


</select>
<br>
<button class="button-hoverable" type="submit" style="margin-top:20px;"> OK </button>
</form>
</div>

<?php
if(isset($_POST['tgval'])){
    #Calculate Statistics for the selected property
    #Calculated Statistics: Average, Standard Deviation, Maximum, Minimum, Total Number
    printf("<div class='div-table'><table id='manutable' style='table-layout: fixed'>");
    printf("<tr> <th>Statistics</th> <th>  %s </th></tr>", $nms[$chosen]);

     $db_server = dbset($db_hostname,$db_username,$db_password,$db_database);
     $query = sprintf("select COUNT(%s), AVG(%s), STD(%s), MAX(%s), MIN(%s)  from Compounds",$dbfs[$chosen],$dbfs[$chosen], $dbfs[$chosen],$dbfs[$chosen],$dbfs[$chosen]);
     $smask = $_SESSION['supmask'];
     $mansel = get_mansel($smask);
     $query = $query." where ".$mansel;
     $result = mysql_query($query);
     if(!$result) die("unable to process query: " . mysql_error());
     $row = mysql_fetch_row($result);
     printf("<tr><td> Total Compounds </td><td> %d </td></tr>", $row[0]);
     printf("<tr><td> Average </td><td> %f </td></tr>", $row[1]);
     printf("<tr><td> Std. Deviation </td><td> %f </td></tr>", $row[2]);
     printf("<tr><td> Maxmum </td><td> %f </td></tr>", $row[3]);
     printf("<tr><td> Minimum </td><td> %f </td></tr>", $row[4]);
     printf("</table></div>");
   }

?>
<?php include_once "footer.php"; ?>
</body>
</html>


