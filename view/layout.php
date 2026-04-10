<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>VIHMART</title>
</head>
<body>
    <div class="wrapper">
        <header>
            <div class="container">
                <a href="">
                    <img class="logo" src="../public/images/site/logo.png" alt="logo">
                </a>

                <nav class="header_menu">
                    <ul class="menu_links">
                        <li><a href="">Avaleht</a></li>
                        <li><a href="">Pood</a></li>
                        <li><a href="">Meist</a></li>
                        <li><a href="">Kontakt</a></li>
                    </ul>

                    <div class="line"></div>

                    <ul class="cat_gallery">
                        <li><a href="">MAAL</a></li>
                        <li><a href="">ILLUSTRATSIOON</a></li>
                        <li><a href="">PLAKAT</a></li>
                        <li><a href="">KUJUNDUS</a></li>
                        <li><a href="">FOTO</a></li>
                        <li><a href="">RUUM</a></li>
                    </ul>
                </nav>

                <div>
                    <a href="">EESTI</a>
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

        <footer>
            <div class="container">
                <nav class="footer_menu">
                    <ul class="menu_links">
                        <li><a href="">Avaleht</a></li>
                        <li><a href="">Pood</a></li>
                        <li><a href="">Meist</a></li>
                        <li><a href="">Kontakt</a></li>
                    </ul>
                    <span class="diagonal">/</span>
                    <ul class="cat_gallery">
                        <li><a href="">MAAL</a></li>
                        <li><a href="">ILLUSTRATSIOON</a></li>
                        <li><a href="">PLAKAT</a></li>
                        <li><a href="">KUJUNDUS</a></li>
                        <li><a href="">FOTO</a></li>
                        <li><a href="">RUUM</a></li>
                    </ul>
                </nav>

                <div>
                    <a href="">EESTI</a>
                </div>
                <p>&copy; Vihmart 2026. Kõik õigused reserves.</p>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>