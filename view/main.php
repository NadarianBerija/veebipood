<?php
$heroImages = Controller::AllHeroSlides();
if (is_array($heroImages)) {
    shuffle($heroImages);
}
?>

<div id="hero-slider">
    <?php foreach($heroImages as $index => $image){ ?>
        <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>"data-bg="<?= BASE_URL ?>/public/<?= htmlspecialchars($image['hero_slide'], ENT_QUOTES, 'UTF-8') ?>"></div>
    <?php } ?>
    <div class="hero-slide-content">
        <div class="hero-main">
            <h1><?= htmlspecialchars(Lang::get('hello')) ?></h1>
            <a href="<?= BASE_URL ?>/<?= APP_LANG ?>/contact"><?= htmlspecialchars(Lang::get('write_us')) ?></a>
        </div>
        <div class="hero-bottom-line">
            <p><?= htmlspecialchars(Lang::get('phrase')) ?></p>
        </div>
    </div>
</div>

<div class="my-container">
    <div class="cat_container">
        <?php foreach(Controller::AllCategory() as $cat) { ?>
        <a href="<?= BASE_URL ?>/<?= APP_LANG ?>/gallery/category?id=<?= (int)$cat['category_id'] ?>">
            <div class="cat_block">
                <img class="w-100 d-block object-fit-cover mx-auto" src="<?= BASE_URL ?>/public/<?= htmlspecialchars($cat['cat_img']) ?>">
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <p style="color: black;"><?= htmlspecialchars($cat['category_name']) ?></p>
                    </div>
            </div>
        </a>
        <?php } ?>
    </div>
</div>
<script src="<?= BASE_URL ?>/public/js/slider.js"></script>
