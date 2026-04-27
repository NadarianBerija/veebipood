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
    <div class="mx-auto my-5" style="width: 500px;">
        <form action="login" method="POST">
            <h3>Sisestage oma andmed</h3>
            <input type="text" name="login" placeholder="Login" class="form-control mb-3">
            <input type="password" name="password" placeholder="Parool" class="form-control mb-3">
            <button type="submit" class="btn btn-dark btn-lg rounded-2 mt-2">Siseneda</button>
        </form>
    </div>
</body>
</html>