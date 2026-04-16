<div class="my-container">
    <h2><?= htmlspecialchars(Lang::get('contact')) ?></h2>

    <p class="mx-auto mb-5 text-center fs-5 text-muted lh-base" style="color: #555;"><?= htmlspecialchars(Lang::get('phrase')) ?></p>

    <div class="d-flex justify-content-center flex-wrap gap-4">
        <div class="contact-card">
            <h4><?= htmlspecialchars(Lang::get('Illimar_name')) ?></h4>
            <div class="my-3">
                <span class="skill"><?= htmlspecialchars(Lang::get('painting')) ?></span>
                <span class="skill"><?= htmlspecialchars(Lang::get('illustration')) ?></span>
                <span class="skill"><?= htmlspecialchars(Lang::get('poster')) ?></span>
                <span class="skill"><?= htmlspecialchars(Lang::get('design')) ?></span>
            </div>
            <a href="mailto:illimar@gmail.com">illimar@gmail.com</a>
        </div>

        <div class="contact-card">
            <h4><?= htmlspecialchars(Lang::get('Kulli_name')) ?></h4>
            <div class="my-3">
                <span class="skill"><?= htmlspecialchars(Lang::get('painting')) ?></span>
                <span class="skill"><?= htmlspecialchars(Lang::get('illustration')) ?></span>
            </div>
            <a href="mailto:kulli@gmail.com">kulli@gmail.com</a>
            <div class="d-flex align-items-center justify-content-center gap-1 mt-2">
                <img style="width: 18px;" src="<?= BASE_URL ?>/public/images/site/inst_icon.png" alt="inst_icon">
                <a class="text-decoration-none fw-medium" style="color:#d62976;" href="https://www.instagram.com/mana_joonistused" target="_blank" rel="noopener noreferrer">@mana_joonistused</a>
            </div>
        </div>
        
        <div class="contact-card">
            <h4><?= htmlspecialchars(Lang::get('Liis_name')) ?></h4>
            <div class="my-3">
                <span class="skill"><?= htmlspecialchars(Lang::get('photo')) ?></span>
            </div>
            <a href="mailto:liis@gmail.com">liis@gmail.com</a>
            <div class="d-flex align-items-center justify-content-center gap-1 mt-2">
                <img style="width: 18px;" src="<?= BASE_URL ?>/public/images/site/inst_icon.png" alt="inst_icon">
                <a class="text-decoration-none fw-medium" style="color:#d62976;" href="https://www.instagram.com/tasa.photography" target="_blank" rel="noopener noreferrer">@tasa.photography</a>
            </div>
        </div>
    </div>
</div>