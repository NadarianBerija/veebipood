<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/public/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>VIHMART</title>
</head>
<body>
    <div class="d-flex flex-column min-vh-100">
        <header class="d-flex">
            <div class="my-container">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="<?= BASE_URL ?>/<?= APP_LANG ?>/">
                        <img class="logo" src="<?= BASE_URL ?>/public/images/site/logo.png" alt="logo">
                    </a>

                    <nav class="d-none d-xl-flex flex-column align-items-center gap-2">
                        <ul class="menu_links d-flex mb-0 gap-3 text-uppercase">
                            <li><a href="<?= BASE_URL ?>/<?= APP_LANG ?>/"><?= htmlspecialchars(Lang::get('home')) ?></a></li>
                            <li><a href="<?= BASE_URL ?>/<?= APP_LANG ?>/shop"><?= htmlspecialchars(Lang::get('shop')) ?></a></li>
                            <li><a href="<?= BASE_URL ?>/<?= APP_LANG ?>/aboutUs"><?= htmlspecialchars(Lang::get('about')) ?></a></li>
                            <li><a href="<?= BASE_URL ?>/<?= APP_LANG ?>/contact"><?= htmlspecialchars(Lang::get('contact')) ?></a></li>
                        </ul>

                        <div class="line"></div>

                        <ul class="cat_gallery d-flex mb-0 gap-3 text-uppercase">
                            <?php foreach (Controller::AllCategory() as $cat) {?>
                                <li><a href="<?= BASE_URL ?>/<?= APP_LANG ?>/gallery/category?id=<?= (int)$cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></a></li>
                            <?php } ?>
                        </ul>
                    </nav>

                    <?php 
                        $path = trim($GLOBALS['path'] ?? '');
                        $query = trim($GLOBALS['query'] ?? '');
                    ?>

                    <?php
                    $languages = [
                        'ee' => ['name' => 'EESTI', 'flag' => 'ee.png'],
                        'en' => ['name' => 'ENGLISH', 'flag' => 'en.png'],
                        'ru' => ['name' => 'РУССКИЙ', 'flag' => 'ru.png'],
                    ];

                    $currentLang = $GLOBALS['lang'] ?? 'ee';

                    $menuLanguages = $languages;
                    unset($menuLanguages[$currentLang]);
                    ?>

                    <div class="dropdown d-none d-xl-block">
                        <button class="btn btn-outline-dark dropdown-toggle d-flex align-items-center" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                            <?= htmlspecialchars($languages[$currentLang]['name']) ?>
                            <img class="flag ms-2"
                            src="<?= BASE_URL ?>/public/images/site/flags/<?= htmlspecialchars($languages[$currentLang]['flag']) ?>"
                            alt="<?= htmlspecialchars($currentLang) ?>">
                        </button>

                        <ul class="dropdown-menu">
                            <?php foreach ($menuLanguages as $code => $lang) { 
                                $url = BASE_URL . '/' . $code . '/' . $path;
                                if (!empty($query)) {
                                    $url .= '?' . $query;
                                }
                            ?>
                            <li>
                                <a class="dropdown-item d-flex align-items-center"
                                href="<?= htmlspecialchars($url) ?>">

                                <?= htmlspecialchars($lang['name']) ?>

                                <img class="flag ms-2"
                                    src="<?= BASE_URL ?>/public/images/site/flags/<?= htmlspecialchars($lang['flag']) ?>"
                                    alt="<?= htmlspecialchars($code) ?>">
                                </a>
                            </li>
                            <?php } ?>

                        </ul>
                    </div>
                    <button class="btn d-xl-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                        <span class="bg-span"></span>
                        <span class="bg-span"></span>
                        <span class="bg-span"></span>
                    </button>
                </div>
            </div>
        </header>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
            <div class="offcanvas-header">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column gap-4">

                <ul class="d-flex flex-column gap-3 text-uppercase ps-0">
                    <li><a style="color:black;" href="<?= BASE_URL ?>/<?= APP_LANG ?>/"><?= htmlspecialchars(Lang::get('home')) ?></a></li>
                    <li><a style="color:black;" href="<?= BASE_URL ?>/<?= APP_LANG ?>/shop"><?= htmlspecialchars(Lang::get('shop')) ?></a></li>
                    <li><a style="color:black;" href="<?= BASE_URL ?>/<?= APP_LANG ?>/aboutUs"><?= htmlspecialchars(Lang::get('about')) ?></a></li>
                    <li><a style="color:black;" href="<?= BASE_URL ?>/<?= APP_LANG ?>/contact"><?= htmlspecialchars(Lang::get('contact')) ?></a></li>
                </ul>

                <div class="line"></div>

                <ul class="d-flex flex-column gap-3 text-uppercase ps-0"">
                    <?php foreach (Controller::AllCategory() as $cat) { ?>
                        <li>
                            <a
                            href="<?= BASE_URL ?>/<?= APP_LANG ?>/gallery/category?id=<?= (int)$cat['category_id'] ?>" style="color:black;">
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>

                <div class="line"></div>

                <ul class="d-flex flex-column gap-3 text-uppercase ps-0">
                    <?php foreach ($languages as $code => $lang) { 
                        $url = BASE_URL . '/' . $code . '/' . $path;
                        if (!empty($query)) $url .= '?' . $query;
                    ?>
                        <li>
                            <a class="d-flex align-items-center"
                            style="color:black;"
                            href="<?= htmlspecialchars($url) ?>">

                                <?= htmlspecialchars($lang['name']) ?>

                                <img class="flag ms-2"
                                    src="<?= BASE_URL ?>/public/images/site/flags/<?= htmlspecialchars($lang['flag']) ?>">
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

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
                            <li><a href="<?= BASE_URL ?>/<?= APP_LANG ?>/"><?= htmlspecialchars(Lang::get('home')) ?></a></li>
                            <li><a href="<?= BASE_URL ?>/<?= APP_LANG ?>/shop"><?= htmlspecialchars(Lang::get('shop')) ?></a></li>
                            <li><a href="<?= BASE_URL ?>/<?= APP_LANG ?>/aboutUs"><?= htmlspecialchars(Lang::get('about')) ?></a></li>
                            <li><a href="<?= BASE_URL ?>/<?= APP_LANG ?>/contact"><?= htmlspecialchars(Lang::get('contact')) ?></a></li>
                        </ul>

                        <span class="diagonal">/</span>
                        <span class="vartical-line"></span>

                        <ul class="cat_gallery d-flex mb-0 gap-2 text-uppercase">
                            <?php foreach (Controller::AllCategory() as $cat) {?>
                                <li><a href="<?= BASE_URL ?>/<?= APP_LANG ?>/gallery/category?id=<?= (int)$cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></a></li>
                            <?php } ?>
                        </ul>
                    </nav>

                    <ul class="lang-footer d-flex flex-row gap-3 mb-0">
                        <?php foreach ($languages as $code => $lang) { 
                            $url = BASE_URL . '/' . $code . '/' . $path;
                            if (!empty($query)) $url .= '?' . $query;
                        ?>
                            <li>
                                <a class="d-flex align-items-center"
                                style="color:black;"
                                href="<?= htmlspecialchars($url) ?>">

                                    <?= htmlspecialchars($lang['name']) ?>

                                    <img class="flag"
                                        src="<?= BASE_URL ?>/public/images/site/flags/<?= htmlspecialchars($lang['flag']) ?>">
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <p class="copyright">&copy; Vihmart 2026. <?= htmlspecialchars(Lang::get('copyright')) ?>.</p>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>