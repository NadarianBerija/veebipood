<?php
ob_start()
?>

<div class="d-flex flex-column gap-4">
    <h2>Slaidid</h2>
    <button class="btn btn-dark btn-lg rounded-2" style="width: 200px;" data-bs-toggle="modal" data-bs-target="#addSlideModal">Lisa uus slaid</button>
    <div class="slidesContainer">
    <?php
    if (!empty($arr)) { 
        foreach($arr as $slide) { 
    ?>
    <div class="card">
        <div class="slide card-body">
            <img src="../public/<?= htmlspecialchars($slide['slide_img']) ?>">
        </div>
        <button class="btn btn-danger btn-lg rounded-2 my-2 mx-5" data-bs-toggle="modal" data-bs-target="#deleteSlideModal" data-id="<?= htmlspecialchars($slide['slide_id']) ?>"><i class="bi bi-trash-fill"></i></button>
    </div>
    <?php 
        }
    } else {
    echo '<p>Slaidid puuduvad</p>';
    }
    ?>
    </div>
</div>
<div class="modal fade" id="addSlideModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="addSlide.php" enctype="multipart/form-data">

        <div class="modal-header">
          <h5 class="modal-title">Lisa uus slaid</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label">Vali pilt</label>
            <input type="file" name="slide_img" class="form-control" accept="image/*" required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Lisa</button>
        </div>

      </form>

    </div>
  </div>
</div>

<div class="modal fade" id="deleteSlideModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form method="POST" action="heroSlides">

        <div class="modal-header">
          <h5 class="modal-title">Kustutamine</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <p>Kas olete kindel, et soovite slaidi eemaldada?</p>
          <input type="hidden" name="slide_id" id="deleteSlideId">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Loobu
          </button>
          <button type="submit" class="btn btn-danger">
            Kustuta
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include "viewAdmin/layout.php";
?>