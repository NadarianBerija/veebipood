<?php
/** @var array $items */
/** @var float $total */
?>
<div class="my-container">
    <?php 
    if (isset($result)) {
        if ($result[0] == true) {
    ?>
        <div class="alert alert-success col-12 col-lg-4 mx-auto">
            <strong><?= htmlspecialchars(Lang::get('send_order')) ?></strong><br><br>
            <a href="<?= BASE_URL ?>/<?= APP_LANG ?>/shop" class="link-dark"><?= htmlspecialchars(Lang::get('shop')) ?></a>
        </div>
    <?php 
        } else {
    ?>
        <div class="alert alert-danger col-12 col-lg-4 mx-auto">
            <strong>Viga!</strong>
            <?php if(!empty($result[1])) echo "<br>".$result[1]; ?><br><br>
            <a href="<?= BASE_URL ?>/<?= APP_LANG ?>/cart" class="link-dark"><?= htmlspecialchars(Lang::get('cart')) ?></a>
        </div>
    <?php 
        }
    } else {
    ?>
    <h2><?= htmlspecialchars(Lang::get('cart')) ?></h2>
    <?php if (empty($items)) { ?>
        <p class="text-center my-5"><?= htmlspecialchars(Lang::get('empty_cart')) ?></p>
    <?php } else { ?>
        <div class="row g-4">
            <div class="col-12 col-md-8 d-flex flex-column gap-2">
                <?php foreach ($items as $item) { ?>
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <img style="width: 70px; border-radius: 5px;" src="<?= BASE_URL ?>/public/<?= htmlspecialchars($item['art_image'], ENT_QUOTES, 'UTF-8') ?>">
                            <div>
                                <h5><?= htmlspecialchars($item['art_title']) ?></h5>
                                <p class="fst-italic fs-5"><?= htmlspecialchars($item['art_price']) ?> €</p>
                            </div>
                        </div>
                        <a class="mx-3 text-danger" href="<?= BASE_URL ?>/<?= APP_LANG ?>/cart/remove?id=<?= (int)$item['art_id'] ?>"><?= htmlspecialchars(Lang::get('remove')) ?></a>
                    </div>
                </div>
                <?php } ?>
                <p class="fs-4 fw-bold fst-italic text-end mt-3"><?= htmlspecialchars(Lang::get('total')) ?>: <?= number_format($total, 2) ?> €</p>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/<?= APP_LANG ?>/order" class="col-12 col-md-4 d-flex flex-column">
                <label class="form-label fs-5 fw-medium">
                    <?= htmlspecialchars(Lang::get('your_name')) ?> *
                </label>
                <input type="text" name="name" class="form-control mb-3" required>

                <label class="form-label fs-5 fw-medium">
                    <?= htmlspecialchars(Lang::get('email')) ?> *
                </label>
                <input type="email" name="email" class="form-control mb-3" required>

                <label class="form-label fs-5 fw-medium">
                    <?= htmlspecialchars(Lang::get('phone')) ?>
                </label>
                <input type="tel" name="phone" class="form-control mb-3">

                <label class="form-label fs-5 fw-medium">
                    <?= htmlspecialchars(Lang::get('message')) ?>
                </label>
                <textarea name="message" class="form-control mb-3" rows="5" style="resize: none;"></textarea>

                <input type="text" name="website" style="display:none">

                <button type="submit" id="submitBtn" name="order" class="btn btn-dark btn-lg rounded-2 mt-2" data-sending-text="<?= htmlspecialchars(Lang::get('sending')) ?>">
                    <span id="btnText">
                        <?= htmlspecialchars(Lang::get('order_btn')) ?>
                    </span>

                    <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    <?php 
        } 
    }
    ?>
</div>

<script src="<?= BASE_URL ?>/public/js/spinner.js"></script>