<?php include "mainheader.php" ?>

<html>
<head>
<?php include_once 'commondep.php'; ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>

<?php
# connect to database and get selected suppliers
$db_server = dbset($db_hostname,$db_username,$db_password,$db_database);
$manarray = array();
$manid = array();
$mask = $_SESSION['supmask'];
$masql = get_mansel($mask);
get_manarray($manarray,$manid);

# get input form information
if (($_POST['tgval'] != "") && ($_POST['cval']!="") && ($_POST['sortedorder']!="")) {
  $mychoice=get_post('tgval');
  $myvalue=get_post('cval');
  $mysort=get_post('sortedorder');
  $choicestr="<option value='' selected>---- Select Property ----</option>";
  $sortstr="<option value='nosort' selected> -- Default Order -- </option>";

  # build sql query for the task
  # at the same time this constructs html code needed to display the current search parameters
  $compsel = "select * from Compounds where ";
  if($mychoice == "mw") {
    $compsel = $compsel."( mw > ".($myvalue - 1.0)." and  mw < ".($myvalue + 1.0).")";
    $choicestr = "<option value='mw' selected>Molecular Weight</option>";
  }
  if($mychoice == "TPSA") {
    $compsel = $compsel."( TPSA > ".($myvalue - 0.1)." and  TPSA < ".($myvalue + 0.1).")";
    $choicestr = '<option value="TPSA" selected>Topological Polar Surface Area</option>';
  }
  if($mychoice == "XlogP") {
    $compsel = $compsel."( XlogP > ".($myvalue - 0.1)." and  XlogP < ".($myvalue + 0.1).")";
    $choicestr = '<option value="XlogP" selected>Estimated logP</option>';
  }
  $compsel = $compsel." and ".$masql;
  if ($mysort != "nosort") {
    if ($mysort == "DESC"){
      $sortstr='<option value="DESC" selected> Descend </option>';
    } else {
      $sortstr='<option value="ASC" selected> Ascend </option>';
    }
  
    $compsel = $compsel."ORDER BY $mychoice $mysort LIMIT 10000";
  }
  $result = mysql_query($compsel);
  if(!$result) die("unable to process query: " . mysql_error());

# show the information the user have inputted
# also display a "Edit Search" button for re-search with different parameters
echo '<div class="div-center" style="text-align:center">';
echo '<div class="div-grid-2" style="grid-template-columns:1fr 3fr; margin-top:20px">';
echo '<div style="margin:auto; text-align:center">';
echo "<form action='p10a.php'>";
echo '<button class="button-hoverable" type="submit" value=""> Edit Search </button></form></div>';
echo '<div style="margin-top:10px"><select name="d" style="height: 30px;" disabled>';
echo $choicestr;
echo "</select><input style='height: 30px;' type='number' step='any' name='cval' value='$myvalue' disabled />";
echo " <select name='sortedorder' style='height: 30px;' disabled>$sortstr</select>";
echo "</div> </div></div>";
    
$rows = mysql_num_rows($result);

# output the result table
# the table has four columns row_id, CAT number, Manufacturer, Property
      echo<<<TABLESET_
<div class="div-table" style="margin-top:0;">
<table id="manutable" class="tableCanSort">
 <thead>
  <tr>
    <th> </th>
    <th>CAT Number</th>
    <th>Manufacturer</th>
    <th>Property ($mychoice)</th>
  </tr>
   </thead>
   <tbody>
TABLESET_;
      for($j = 0 ; $j < $rows ; ++$j)
	{
	  echo "<tr>";
	  $row = mysql_fetch_row($result);
     # create link to single molecule information page
	  printf("<td>%d</td><td><a href=moldisplay.php?cid=%s>%s</a></td> <td>%s</td>", $j+1, $row[0],$row[11],$manarray[$row[10] - 1]);
	  if($mychoice == "mw") {
	     printf("<td>%s</td> ", $row[12]);
	  }
	  if($mychoice == "TPSA") {
	     printf("<td>%s</td> ", $row[13]);
	  }
	  if($mychoice == "XlogP") {
	     printf("<td>%s</td> ", $row[14]);
	  }     
          echo "</tr>";
	}
      echo "</tbody></table></div>";
  } else {
    echo "No Query Given\n";
  }
?>


<?php 
# add footer information
include_once "footer.php"; 
?>

</body>

</html>

