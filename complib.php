<?php 
require_once 'websitedir.php';
#get session
include_once 'session.php';
#get the page redirected if not login
require_once 'login.php';
#get the utility functions
require_once 'phpscript/utilfuncs.php';
?>

<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<?php

include_once 'topbanner.php';
echo "<body>";

# get build supmask to indicate which suppliers are chosen
# supmask converts from binary code whose length equals to the number of suppliers
# a supplier is chosen if and only if the corresponding binary code is 1
# initially all suppliers are chosen
$db_server = dbset($db_hostname,$db_username,$db_password, $db_database);
$query = "select * from Manufacturers";
     $result = mysql_query($query);
     if(!$result) die("unable to process query: " . mysql_error());
     $rows = mysql_num_rows($result);
     $mask = 0;
     mysql_close($db_server);
     for($j = 0 ; $j < $rows ; ++$j)
     {
       $mask = (2 * $mask) + 1;
     }
$_SESSION['supmask'] = $mask;

# create validation function to check if both names are given
   echo <<<_EOP
<script>
   function validate(form) {
   fail = ""
   if(form.fn.value =="") fail = "Must Give Forname "
   if(form.sn.value == "") fail += "Must Give Surname"
   if(fail =="") return true
       else {alert(fail); return false}
   }
</script>

<!-- create name input form -->

<form action="index.php" method="post" onSubmit="return validate(this)">
<div class="div-center">
<div class="div-grid-2"  style="width:350px; margin-top:100px">
<div style="display:inline-block; margin-right:30px"> <h2 style="text-align:left;font-size:25px"> Forename </h2> </div>
 <div style="display:inline-block; margin:auto">  <input type="text" name="fn"/></div>
 <div style="display:inline-block; margin-right:30px"><h2 style="text-align:left;font-size:25px"> Surname </h2> </div>
 <div style="display:inline-block; margin:auto"> <input type="text" name="sn"/> </div>
</div>
 <button class="button-hoverable" style="margin-top:50px" type="submit" value="go"> Go </button>
 </div>
</form>
_EOP;

# inlcude footer
include_once "footer.php";
?>


</body>
</html>


