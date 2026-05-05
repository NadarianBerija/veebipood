<?php
ob_start()
?>

<div class="d-flex flex-column flex-grow-1" style="margin: 30px;">
<h2>Uus kasutaja</h2>
<?php 
if (isset($result)) {
    if ($result[0] == true) {
?>
    <div class="alert alert-success w-25">
        <strong>Kasutaja on lisatud.</strong><br><br>
        <a href="artsList" class="link-dark">Kasutajatele</a>
    </div>
<?php 
    } else {
?>
    <div class="alert alert-danger w-25">
        <strong>Kasutaja lisamise viga!</strong>
        <?php if(!empty($result[1])) echo "<br>".$result[1]; ?><br><br>
        <a href="addUser" class="link-dark">Lisamisvorm</a>
    </div>
<?php 
    }
} else {
?>

<form action="addUserResult" method="POST" enctype="multipart/form-data" class="d-flex flex-column w-100" style="max-width: 700px;">
    <label class="form-label fs-5 fw-semibold">Kasutajanimi</label>
    <input type="text" name="name" class="form-control mb-3" required>

    <label class="form-label fs-5 fw-semibold">Kasutajatunnus</label>
    <input type="text" name="login" class="form-control mb-3" required>

    <label class="form-label fs-5 fw-semibold">Parool</label>
    <input type="password" name="password" class="form-control mb-3" required>

    <label class="form-label fs-5 fw-semibold">Kinnita parool</label>
    <input type="password" name="confirm" class="form-control mb-3" required>

    <label class="form-label fs-5 fw-semibold">Status</label>
    <select name="status" class="form-control mb-3" required>
        <option value="admin">admin</option>
        <option value="moderaator">moderaator</option>
    </select>

    <label class="form-label fs-5 fw-semibold">Kasutajate pilt</label>
    <input type="file" name="picture" class="form-control mb-3">

    <div class="mt-4">
        <a href="users" class="btn btn-dark">&larr; Tagasi</a>
        <button type="submit" name="save" class="btn btn-dark">Salvestada</button>
    </div>
    
</form>

<?php } ?>

</div>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>