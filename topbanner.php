<?php
require_once 'websitedir.php';
# this creates the logo on the top
echo <<<_TOPBANNER
<div>
<style>

.title-bar {
  height: 100px;
  line-height: 100px;
  text-align: center;
  font-weight: bold;
  font-size: 45px;
  min-width: 800px;
  background-color: #05386B;
  color: #EDF5E1;
}


</style>
<div class="title-bar">

<?php  
# allow user to go back to home by clicking the logo
?>

<a class="mulink" href="$webpath/index.php"> CompLib </a>
</div>
</div>
_TOPBANNER;
?>
