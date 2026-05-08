<?php
/** @var array $authors */
/** @var array $categories */
/** @var array $list */
ob_start()
?>

<div style="margin: 30px;">
<h2>Teosed</h2>
<a href="addArt" class="btn btn-dark btn-lg my-3 mx-auto rounded-2" >Lisa uus teos</a>
<form class="d-flex flex-wrap gap-3 mb-3" method="GET" action="artsList" id="filterForm">
    <div>
        <label class="form-label fw-semibold">Autor</label>
        <select class="form-control" name="author" onchange="this.form.submit()">
            <option value="">Kõik</option>
            <?php foreach ($authors as $auth) { ?>
                <option value="<?= (int)$auth['author_id'] ?>"
                    <?= (isset($_GET['author']) && $_GET['author'] == $auth['author_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($auth['author_name']) ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div>
        <label class="form-label fw-semibold">Kategooria</label>
        <select class="form-control" name="category" onchange="this.form.submit()">
            <option value="">Kõik</option>
            <?php foreach ($categories as $cat) { ?>
                <option value="<?= (int)$cat['cat_id'] ?>"
                    <?= (isset($_GET['category']) && $_GET['category'] == $cat['cat_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['cat_name']) ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div>
        <label class="form-label fw-semibold">On poes</label>
        <select class="form-control" name="in_shop" onchange="this.form.submit()">
            <option value="">Kõik</option>
            <option value="1" <?= (isset($_GET['in_shop']) && $_GET['in_shop'] == '1') ? 'selected' : '' ?>>Jah</option>
            <option value="0" <?= (isset($_GET['in_shop']) && $_GET['in_shop'] == '0') ? 'selected' : '' ?>>Ei</option>
        </select>
    </div>

    <div>
        <label class="form-label fw-semibold">Kustutatud</label>
        <select class="form-control" name="is_deleted" onchange="this.form.submit()">
            <option value="">Kõik</option>
            <option value="1" <?= (isset($_GET['is_deleted']) && $_GET['is_deleted'] == '1') ? 'selected' : '' ?>>Jah</option>
            <option value="0" <?= (isset($_GET['is_deleted']) && $_GET['is_deleted'] == '0') ? 'selected' : '' ?>>Ei</option>
        </select>
    </div>
</form>

<?php if (!empty($list)) { ?>
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
        <a href="editArt?id=<?= $row['art_id'] ?>" class="btn btn-primary btn-lg rounded-2 my-2 mx-3"><i class="bi bi-pencil-square"></i></a>
        <button  class="toggle-delete btn btn-primary btn-lg rounded-2 my-2 mx-3 <?= $row['is_deleted'] ? 'btn-success' : 'btn-warning' ?>" data-id="<?= $row['art_id'] ?>"><i class="bi <?= $row['is_deleted'] ? 'bi-arrow-counterclockwise' : 'bi-trash' ?>"></i></button>
        <?php
        if (isset($_SESSION["status"])) {
            if ($_SESSION["status"] === 'admin') { 
        ?>
        <a href="deleteArt?id=<?= $row['art_id'] ?>" class="btn btn-danger btn-lg rounded-2 my-2 mx-3"><i class="bi bi-trash-fill"></i></a>
        <?php
            }
        }
        ?>
    </div>
<?php } ?>
</div>
<?php } else {?>
    <p class="text-center my-5">Pole midagi</p>
<?php } ?>
</div>

<div class="modal fade" id="warningModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Hoiatus</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                <p id="warningText"></p>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Sulge
                </button>
            </div>

        </div>
    </div>
</div>

<script src="../admin/public/js/toggleDelete.js"></script>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>