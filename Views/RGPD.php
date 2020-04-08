<?php
$file = $_GET['f'];
require("./Views/Common/head.php");
require("./Views/Common/navbar.php");
echo "<div class=container>";
require("./" . $file . ".htm");
echo "</div>";
require("./Views/Common/footer.php");
?>
