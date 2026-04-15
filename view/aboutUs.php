<div class="my-container">
    <h2><?= htmlspecialchars(Lang::get('about')) ?></h2>

    <div class="biography_container">
        <div class="biography_box">
            <div class="auth_img">
                <img src="<?= BASE_URL ?>/public/images/users/user.jpg" alt="Illimar">
            </div>
            <h4><?= htmlspecialchars(Lang::get('Illimar_name')) ?></h4>
            <p><?= nl2br(htmlspecialchars(Lang::get('Illimar_bio'))) ?></p>
        </div>

        <div class="biography_box">
            <div class="auth_img">
                <img src="<?= BASE_URL ?>/public/images/users/user.jpg" alt="Kulli">
            </div>
            <h4><?= htmlspecialchars(Lang::get('Kulli_name')) ?></h4>
            <p><?= nl2br(htmlspecialchars(Lang::get('Kulli_bio'))) ?></p>
        </div>

        <div class="biography_box">
            <div class="auth_img">
                <img src="<?= BASE_URL ?>/public/images/users/user.jpg" alt="Liis">
            </div>
            <h4><?= htmlspecialchars(Lang::get('Liis_name')) ?></h4>
            <p><?= nl2br(htmlspecialchars(Lang::get('Liis_bio'))) ?></p>
        </div>
    </div>
</div>