<div class="my-container">
<span class="breadcrumbs">
  <a href="<?= BASE_URL ?>/<?= APP_LANG ?>/"><?= htmlspecialchars(Lang::get('home')) ?></a> /
  <a href="<?= BASE_URL ?>/<?= APP_LANG ?>/shop"><?= htmlspecialchars(Lang::get('shop')) ?></a> /
  <a href="<?= BASE_URL ?>/<?= APP_LANG ?>/shop?category_id=<?= (int)$art['cat_id'] ?>"><?= htmlspecialchars($art['cat_name']) ?></a> /
  <a href="<?= BASE_URL ?>/<?= APP_LANG ?>/shop/art?id=<?=  (int)$art['art_id'] ?>"><?= htmlspecialchars($art['art_title']) ?></a> /
  <?= htmlspecialchars(Lang::get('order')) ?>
</span>

<form class="mx-auto mt-4" style="max-width: 500px;">

    <div class="card mb-4">
      <div class="card-body">

        <p class="mb-2">
          <strong><?= htmlspecialchars(Lang::get('title')) ?>:</strong>
          <?= htmlspecialchars($art['art_title']) ?>
        </p>

        <p class="mb-2">
          <strong><?= htmlspecialchars(Lang::get('category')) ?>:</strong>
          <?= htmlspecialchars($art['cat_name']) ?>
        </p>

        <p class="mb-0">
          <strong><?= htmlspecialchars(Lang::get('price')) ?>:</strong>
          <?= htmlspecialchars($art['art_price']) ?> €
        </p>

      </div>
    </div>

    <div class="d-flex flex-column">

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
      <textarea name="message" class="form-control mb-3" rows="5"></textarea>

      <button type="submit" name="order" class="btn btn-dark btn-lg rounded-2 mt-2">
        <?= htmlspecialchars(Lang::get('order_btn')) ?>
      </button>

    </div>
  </form>
</div>