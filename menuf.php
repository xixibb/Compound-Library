<?php
require_once 'websitedir.php';
include_once 'topbanner.php';
include_once 'supplierselector.php';
?>

<div>
<style>


.topnav {
  display: block;
  height: 50px;
  background-color: #5CDB95;
  color: #05386B;
  font-weight: bold;
  min-width: 1000px;
}

.topnav a {
  float: left;
  display: inline-block;
  position: relative;
  text-align: center;
  padding: 16px 16px;
  text-decoration: none;
  font-size: 16px;
}

.topnav a:hover {
  background-color: #EDF5E1;
}

.topnav a.active {
  background-color: #04AA6D;
}

/* Style The Dropdown Button */
.dropbtn {
  background-color: #5CDB95;
  float: left;
  color: #05386B;
  font-weight: bold;
  height: 50px;
  padding: 14px 16px;
  font-size: 16px;
  border: none;
  text-decoration: none;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  height: 50px;
  float: left;
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  margin-top: 50px;
  background-color: #EDF5E1;
  min-width: 100px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: #05386B;
  font-weight: bold;
  min-width: 100px;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {
background-color: #F64C72;
color: #EDF5E1;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
  display: block;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
  background-color: #EDF5E1;
}
</style>
<?php
# add buttons using dropdown style
?>
  <div  class="topnav">
    <a class="mulink" href="<?php echo $webpath ?>/index.php" style="float: left;color:#05386B"> Home </a>
    <div class="dropdown">
    <button class="dropbtn" disabled>Suppliers</button>
    <div class="dropdown-content">
    <a href="<?php echo $webpath ?>/p1.php"> Select </a>
    <a href="<?php echo $webpath ?>/dbdownload.php"> Download </a>
    </div>
    </div>
    <div class="dropdown">
    <button class="dropbtn" disabled>Overview</button>
    <div class="dropdown-content">
    <a href="<?php echo $webpath ?>/p3.php"> Statistics </a>
    <a href="<?php echo $webpath ?>/p3a.php"> Histogram </a>
    <a href="<?php echo $webpath ?>/p4.php"> Correlations </a>
    </div>
    </div>
     <div class="dropdown">
    <button class="dropbtn" disabled>Search</button>
    <div class="dropdown-content">
    <a href="<?php echo $webpath ?>/p2smilesex.php"> Compounds </a>
    <a href="<?php echo $webpath ?>/p10a.php"> Properties </a>
    </div>
    </div>
     <div class="dropdown">
    <button class="dropbtn" disabled>Tools</button>
    <div class="dropdown-content">
    <a href="similar.php"> Similarity </a>
    
    </div>
    </div>
    <?php
    # two buttons are aligned to the right
    # different from all other four buttons which are aligned to the left
    ?>
    <a class="mulink" href="<?php echo $webpath ?>/p5.php" style="float: right;color:#05386B"> Logout </a>
    <a class="mulink" href="<?php echo $webpath ?>/phelp.php" style="float: right;color:#05386B"> Help </a>
  </div>
</div>
