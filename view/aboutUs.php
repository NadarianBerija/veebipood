<?php
/**
 * About us page
 */
/** @var array $authPic */
?>

<?php
$pictures = [];

$authPic = $authPic ?? [];
foreach ($authPic as $pic) {
    $pictures[$pic['username']] = $pic['user_pic'];
}
?>

<div class="my-container">
    <h2><?= htmlspecialchars(Lang::get('about')) ?></h2>

    <div class="biography_container">
        <div class="biography_box">
            <div class="auth_img">            
                <img src="<?= !empty($pictures['Illimar Vihmar']) ? BASE_URL . '/public/' . htmlspecialchars($pictures['Illimar Vihmar']) : BASE_URL . '/public/images/users/user.jpg' ?>" alt="Illimar">
            </div>
            <h4><?= htmlspecialchars(Lang::get('Illimar_name')) ?></h4>
            <p><?= nl2br(htmlspecialchars(Lang::get('Illimar_bio'))) ?></p>
        </div>

        <div class="biography_box">
            <div class="auth_img">
                <img src="<?= !empty($pictures['Külli Vihmar']) ? BASE_URL . '/public/' . htmlspecialchars($pictures['Külli Vihmar']) : BASE_URL . '/public/images/users/user.jpg' ?>" alt="Kulli">
            </div>
            <h4><?= htmlspecialchars(Lang::get('Kulli_name')) ?></h4>
            <p><?= nl2br(htmlspecialchars(Lang::get('Kulli_bio'))) ?></p>
        </div>

        <div class="biography_box">
            <div class="auth_img">
                <img src="<?= !empty($pictures['Liis Tasa']) ? BASE_URL . '/public/' . htmlspecialchars($pictures['Liis Tasa']) : BASE_URL . '/public/images/users/user.jpg' ?>" alt="Liis">
            </div>
            <h4><?= htmlspecialchars(Lang::get('Liis_name')) ?></h4>
            <p><?= nl2br(htmlspecialchars(Lang::get('Liis_bio'))) ?></p>
        </div>
    </div>
</div>