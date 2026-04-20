<div class="my-container">
    <div class="d-flex justify-content-center gap-4 my-4 mb-5">
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

        <div style="width: 300px;">
            <h3 class="fs-2 mt-4"><?= htmlspecialchars($currentArt['art_title']) ?></h3>
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
            <p class="fs-5"><?= htmlspecialchars($currentArt['art_text']) ?></p>
            <p class="fs-3 fw-bold fst-italic"><?= htmlspecialchars($currentArt['art_price']) ?> € </p>
            <a class="btn btn-dark btn-lg rounded-2" href="<?= BASE_URL ?>/<?= APP_LANG ?>/order?id=<?= (int)$currentArt['art_id'] ?>"><?= htmlspecialchars(Lang::get('order_btn')) ?></a>
        </div>
    </div>
</div>