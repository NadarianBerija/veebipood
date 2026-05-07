<?php
/** @var array $artData */
/** @var array $categories */
/** @var array $authors */
ob_start();

$langs = [];
foreach($artData['langs'] as $l){
    $langs[$l['code']] = $l;
}
?>

<div class="d-flex flex-column flex-grow-1" style="margin: 30px;">
<h2>Muuta teos</h2>
<?php 
if (isset($result)) {
    if ($result[0] == true) {
?>
    <div class="alert alert-success w-25">
        <strong>Teos on muudetud.</strong><br><br>
        <a href="artsList" class="link-dark">Teostele</a>
    </div>
<?php 
    } else {
?>
    <div class="alert alert-danger w-25">
        <strong>Töö muutmise viga!</strong>
        <?php if(!empty($result[1])) echo "<br>".$result[1]; ?><br><br>
        <a href="editArt?id=<?= (int)$artData['art']['id'] ?>" class="link-dark">Muutmisvorm</a>
    </div>
<?php 
    }
} else {
?>

<form action="editArtResult?id=<?= (int)$artData['art']['id'] ?>" method="POST" enctype="multipart/form-data" class="d-flex flex-column w-100" style="max-width: 700px;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <label class="form-label fs-5 fw-semibold">On poes
        <input type="checkbox" name="in_shop" id="toggleExtra" <?= $artData['art']['in_shop'] ? 'checked' : '' ?>>
    </label>

    <div class="extraFields">
        <label class="form-label fs-5 fw-semibold">Hind</label>
        <input type="number" name="price" min="0" step="0.01" class="form-control mb-3 requiredField" value="<?= htmlspecialchars($artData['art']['price']) ?>">
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
            <input type="text" name="title_ee" class="form-control mb-3" required value="<?= htmlspecialchars($langs['ee']['title']) ?>">

            <div class="extraFields">
                <label class="form-label fs-5 fw-semibold">Kirjeldus (eesti)</label>
                <textarea name="desc_ee" class="form-control mb-3 requiredField"><?= htmlspecialchars($langs['ee']['text']) ?></textarea>
            </div>
        </div>

        <div class="card mb-3 p-3 tab-pane fade" id="en">
            <label class="form-label fs-5 fw-semibold">Nimetus (inglise)</label>
            <input type="text" name="title_en" class="form-control mb-3" required value="<?= htmlspecialchars($langs['en']['title']) ?>">

            <div class="extraFields">
                <label class="form-label fs-5 fw-semibold">Kirjeldus (inglise)</label>
                <textarea name="desc_en" class="form-control mb-3 requiredField"><?= htmlspecialchars($langs['en']['text']) ?></textarea>
            </div>
        </div>

        <div class="card mb-3 p-3 tab-pane fade" id="ru">
            <label class="form-label fs-5 fw-semibold">Nimetus (vene)</label>
            <input type="text" name="title_ru" class="form-control mb-3" required value="<?= htmlspecialchars($langs['ru']['title']) ?>">

            <div class="extraFields">
                <label class="form-label fs-5 fw-semibold">Kirjeldus (vene)</label>
                <textarea name="desc_ru" class="form-control mb-3 requiredField"><?= htmlspecialchars($langs['ru']['text']) ?></textarea>
            </div>
        </div>
    </div>

    <label class="form-label fs-5 fw-semibold">Kategooria</label>
    <select name="category" class="form-control mb-3" required>
        <option value="" disabled selected>-</option>
        <?php foreach ($categories as $cat) {?>
            <option value="<?= $cat['cat_name'] ?>" <?= $cat['cat_id'] == $artData['art']['category_id'] ? 'selected' : '' ?>><?= $cat['cat_name'] ?></option>
        <?php } ?>
    </select>

    <label class="form-label fs-5 fw-semibold">Author</label>
    <select name="author" class="form-control mb-3" required>
        <option value="" disabled selected>-</option>
        <?php foreach ($authors as $auth) {?>
            <option value="<?= $auth['author_name'] ?>" <?= $auth['author_name'] == $artData['art']['username'] ? 'selected' : '' ?>><?= $auth['author_name'] ?></option>
        <?php } ?>
    </select>

    <input type="file" name="images[]" class="form-control mb-3" id="artImages" multiple accept="image/*">

    <div id="gallery" class="gallery" data-existing-images='<?= json_encode($artData['images'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>'></div>

    <div class="mt-4">
        <a href="artsList" class="btn btn-dark">&larr; Tagasi</a>
        <button type="submit" name="save" class="btn btn-dark">Salvestada</button>
    </div>
    
</form>

<?php } ?>

</div>
<script src="../admin/public/js/artScripts.js"></script>
<script src="../admin/public/js/editArt.js"></script>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>