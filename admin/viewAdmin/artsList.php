<?php
ob_start()
?>

<div class="slidesContainer">
<?php foreach($list as $row) { ?>
    <div class="card">
        <div class="slide card-body">
            <img src="../public/<?= htmlspecialchars($row['art_image']) ?>">
        </div>
        <div class="card-body">
            <p><b>Nimetus: </b><?= htmlspecialchars($row['art_title']) ?></p>
            <p><b>Autor: </b><?= htmlspecialchars($row['author']) ?></p>
            <p><b>Kategooria: </b><?= htmlspecialchars($row['cat_name']) ?></p>
            <?php if ($row['in_shop'] == 1) {
                echo '<p><b>On poes: </b>Jah</p>';
            } else {
                echo '<p><b>On poes: </b>Ei</p>';    
            }?>
        </div>
    </div>
<?php } ?>
</div>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>