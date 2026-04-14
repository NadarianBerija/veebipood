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
            <h1>Tere!</h1>
            <a href="">Kirjuta meil</a>
        </div>
        <div class="hero-bottom-line">
            <p>Saate tellida individuaalse töö vastavalt oma soovidele.</p>
        </div>
    </div>
</div>

<div class="container">
    <div class="cat_container">
        <a href="">
            <div>
                <img src="../public/images/cat_img/maal.png" alt="maal">
                    <div>
                        <p>MAAL</p>
                    </div>
                </img>
            </div>
        </a>
    
        <a href="">
            <div>
                <img src="../public/images/cat_img/illustratsioon.png" alt="illustratsioon">
                    <div>
                        <p>ILLUSTRATSIOON</p>
                    </div>
                </img>
            </div>
        </a>
    
        <a href="">
            <div>
                <img src="../public/images/cat_img/plakat.png" alt="plakat">
                    <div>
                        <p>PLAKAT</p>
                    </div>
                </img>
            </div>
        </a>
    
        <a href="">
            <div>
                <img src="../public/images/cat_img/kujundus.png" alt="kujundus">
                    <div>
                        <p>KUJUNDUS</p>
                    </div>
                </img>
            </div>
        </a>
    
        <a href="">
            <div>
                <img src="../public/images/cat_img/foto.png" alt="foto">
                    <div>
                        <p>FOTO</p>
                    </div>
                </img>
            </div>
        </a>
    
        <a href="">
            <div>
                <img src="../public/images/cat_img/ruum.png" alt="ruum">
                    <div>
                        <p>RUUM</p>
                    </div>
                </img>
            </div>
        </a>
    </div>
</div>
<script src="<?= BASE_URL ?>/public/js/slider.js"></script>
