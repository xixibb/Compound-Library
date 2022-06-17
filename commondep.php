<!-- load my global css -->
<link href="style/mybasic.css" rel="stylesheet" type="text/css">
<!--  load jQuery to allow defining events by functions -->
<script type="text/javascript" src="javascript/jquery-3.3.1.min.js"></script>
<!--  load tablesorter to sort tables -->
<script type="text/javascript" src="javascript/jquery.tablesorter.min.js"></script>


<!--The following script define two functions
the function on the top is to enable tablesorter which is a method imported from jquery.tablesorter
the function on the bottom is to enable a temporary mask and spanner when submitting form but takes time to load new page-->

<script>
$(function() {
  $(".tableCanSort").tablesorter();
});

$(document).ready(function() {
  $('#showloading').click(function(){
            $('#overlay').fadeIn();
        });
    });
</script>