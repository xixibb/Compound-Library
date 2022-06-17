<?php
# get webiste directory
require_once 'websitedir.php';
#get session
include_once 'session.php';
#get the database access credentials
require_once 'login.php';
#get the utility functions
require_once 'phpscript/utilfuncs.php';
?>

<?php
# if get post login information
# then set up session to store these names
if(isset($_POST['fn']) &&
   isset($_POST['sn']))
  {
    $_SESSION['forname'] = $_POST['fn'];
    $_SESSION['surname'] = $_POST['sn'];
    } else { 
    # if there is no session information, redirect to login page
    if(!(isset($_SESSION['forname']) &&
     isset($_SESSION['surname']))){
  header("location: $webpath/complib.php");
  }
}

# the below is for displaying loading icon
# overlay is initially not showing
# A ready function at the end of this script will make overlay visible after clicking search button
?>
<div id="overlay" style="display:none;">
  <div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
</div>

<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>



<?php
if (isset($_GET['noresult'])){
echo "<script type='text/javascript'>alert('No matched compound.');</script>";
}

$smask =  $_SESSION['supmask'];
$fn = $_SESSION['forname'];
$sn = $_SESSION['surname']; 
    
echo <<< _WELCOME
<div class="div-center" style="text-align:center">
<h2> Welcome to the CompLib </h2>
<h3 style="font-family: Trebuchet MS;
  font-weight: heavy; margin-top:50px"> Complib is a compound database integrating various search tools to help finding target compound. </h3>
</div>
<div class="div-center" style="text-align:center">

<style>
/* a pure css search icon copied from https:"//css.gg/search" */

 .gg-search {
 box-sizing: border-box;
 position: relative;
 display: block;
 transform: scale(var(--ggs,1));
 width: 16px;
 height: 16px;
 border: 2px solid;
 border-radius: 100%;
 margin-left: -4px;
 margin-top: -4px
}

.gg-search::after {
 content: "";
 display: block;
 box-sizing: border-box;
 position: absolute;
 border-radius: 3px;
 width: 2px;
 height: 8px;
 background: currentColor;
 transform: rotate(-45deg);
 top: 10px;
 left: 12px
} 
</style>

<?php
# create search bar 
?>

<div style="display:inline-block; margin-top:10%">
<form action="searchredir.php">
<input type="text" placeholder="  Search Smiles, Compound Catalogue ..." name ="search" style="height:40px; width:500px;border-radius:30px">
<button type="submit" class="button-hoverable" id="showloading"> <i class="gg-search"></i></button>
<p style="font-size:12px">
<i> e.g. [H]OC(=O)C([H])([H])C1([H])(C(=O)N(C(=O)N1([H]))C([H])([H])C([H])([H])C([H])([H])[H]), SPH1-096-606 </i></p>
</form>
</div>
</div>

_WELCOME;

# add footer
include_once "footer.php";


# the ready function used to show overlay loading icon after clicking button
echo <<< _END
</body>
<script>
$(document).ready(function() {
  $('#showloading').click(function(){
            $('#overlay').fadeIn();
        });
    });
</script>
</html>
_END;
?>
