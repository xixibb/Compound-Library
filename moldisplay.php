<?php include "mainheader.php"?>

<?php
$cid = 1;
if(isset($_GET['cid'])) {
  $cid = $_GET['cid'];
}
$db_server = dbset($db_hostname,$db_username,$db_password,$db_database);
$query = "select cp.*, ma.name, smi.smiles from Compounds cp join Manufacturers ma on cp.ManuID=ma.id join Smiles smi WHERE cp.id=$cid and cp.id=smi.cid";
$result = mysql_query($query);
if(!$result) die("unable to process query: " . mysql_error());
$row = mysql_fetch_row($result);
#natm 1| ncar 2| nnit 3| noxy 4| nsul 5| ncycl 6| nhdon 7| nhacc 8| nrotb 9| ManuID 10| catn 11| mw 12| TPSA 13| XLogP 14 
$dbfs = array("natm","ncar","nnit","noxy","nsul","ncycl","TPSA", "nrotb","nhdon","nhacc","mw","XLogP");
$molval = array($row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[13], $row[9], $row[7], $row[8], $row[12], $row[14]);
$liprule = array(5, 10, 500, 5);
$catn = $row[11];
$manu = $row[15];
$smiles = $row[16];
?>


<html>
<head>
<?php include_once 'commondep.php' ?>

<style>
# define check marker by css
# the css template is copied from https://css.gg/check-o and https://css.gg/close
# colors are changed for better visualisation
.gg {
  display:block;
}

.gg-close {
 box-sizing: border-box;
 position: relative;
 display: block;
 transform: scale(var(--ggs,1));
 width: 22px;
 height: 22px;
 border: 2px solid transparent;
 border-radius: 40px
}

.gg-close::after,
.gg-close::before {
 content: "";
 display: block;
 box-sizing: border-box;
 position: absolute;
 width: 16px;
 height: 2px;
 background: #F64C72;
 transform: rotate(45deg);
 border-radius: 5px;
 top: 8px;
 left: 1px
}

.gg-close::after {
 transform: rotate(-45deg)
} 

.gg-close {
    box-sizing: border-box;
    position: relative;
    display: block;
    transform: scale(var(--ggs,1));
    width: 22px;
    height: 22px;
    border: 2px solid #F64C72;
    border-radius: 40px
}

.gg-check-o {
 box-sizing: border-box;
 position: relative;
 display: block;
 transform: scale(var(--ggs,1));
 width: 22px;
 height: 22px;
 border: 2px solid #379583;
 border-radius: 100px
}

.gg-check-o::after {
 content: "";
 display: block;
 box-sizing: border-box;
 position: absolute;
 left: 3px;
 top: -1px;
 width: 6px;
 height: 10px;
 border-color: #379583;
 border-width: 0 2px 2px 0;
 border-style: solid;
 transform-origin: bottom left;
 transform: rotate(45deg)
} 

</style>

<?php 
# get 3D compound visualisation by JSMol
# the JSMol is rendered by getmol_resp.php
?>
<script type="text/javascript" src="jsmol/JSmol.min.js"></script>
<script type="text/javascript"> 
$(document).ready(function() {

Info = {
	width: 300,
	height: 300,
	debug: false,
	j2sPath: "jsmol/j2s",
	color: "0xEDF5E1",
  disableJ2SLoadMonitor: true,
  disableInitialConsole: true,
	addSelectionOptions: false,
        readyFunction: null,
        src: <?php echo "'$webpath/getmol_resp.php?cid=$cid'"?>,
}

$("#mydiv").html(Jmol.getAppletHtml("jmolApplet0",Info))

});
</script>
</head>

<body>
<?php include_once 'menuf.php'; ?>


<div class="div-center">
<h2> <?php echo $catn ?></h2>
</div>

