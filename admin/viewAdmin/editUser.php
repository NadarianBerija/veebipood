<?php
/**
 * Admin form to edit an existing user.
 */
/** @var array $detail */
ob_start();
?>

<div class="d-flex flex-column flex-grow-1" style="margin: 30px;">
<h2>Andmete muutmine</h2>
<?php 
if (isset($result)) {
    if ($result[0] == true) {
?>
    <div class="alert alert-success col-12 col-lg-4">
        <strong>Kasutaja andmed on muudetud.</strong><br><br>
        <a href="users" class="link-dark">Kasutajatele</a>
    </div>
<?php 
    } else {
?>
    <div class="alert alert-danger col-12 col-lg-4">
        <strong>Viga!</strong>
        <?php if(!empty($result[1])) echo "<br>".$result[1]; ?><br><br>
        <a href="editUser?id=<?= (int)$detail['user_id'] ?>" class="link-dark">Muutmisvorm</a>
    </div>
<?php 
    }
} else {
?>

<form action="editUserResult?id=<?= (int)$detail['user_id'] ?>" method="POST" enctype="multipart/form-data" class="d-flex flex-column w-100" style="max-width: 700px;">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <label class="form-label fs-5 fw-semibold">Muuta parool
        <input type="checkbox" name="changePassword" id="toggleExtra">
    </label>

    <div class="card mb-3 p-3 extraFields">
        <label class="form-label fs-5 fw-semibold">Vana parool</label>
        <input type="password" name="oldPassword" class="form-control mb-3 requiredField">

        <label class="form-label fs-5 fw-semibold">Uus parool</label>
        <input type="password" name="newPassword" class="form-control mb-3 requiredField">

        <label class="form-label fs-5 fw-semibold">Kinnita parool</label>
        <input type="password" name="confirmPassword" class="form-control mb-3 requiredField">
    </div>

    <label class="form-label fs-5 fw-semibold">Kasutajanimi</label>
    <input type="text" name="name" class="form-control mb-3" required value="<?= htmlspecialchars($detail['user_name']) ?>">

    <label class="form-label fs-5 fw-semibold">Kasutajatunnus</label>
    <input type="text" name="login" class="form-control mb-3" required value="<?= htmlspecialchars($detail['user_login']) ?>">

    <?php if ((int)$detail['user_id'] !== 1) {?>
    <label class="form-label fs-5 fw-semibold">Status</label>
    <select name="status" class="form-control mb-3" required>
        <option value="admin" <?= $detail['user_status'] == 'admin' ? 'selected' : '' ?>>admin</option>
        <option value="moderaator" <?= $detail['user_status'] == 'moderaator' ? 'selected' : '' ?>>moderaator</option>
    </select>
    <?php } else {?>
    <label class="form-label fs-5 fw-semibold">Status</label>
	<select name="status" class="form-control mb-3" required>
	    <option value="admin" <?= $detail['user_status'] == 'admin' ? 'selected' : '' ?>>admin</option>
    </select>
	<?php } ?>

        
    <label class="form-label fs-5 fw-semibold">Vana pild</label>
    <?php if (!empty($detail['picture'])) { ?>
    <img src="../public/<?= htmlspecialchars($detail['picture']) ?>" width="150px">
    <?php } else { ?>
    <img src="../public/images/users/user.jpg" width="150px">
    <?php } ?>

    <input type="file" name="picture" class="form-control mb-3" id="picture">

    <div class="mt-4">
        <a href="users" class="btn btn-dark">&larr; Tagasi</a>
        <button type="submit" name="save" class="btn btn-dark">Salvestada</button>
    </div>
    
</form>

<?php } ?>

</div>
<script src="../admin/public/js/artScripts.js"></script>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>