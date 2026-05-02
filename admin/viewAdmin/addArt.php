<?php
ob_start()
?>

<div class="d-flex flex-column flex-grow-1" style="margin: 30px;">
<h2>Uus teos</h2>

<form action="" class="d-flex flex-column w-100" style="max-width: 700px;">
    <label class="form-label fs-5 fw-medium">On poes
        <input type="checkbox" name="in_shop">
    </label>

    <label class="form-label fs-5 fw-medium">Hind</label>
    <input type="number" name="price" min="0" step="0.01" class="form-control mb-3">
    

    <button type="button">Eesit</button>
    <button type="button"> Inglise</button>
    <button type="button">Vene</button>

    <div class="d-flex flex-column">
        <label class="form-label fs-5 fw-medium">Nimetus (eesti)</label>
        <input type="text" name="title_ee" class="form-control mb-3" required>

        <label class="form-label fs-5 fw-medium">Kirjeldus (eesti)</label>
        <textarea name="desc_ee" class="form-control mb-3"></textarea>
    </div>

    <div class="d-flex flex-column">
        <label class="form-label fs-5 fw-medium">Nimetus (inglise)</label>
        <input type="text" name="title_en" class="form-control mb-3" required>

        <label class="form-label fs-5 fw-medium">Kirjeldus (inglise)</label>
        <textarea name="desc_en" class="form-control mb-3"></textarea>
    </div>

    <div class="d-flex flex-column">
        <label class="form-label fs-5 fw-medium">Nimetus (vene)</label>
        <input type="text" name="title_ru" class="form-control mb-3" required>

        <label class="form-label fs-5 fw-medium">Kirjeldus (vene)</label>
        <textarea name="desc_ru" class="form-control mb-3"></textarea>
    </div>

    <label class="form-label fs-5 fw-medium">Kategooria</label>
    <select name="category" class="form-control mb-3" required>
        <option value="" disabled selected>-</option>
        <?php foreach ($categories as $cat) {?>
            <option value="<?= $cat['cat_name'] ?>"><?= $cat['cat_name'] ?></option>
        <?php } ?>
    </select>

    <label class="form-label fs-5 fw-medium">Author</label>
    <select name="author" class="form-control mb-3" required>
        <option value="" disabled selected>-</option>
        <?php foreach ($authors as $auth) {?>
            <option value="<?= $auth['author_name'] ?>"><?= $auth['author_name'] ?></option>
        <?php } ?>
    </select>
    
</form>
</div>
<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>