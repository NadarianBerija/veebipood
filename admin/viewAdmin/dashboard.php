<?php
ob_start()
?>

<h1>Welcome!</h1>

<?php
$content = ob_get_clean();
?>

<?php
include "viewAdmin/layout.php";
?>