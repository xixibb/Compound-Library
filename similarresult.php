<?php include "mainheader.php" ?>

<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>

<?php
# add header line 
?>
<div class="div-center">
<h2> Top 100 Similar Compounds </h2>
</div>
<div class="div-center">
<form action="similar.php" style="margin-bottom:10px">
<button class="button-hoverable" style="margin-top:10px" type="submit" value="Upload SDF" name="submit"> Re-Upload </button>
</form>
</div>
<div class="div-table">
<?php
# get selected suppliers
$smask = $_SESSION['supmask'];
$mansel = get_mansel($smask);

# get uploaded files
 if(isset($_FILES['fileToUpload']))
 {

# use python script to search top 100 most similar compounds
# python script outputs a html table code
       $file_content=file_get_contents($_FILES['fileToUpload']['tmp_name']);
       $upload_sdf = base64_encode($file_content);
       $comtodo = "./pyscript/similar.py $upload_sdf"." \"".$mansel."\"";
       $result = shell_exec($comtodo);
       echo $result; # echo the outputed html source code
}
?>
</div>
<?php include_once "footer.php"; ?>
</body>
</html>