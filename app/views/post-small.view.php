<div class="row post p-1">
    <div class="col-3 bg-light text-center">
        <img class="profile-image rounded-circle m-1" src="<?= get_image($post->user->image) ?>" alt="" style="width: 80px; height: 80px; object-fit: cover;">
        <h5><?= esc($post->user->username) ?></h5>
    </div>
    <div class="col-9 text-start">
        <div class="muted"><?= get_date($post->date) ?></div>

        <?php if (!empty($post->image)) : ?>
            <img class="profile-image rounded-circle m-2" src="<?= get_image($post->image) ?>" alt="" style="width: 80px; height: 80px; object-fit: cover;">
        <?php endif; ?>

        <p><?= esc($post->post) ?></p>
    </div>
    <hr>
</div>