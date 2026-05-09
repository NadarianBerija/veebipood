<?php
/** @var array $authors */
/** @var array $categories */
ob_start()
?>

<div class="d-flex flex-column flex-grow-1" style="margin: 30px;">
<h2>Uus teos</h2>
<?php 
if (isset($result)) {
    if ($result[0] == true) {
?>
    <div class="alert alert-success col-12 col-lg-4">
        <strong>Teos on lisatud.</strong><br><br>
        <a href="artsList" class="link-dark">Teostele</a>
    </div>
<?php 
    } else {
?>
    <div class="alert alert-danger col-12 col-lg-4">
        <strong>Töö lisamise viga!</strong>
        <?php if(!empty($result[1])) echo "<br>".$result[1]; ?><br><br>
        <a href="addArt" class="link-dark">Lisamisvorm</a>
    </div>
<?php 
    }
} else {
?>

<form action="addArtResult" method="POST" enctype="multipart/form-data" class="d-flex flex-column w-100" style="max-width: 700px;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    
    <label class="form-label fs-5 fw-semibold">On poes
        <input type="checkbox" name="in_shop" id="toggleExtra">
    </label>

    <div class="extraFields">
        <label class="form-label fs-5 fw-semibold">Hind</label>
        <input type="number" name="price" min="0" step="0.01" class="form-control mb-3 requiredField">
    </div>
    
    <ul class="nav d-flex gap-1 mt-2">
        <li class="nav-item">
            <button class="btn btn-dark active" data-bs-toggle="tab" data-bs-target="#ee" type="button">Eesti</button>
        </li>
        <li class="nav-item">
            <button class="btn btn-dark" data-bs-toggle="tab" data-bs-target="#en" type="button">Inglise</button>
        </li>
        <li class="nav-item">
            <button class="btn btn-dark" data-bs-toggle="tab" data-bs-target="#ru" type="button">Vene</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="card mb-3 p-3 tab-pane fade show active" id="ee">
            <label class="form-label fs-5 fw-semibold">Nimetus (eesti)</label>
            <input type="text" name="title_ee" class="form-control mb-3" required>

            <div class="extraFields">
                <label class="form-label fs-5 fw-semibold">Kirjeldus (eesti)</label>
                <textarea name="desc_ee" class="form-control mb-3 requiredField"></textarea>
            </div>
        </div>

        <div class="card mb-3 p-3 tab-pane fade" id="en">
            <label class="form-label fs-5 fw-semibold">Nimetus (inglise)</label>
            <input type="text" name="title_en" class="form-control mb-3" required>

            <div class="extraFields">
                <label class="form-label fs-5 fw-semibold">Kirjeldus (inglise)</label>
                <textarea name="desc_en" class="form-control mb-3 requiredField"></textarea>
            </div>
        </div>

        <div class="card mb-3 p-3 tab-pane fade" id="ru">
            <label class="form-label fs-5 fw-semibold">Nimetus (vene)</label>
            <input type="text" name="title_ru" class="form-control mb-3" required>

            <div class="extraFields">
                <label class="form-label fs-5 fw-semibold">Kirjeldus (vene)</label>
                <textarea name="desc_ru" class="form-control mb-3 requiredField"></textarea>
            </div>
        </div>
    </div>

    <label class="form-label fs-5 fw-semibold">Kategooria</label>
    <select name="category" class="form-control mb-3" required>
        <option value="" disabled selected>-</option>
        <?php foreach ($categories as $cat) {?>
            <option value="<?= $cat['cat_name'] ?>"><?= $cat['cat_name'] ?></option>
        <?php } ?>
    </select>

    <label class="form-label fs-5 fw-semibold">Author</label>
    <select name="author" class="form-control mb-3" required>
        <option value="" disabled selected>-</option>
        <?php foreach ($authors as $auth) {?>
            <option value="<?= $auth['author_name'] ?>"><?= $auth['author_name'] ?></option>
        <?php } ?>
    </select>

    <input type="file" name="images[]" class="form-control mb-3" id="artImages" multiple accept="image/*">

    <div id="gallery" class="gallery"></div>

    <div class="mt-4">
        <a href="artsList" class="btn btn-dark">&larr; Tagasi</a>
        <button type="submit" name="save" class="btn btn-dark">Salvestada</button>
    </div>
    
</form>

<?php } ?>

</div>
<script src="../admin/public/js/artScripts.js"></script>
<script src="../admin/public/js/addArt.js"></script>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>