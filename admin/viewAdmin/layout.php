<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin dashboard</title>
    <link href="public/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/icons/bootstrap-icons.css" rel="stylesheet">
    <script src="public/js/Sortable.min.js"></script>
</head>
<body>
<?php
if (isset($_SESSION["userId"]) && isset($_SESSION["sessionId"])) {
?>

    <button class="btn d-lg-none m-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminMobileSidebar">
        <span class="bg-span"></span>
        <span class="bg-span"></span>
        <span class="bg-span"></span>
    </button>

    <div class="d-flex">
        <nav class="d-none d-lg-flex flex-column bg-black text-white admin-sidebar">
            <div class="d-flex flex-column text-center align-items-center mt-3">
                <h4 class="text-white"><?= htmlspecialchars($_SESSION["name"], ENT_QUOTES, 'UTF-8') ?></h4>
                <p class="text-white"><?= htmlspecialchars($_SESSION["status"], ENT_QUOTES, 'UTF-8') ?></p>
            </div>

            <div class="line"></div>

            <ul class="nav flex-column">
            <?php 
            if (isset($_SESSION["status"])) {
                if ($_SESSION["status"] === 'admin') { 
            ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="artsList">Teosed</a>
                </li>
                <li class="nav-item"> 
                    <a class="nav-link text-white" href="heroSlides">Slaidid</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="users">Kasutajad</a>
                </li>
            <?php } elseif ($_SESSION["status"] === 'moderaator') { ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="artsList">Teosed</a>
                </li>
                <li class="nav-item"> 
                    <a class="nav-link text-white" href="heroSlides">Slaidid</a>
                </li>
            <?php 
                } 
            }
            ?>
            
            <div class="line"></div>

            <li class="nav-item">
                <a class="nav-link text-white" href="../" rel="noopener noreferrer">Veebileht</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="logout">Väljuda</a>
            </li>
            </ul>
        </nav>


        <main class="flex-grow-1">
            <?php
            if (isset($_SESSION["status"]) && ($_SESSION["status"]=="admin" || $_SESSION["status"]=="moderaator")) {
                echo $content; 
            } else {
                echo '<div style="margin: 30px;">
                    <h4>Teil ei ole õigusi!</h4>
                    </div>';  
            }?>
        </main>
    </div>

    <div class="offcanvas offcanvas-start bg-black text-white" tabindex="-1" id="adminMobileSidebar">
        <div class="offcanvas-header">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">

            <div class="d-flex flex-column text-center align-items-center mt-3">
                <h4 class="text-white"><?= htmlspecialchars($_SESSION["name"], ENT_QUOTES, 'UTF-8') ?></h4>
                <p class="text-white"><?= htmlspecialchars($_SESSION["status"], ENT_QUOTES, 'UTF-8') ?></p>
            </div>

            <div class="line"></div>

            <ul class="nav flex-column">
                <?php if ($_SESSION["status"] === 'admin') { ?>
                    <li><a class="nav-link text-white" href="artsList">Teosed</a></li>
                    <li><a class="nav-link text-white" href="heroSlides">Slaidid</a></li>
                    <li><a class="nav-link text-white" href="users">Kasutajad</a></li>
                <?php } elseif ($_SESSION["status"] === 'moderaator') { ?>
                    <li><a class="nav-link text-white" href="artsList">Teosed</a></li>
                    <li><a class="nav-link text-white" href="heroSlides">Slaidid</a></li>
                <?php } ?>

                <div class="line"></div>

                <li><a class="nav-link text-white" href="../">Veebileht</a></li>
                <li><a class="nav-link text-white" href="logout">Väljuda</a></li>
            </ul>

        </div>
    </div>
<?php
}
?>
    <script src="public/js/bootstrap.bundle.min.js"></script>

</body>
</html>