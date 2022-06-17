<?php include "mainheader.php" ?>
<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>

<div class="div-center">
<h2> Search By Property </h2>
<p style="font-size:16px; text-align:center"><i> Only top 10,000 Compounds will be retrieved. </i></p>
</div>

<?php
# Create search form
# the form has two selectors, one for choosing property, the other for choosing order
# there is a input form at the middle of two selectors
# input form allows user to input a number
?>

<div class="div-center" style="margin-top: 35px;">
<form action="p10bjsml.php" method="post" id="selectProperty">
<select name="tgval" style="height: 30px;" required>
        <option value="">---- Select Property ----</option>
        <option value="mw">Molecular Weight</option>
        <option value="TPSA">Topological Polar Surface Area</option>
        <option value="XlogP">Estimated logP</option>
    </select>
    <input style="height: 30px;" type="number" step="any" name="cval" required/>
    <select name="sortedorder" style="height: 30px;">
        <option value="nosort"> -- Default Order -- </option>
        <option value="DESC">Descend</option>
        <option value="ASC">Ascend</option>
    </select>
    <br>
    <button class="button-hoverable" type="submit" style="margin-top:20px;"> Submit </button>
</form>
</div>

<?php include_once "footer.php"; ?>
</body>
</html>
