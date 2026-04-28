<?php
ob_start()
?>

<div class="d-flex">
    <?php
    if (!empty($arr)) { 
        foreach($arr as $slide) { 
    ?>
        <img style="width:200px" src="../public/<?= htmlspecialchars($slide['slide_img']) ?>">
    <?php 
        }
    } else {
    echo '<p>Slaidid puuduvad</p>';
    }
    ?>
</div>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>