<?php include "mainheader.php" ?>

<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>

<div class="div-center"  style="text-align:center;margin-bottom:20px">
<h2> Search Similar Compounds </h2>
<i> Retreive top 100 most similar compounds in CompLib to your compound</i>
</div>

<?php
# Create form for user to upload sdf 
?>

<form action="similarresult.php" method="post" enctype="multipart/form-data">
<div class="div-center" style="font-size:15px;">
  <div class="div-grid-2" style="text-align:center; background-color:#05386B; color:#EDF5E1; border-radius:25px; width:600px">
  <div>
  <h3  style="float:right"> Select SDF file to upload </h3> </div>
  <div style="display:inline-block">
  <input style="margin-top:15px" type="file" name="fileToUpload" id="fileToUpload"></div>
  </div>
  </div>
  <button class="button-hoverable" style="margin-top:25px" type="submit" value="Upload SDF" name="submit" id='showloading'> Submit </button>
</form>

<?php include_once "footer.php"; ?>
</body>

</html>