<div class="div-center">
  <div class="div-grid-2">
    <div class="div-table">
    <table id="manutable">
      <thead>
        <tr>
          <th> Property </th>
          <th> Value </th>
          <th> Lipinski </th>
        </tr>
        </thead>
        <tbody>
        <?php
        # get lipniski checklist icons
        # a checker if the lipniski condition is true
        # or a cross if it is false
        $lipval = 0;
        for ($j=8; $j < sizeof($molval); ++$j){
          echo "<tr>";
          echo "<td>".$nms[$j]."</td>";
          echo "<td>".$molval[$j]."</td>";
          if ($molval[$j] < $liprule[$j-8]){ echo "<td><icon class=gg-check-o></icon> </td>";}
          else {
          echo "<td><icon class=gg-close></icon></td>";
          $lipval = $lipval+1;
          }
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
    </div>
    <div style="text-align:center">
      <div style="z-index: 0; position: relative;">
        <span id="mydiv"></span>
      </div>

      <p>
      <a href="javascript:Jmol.script(jmolApplet0, 'spin on')">spin on</a>

      <a href="javascript:Jmol.script(jmolApplet0, 'spin off')">spin off</a>
      </p>
    </div>
  </div>
</div>
<div class="div-center">
  <h3> Representation </h3>
  <div class="div-table">
    <table id="manutable">
     <tbody>
      <tr>
    <td style="background-color: #05386B; color:#EDF5E1"> Supplier </td>
    <td> <?php 
    # get supplier information from database
    $manurl = get_manu_url($manu); echo "<a href=$manurl target='_blank'> $manu </a>"; 
    ?> </td>
    </tr>
     <tr>
     <td style="background-color: #05386B; color:#EDF5E1"> Smiles </td>
     <td><?php echo $smiles ?> </td>
     </tr>
     <tr>
    <td style="background-color: #05386B; color:#EDF5E1"> Standard InChI Key </td>
    <td><?php 
    # get inchikey from cactus.nci.nih.gov
    $convurl = "https://cactus.nci.nih.gov/chemical/structure/".urlencode($smiles)."/stdinchikey";
    $inchikeyresult = file_get_contents($convurl);
    $inchikeysplit = explode("=", $inchikeyresult);
    $inchikey = $inchikeysplit[1];
    echo $inchikey;
     ?> </td></tr><tr>
     <td style="background-color: #05386B; color:#EDF5E1"> PubChem </td>
     <td> 
     <?php 
    # get pubchem link by inchikey and Rest API provided by pubchem.ncbi.nlm.nih.gov 
    $convurl = "https://pubchem.ncbi.nlm.nih.gov/rest/pug/compound/inchikey/".urlencode($inchikey)."/cids/txt";
    $pubchemid = file_get_contents($convurl);
    # by clicking this link, a new tab shows up on user's browser
    echo "<a href='https://pubchem.ncbi.nlm.nih.gov/compound/$pubchemid'  target='_blank'> Link </a>"; 
    ?>
     </td>
    </tr>
    <tr>
    <td style="background-color: #05386B; color:#EDF5E1"> SDF file </td>
    <td><?php 
    # create the link for downloading sdf corresponding to the compound
    echo "<a href='downloadsdf.php?cid=$cid' target='_blank' download='$cid'> Download </a>" 
    ?> </td>
    </tr>
       </tbody>
    </table>
  </div>
</div>

<div class="div-center">
  <h3> Information </h3>
  <div class="div-table">
    <table id="manutable">
     <tbody>
      <?php
      # construct the information table
       for ($j=0; $j<4; ++$j){
         echo("<tr><td style='background-color: #05386B; color: #EDF5E1'>".$nms[$j]."</td><td>".$molval[$j]."</td>");
         echo("<td style='background-color: #05386B; color: #EDF5E1'>".$nms[$j+4]."</td><td>".$molval[$j+4]."</td></tr>");
       }
       ?>
       </tbody>
    </table>
  </div>
</div>

<?php include "footer.php" ?>
</body>


</html>

