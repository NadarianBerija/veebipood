<div class="my-container">
    <h2><?= htmlspecialchars(Lang::get('shop')) ?></h2>
    <form action="">
        <select name="" id="">
            <option value="Kõik"></option>
            <option value="Maal"></option>
            <option value="Illustratsioon"></option>
            <option value="Plakat"></option>
            <option value="Kujundus"></option>
            <option value="Foto"></option>
            <option value="Ruum"></option>
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
        <p class="text-center my-5">nothing</p>
    <?php } ?> 
</div>