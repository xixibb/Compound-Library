<?php include "mainheader.php" ?>
<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>


<div class="div-center">
  <h2> Download Chemical Table File </h2>
</div>
<div class="div-center">
  <table id="manutable">
  <tr><th>Manufacturer</th><th>Website</th><th><?php echo '#Compounds' ?></th><th>Link</th>
  <?php 
  $db_server = dbset($db_hostname,$db_username,$db_password,$db_database);
  $query = "select M.*, stat.cnum from Manufacturers M join (select ManuID, count(*) as cnum  from Compounds group by ManuID) stat on M.id = stat.ManuID";
  $result = mysql_query($query);
  if(!$result) die("unable to process query: " . mysql_error());  
  $rows = mysql_num_rows($result);
  
  for ($j=0; $j<$rows; ++$j){
    $row = mysql_fetch_row($result);
    $dlurl = "sdffile/".$row[1].".sdf";
    echo <<< _TABLEROW
      <tr>
        <td> $row[1]</td>
        <td> <a href=$row[2] target="_blank"> $row[2] </a></td>
        <td> $row[3]</td>
        <td> <a href="$dlurl" target="_blank" download> Download </a> </td>
      </tr>
_TABLEROW;
  }
  ?>
  </table>
</div>

<?php include_once "footer.php" ?>
</body>
</html>