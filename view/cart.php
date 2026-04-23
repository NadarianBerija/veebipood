<div class="my-container">
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
                                <h6><?= htmlspecialchars($item['art_title']) ?></h6>
                                <p><?= htmlspecialchars($item['art_price']) ?> €</p>
                            </div>
                        </div>
                        <a class="mx-3 text-danger" href="<?= BASE_URL ?>/<?= APP_LANG ?>/cart/remove?id=<?= (int)$item['art_id'] ?>"><?= htmlspecialchars(Lang::get('remove')) ?></a>
                    </div>
                </div>
                <?php } ?>
            </div>
            <form class="col-12 col-md-4 d-flex flex-column">
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

                <button type="submit" name="order" class="btn btn-dark btn-lg rounded-2 mt-2">
                    <?= htmlspecialchars(Lang::get('order_btn')) ?>
                </button>
            </form>
        </div>
    <?php } ?>
</div>