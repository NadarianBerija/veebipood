<div class="my-container">
    <div class="back-btn">
        <a href="<?= BASE_URL  ?>/<?= APP_LANG  ?>/gallery/category?id=<?= (int)$currentArt['cat_id']  ?>" class="back-btn">&#11207; <?= htmlspecialchars(Lang::get('back')) ?></a>
    </div>
    <div class="d-flex flex-column justify-content-center align-items-center my-4 mb-5 gap-4">
        <div class="swiper-container">
            <?php if (count($images) > 1) {?>
            <div  class="swiper mySwiper2">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $img) { ?>
                        <div class="swiper-slide">
                            <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($img['art_image'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    <?php } ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            
            <div thumbsSlider="" class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $img) { ?>
                        <div class="swiper-slide">
                            <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($img['art_image'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php } else { ?>
                <img style="width: 100%;" src="<?= BASE_URL ?>/public/<?= htmlspecialchars($images[0]['art_image'], ENT_QUOTES, 'UTF-8') ?>">
            <?php } ?>
        </div>

        <div class="gallery-art-info">
            <h3><?= htmlspecialchars($currentArt['art_title']) ?></h3>
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-circle" style="width: 30px; height: 30px;">
                <?php 
                    if (!empty($currentArt['author_picture'])) {
                        echo '<img style="width: 100%" src="' . BASE_URL . '/public/' . htmlspecialchars($currentArt['author_picture'], ENT_QUOTES, 'UTF-8') . '">';
                    } else {
                        echo '<img style="width: 100%" src="' . BASE_URL . '/public/images/users/user.jpg">';
                    }
                ?>
                </div>
                <p><?= htmlspecialchars($currentArt['author']) ?></p>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/js/swiper-bundle.min.js"></script>
<script src="<?= BASE_URL ?>/public/js/artScripts.js"></script>