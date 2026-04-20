<div class="my-container">
    <h2><?= htmlspecialchars(Lang::get('gallery')) ?></h2>

    <h3 class="fs-3 mb-3 mt-0"><?= htmlspecialchars($category['category_name']) ?></h3>

    <?php if (!empty($arts)) { ?>
    <div class="arts_list">
        <?php foreach ($arts as $art) { ?>
        <a class="gallery_art" href="<?= BASE_URL ?>/<?= APP_LANG ?>/gallery/art?id=<?= (int)$art['art_id'] ?>">
            <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($art['art_image'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="overlay">
                <h3><?= htmlspecialchars($art['art_title'], ENT_QUOTES, 'UTF-8') ?></h3>
            </div>
        </a>
        <?php } ?>
    </div>

    <?php Pagination::Pagination($totalPages, $page, 'gallery/category', (int)$category['category_id']) ?>

    <?php } else {?>
        <p class="text-center my-5"><?= htmlspecialchars(Lang::get('nothing')) ?></p>
    <?php } ?>


</div>