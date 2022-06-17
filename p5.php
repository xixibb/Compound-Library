<?php include "mainheader.php"?>
<html>
<head>
<?php include_once 'commondep.php' ?>
</head>
<body>
<?php include_once 'menuf.php'; ?>

<?php
$fn = $_SESSION['forname'];
# say goodbye to user
echo <<<_MAIN1
    <div class='div-center'>
    <h2> Goodbye,  $fn </h2>
    <h3 style="text-align:center"> You have now exited Complib </h3>
    </div>
_MAIN1;

# clean session
$_SESSION = array();
if( session_id() != "" || isset($_COOKIE[session_name()]))
  setcookie(session_name(), '', time() - 2592000, '/');
  session_destroy();
?>

<?php include 'footer.php' ?>
</body>
</html>

