<div class="my-container">
    <h2><?= htmlspecialchars(Lang::get('shop')) ?></h2>
    <form method="GET" class="d-flex justify-content-end me-3 mb-3">
        <select name="category_id" onchange="this.form.submit()" class="form-select w-auto">
            <option value=""> <?= htmlspecialchars(Lang::get('all_categories')) ?></option>
            <?php foreach ($categories as $category) { ?>
                <option value="<?= (int)$category['category_id'] ?>" <?= ($selectedCategory == (int)$category['category_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['category_name']) ?>
                </option>
                <?php } ?>
        </select>
    </form>

    <?php if (!empty($allArtsShop)) { ?>
        <div class="arts_list">
            <?php foreach ($allArtsShop as $art) { ?>
                <a class="shop_art" href="">
                    <div class="shop_art_img">
                        <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($art['art_image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($art['art_title']) ?>">
                    </div>
                    <div class="shop_art_info">
                        <h3><?= htmlspecialchars($art['art_title']) ?></h3>
                        <p class="text-truncate"><?= htmlspecialchars($art['art_text']) ?></p>
                        <p class="fs-5 fw-bold fst-italic"><?= htmlspecialchars($art['art_price']) ?> €</p>
                    </div>
                </a>
            <?php } ?>
        </div>
    <?php } else {?>
        <p class="text-center my-5"><?= htmlspecialchars(Lang::get('nothing')) ?></p>
    <?php } ?> 
</div>