<div class="my-container">
    <?php if (empty($items)) { ?>
        <p class="text-center my-5"><?= htmlspecialchars(Lang::get('empty_cart')) ?></p>
    <?php } else { ?>
        <?php foreach ($items as $item) { ?>
            <div>
                <h4><?= htmlspecialchars($item['art_title']) ?></h4>
                <p><?= htmlspecialchars($item['art_price']) ?> €</p>

                <a href="<?= BASE_URL ?>/<?= APP_LANG ?>/cart/remove?id=<?= (int)$item['art_id'] ?>"><?= htmlspecialchars(Lang::get('remove')) ?></a>
            </div>
        <?php } ?>
    <?php } ?>
</div>