<?php
# create two commmonly used arrays
$dbfs = array("natm","ncar","nnit","noxy","nsul","ncycl","TPSA", "nrotb","nhdon","nhacc","mw","XLogP");
$nms = array("Number of Atoms","Number of Carbons","Number of Nitrogens","Number of Oxygens","Number of Sulphurs","Number of Cycles","Top. Polar Surface Area", "Number of Rotatable Bonds", "Number of Hydrogen Donors","Number of Hydrogen Acceptors","Moleculer Weight (u)","Estimated LogP");
?>

<?php
# get webiste directory
require_once 'websitedir.php';
#get session
include_once 'session.php';
#get the page redirected if not login
include_once 'redir.php';
#get the database access credentials
require_once 'login.php';
#get the utility functions
require_once 'phpscript/utilfuncs.php';
?>

<!-- create loading mask with class overlay. It is hidden by default.
 once a button is pressed, then this overlay frame should come up to prevent users to do further actions
 also, there is a loading icon animation shows in the center
 to achieve these two things, use javascript function to automatically adjust style of .overlay 
 please refer to commondep.php for the javascript function -->
 
<div id="overlay" style="display:none;">
  <div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
</div>





