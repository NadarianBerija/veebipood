<?php
ob_start()
?>

<div class="slidesContainer">
<?php foreach ($arr as $row) {?>
    <div class="card">
        <div class="userImg card-body">
        <?php if (!empty($row['picture'])) {
            echo '<img src="../public/' . htmlspecialchars($row['picture']) . '">';
        } else {
            echo '<img src="../public/images/users/user.jpg">';
        } ?>
        </div>
        <div class="card-body d-flex flex-column align-items-center">
            <h4><?= htmlspecialchars($row['user_name']) ?></h4>
            <p><?= htmlspecialchars($row['user_status']) ?></p>
        </div>
    </div>
<?php } ?>
</div>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>