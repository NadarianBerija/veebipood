<?php
ob_start()
?>

<div class="d-flex">
    <div class="slidesContainer">
    <?php
    if (!empty($arr)) { 
        foreach($arr as $slide) { 
    ?>
    <div class="card">
        <div class="slide card-body">
            <img src="../public/<?= htmlspecialchars($slide['slide_img']) ?>">
        </div>
    </div>
    <?php 
        }
    } else {
    echo '<p>Slaidid puuduvad</p>';
    }
    ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>