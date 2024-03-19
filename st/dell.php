<a href="#" onClick="goclear()" id="button">click event</a>
<script type="text/javascript">
var btn = document.getElementById('button');
function goclear() { 
alert("Handler called. Page will redirect to clear.php");
document.location.href = "clear.php";
};
</script>
<?php
//open file to write
$fp = fopen("baz.dat", "r+");
// clear content to 0 bits
ftruncate($fp, 0);
//close file
fclose($fp);
?>
<?php
header('Location: /remont/');
?>
