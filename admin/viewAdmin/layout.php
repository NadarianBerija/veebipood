<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.4/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
<?php
if (isset($_SESSION["userId"]) && isset($_SESSION["sessionId"])) {
?>

    <div class="d-flex">
        <nav class="bg-black p-3" style="width: 250px; min-height: 100vh;">
            <h4 class="text-white"><?= htmlspecialchars($_SESSION["name"], ENT_QUOTES, 'UTF-8') ?></h4>
            <p class="text-white"><?= htmlspecialchars($_SESSION["status"], ENT_QUOTES, 'UTF-8') ?></p>
            <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active text-white" href="#">Teosed</a>
            </li>
            <li class="nav-item"> 
                <a class="nav-link text-white" href="#">Slaidid</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Kasutajad</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white" href="../" rel="noopener noreferrer">Veebileht</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="logout">Väljuda</a>
            </li>
            </ul>
        </nav>

<?php
}
?>
        <main>
            <?php echo $content; ?>
        </main>
    </div>
</body>
</html>