<?php
ob_start()
?>

<div style="margin: 30px;">
<h2>Teosed</h2>
<button class="btn btn-dark btn-lg my-3 mx-auto rounded-2" >Lisa uus teos</button>

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
        <a href="" class="btn btn-primary btn-lg rounded-2 my-2 mx-3"><i class="bi bi-pencil-square"></i></a>
        <?php
        if (isset($_SESSION["status"])) {
            if ($_SESSION["status"] === 'admin') { 
        ?>
        <a href="" class="btn btn-danger btn-lg rounded-2 my-2 mx-3"><i class="bi bi-trash-fill"></i></a>
        <?php
            }
        }
        ?>
    </div>
<?php } ?>
</div>
</div>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>