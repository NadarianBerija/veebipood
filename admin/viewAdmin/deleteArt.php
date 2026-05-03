<?php
ob_start();

$langs = [];
foreach($artData['langs'] as $l){
    $langs[$l['code']] = $l;
}
?>

<div class="d-flex flex-column flex-grow-1" style="margin: 30px;">
<h2>Eemalda teos</h2>
<?php 
if (isset($result)) {
    if ($result[0] == true) {
?>
    <div class="alert alert-success w-25">
        <strong>Teos on eemaldatud.</strong><br><br>
        <a href="artsList" class="link-dark">Teostele</a>
    </div>
<?php 
    } else {
?>
    <div class="alert alert-danger w-25">
        <strong>Töö eemaldamine viga!</strong>
        <?php if(!empty($result[1])) echo "<br>".$result[1]; ?><br><br>
        <a href="deleteArt?id=<?= (int)$artData['art']['id'] ?>" class="link-dark">Muutmisvorm</a>
    </div>
<?php 
    }
} else {
?>

<form action="deleteArtResult?id=<?= (int)$artData['art']['id'] ?>" method="POST" enctype="multipart/form-data" class="d-flex flex-column w-100" style="max-width: 700px;">  

    <div class="tab-content">
        <div class="card mb-3 p-3 tab-pane fade show active" id="ee">
            <label class="form-label fs-5 fw-semibold">Nimetus (eesti)</label>
            <input type="text" name="title_ee" class="form-control mb-3" required value="<?= htmlspecialchars($langs['ee']['title']) ?>" readonly>
        </div>
    </div>

    <label class="form-label fs-5 fw-semibold">Kategooria</label>
    <select name="category" class="form-control mb-3" required disabled>
        <option value="" disabled selected>-</option>
        <?php foreach ($categories as $cat) {?>
            <option value="<?= $cat['cat_name'] ?>" <?= $cat['cat_id'] == $artData['art']['category_id'] ? 'selected' : '' ?>><?= $cat['cat_name'] ?></option>
        <?php } ?>
    </select>

    <label class="form-label fs-5 fw-semibold">Author</label>
    <select name="author" class="form-control mb-3" required disabled>
        <option value="" disabled selected>-</option>
        <?php foreach ($authors as $auth) {?>
            <option value="<?= $auth['author_name'] ?>" <?= $auth['author_name'] == $artData['art']['username'] ? 'selected' : '' ?>><?= $auth['author_name'] ?></option>
        <?php } ?>
    </select>

    <div class="gallery">
        <?php foreach($artData['images'] as $img) { ?>
        <div class="gallery-item">
            <img src="../public/<?= htmlspecialchars($img['image']) ?>">
        </div>
        <?php } ?>
    </div>

    <div class="mt-4">
        <a href="artsList" class="btn btn-dark">&larr; Tagasi</a>
        <button type="submit" name="delete" class="btn btn-dark">Eemalda</button>
    </div>
    
</form>

<?php } ?>

</div>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>