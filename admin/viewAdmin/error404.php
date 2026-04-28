<?php
ob_start()
?>

<h2>404 Error</h2>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>