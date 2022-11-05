<!-- review -->
<div class="review-item slide-review <?= $review_with_photo ?>">
  <div class="review-item-header">
    <div class="review-item-main-info">
      <div class="review-item-title"><?= $user_name ?></div>
      <div class="review-item-date"><?= $date ?></div>
    </div>

    <div class="review-item-rating">
      <?= $stars ?>
    </div>
  </div>
  <div class="review-item-text <?= $hidden_text_class ?>">
    <?= $review_text ?>
  </div>

  <?php if (isset($photos_path) and !empty($photos_path)) { ?>

    <div class="review-photo-list">
      <?php foreach ($photos_path as $photo_path) { ?>

        <div class="review-photo-container">
          <img class="review-photo" src=" <?= $photo_path ?>" alt="">
        </div>

      <?php } ?>
    </div>

  <?php } ?>
</div>
<!-- review -->