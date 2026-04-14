<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>VIHMART</title>
</head>
<body>
    <div class="d-flex flex-column min-vh-100">
        <header class="d-flex">
            <div class="my-container">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="">
                        <img class="logo" src="<?= BASE_URL ?>/public/images/site/logo.png" alt="logo">
                    </a>

                    <nav class="d-flex flex-column align-items-center gap-2">
                        <ul class="menu_links d-flex mb-0 gap-3 text-uppercase">
                            <li><a href="">Avaleht</a></li>
                            <li><a href="">Pood</a></li>
                            <li><a href="">Meist</a></li>
                            <li><a href="">Kontakt</a></li>
                        </ul>

                        <div class="line"></div>

                        <ul class="cat_gallery d-flex mb-0 gap-3 text-uppercase">
                            <li><a href="">MAAL</a></li>
                            <li><a href="">ILLUSTRATSIOON</a></li>
                            <li><a href="">PLAKAT</a></li>
                            <li><a href="">KUJUNDUS</a></li>
                            <li><a href="">FOTO</a></li>
                            <li><a href="">RUUM</a></li>
                        </ul>
                    </nav>

                    <div class="position-relative d-inline-block">
                        <a href=""><img class="flag" src="<?= BASE_URL ?>/public/images/site/flags/ee.png" alt="ee">EESTI</a>
                    </div>
                </div>
            </div>
        </header>

        <section>
            <?php 
            if(isset($content)) {
                echo $content;
            } else {
                echo '<p>Content not found</p>';
            }
            ?>
        </section>

        <footer class="d-flex">
            <div class="my-container">
                <div class="d-flex flex-column align-items-center mx-auto">
                    <nav class="d-flex align-items-center gap-2 mb-3">
                        <ul class="menu_links d-flex mb-0 gap-2 text-uppercase">
                            <li><a href="">Avaleht</a></li>
                            <li><a href="">Pood</a></li>
                            <li><a href="">Meist</a></li>
                            <li><a href="">Kontakt</a></li>
                        </ul>

                        <span class="diagonal">/</span>

                        <ul class="cat_gallery d-flex mb-0 gap-2 text-uppercase">
                            <li><a href="">MAAL</a></li>
                            <li><a href="">ILLUSTRATSIOON</a></li>
                            <li><a href="">PLAKAT</a></li>
                            <li><a href="">KUJUNDUS</a></li>
                            <li><a href="">FOTO</a></li>
                            <li><a href="">RUUM</a></li>
                        </ul>
                    </nav>

                    <ul class="lang-footer d-flex flex-row gap-3 mb-0">
                        <li><a href="">EESTI<img class="flag" src="<?= BASE_URL ?>/public/images/site/flags/ee.png" alt="ee"></a></li>
                        <li><a href="">ENGLISH<img class="flag" src="<?= BASE_URL ?>/public/images/site/flags/en.png" alt="en"></a></li>
                        <li><a href="">РУССКИЙ<img class="flag" src="<?= BASE_URL ?>/public/images/site/flags/ru.png" alt="ru"></a></li>
                    </ul>
                    <p class="copyright">&copy; Vihmart 2026. Kõik õigused reserves.</p>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>