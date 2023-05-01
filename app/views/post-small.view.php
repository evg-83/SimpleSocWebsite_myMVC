<div class="row post p-1">

    <div class="col-3 bg-light text-center">
        <a href="<?= ROOT ?>/profile/<?= $post->user->id ?>">
            <img class="profile-image rounded-circle m-1" src="<?= get_image($post->user->image ?? '') ?>" alt="" style="width: 80px; height: 80px; object-fit: cover;">
            <h5><?= esc($post->user->username ?? 'Unknown') ?></h5>
        </a>
    </div>

    <div class="col-9 text-start">
        <div class="muted"><?= get_date($post->date) ?></div>

        <p><?= esc($post->post) ?></p>

        <?php if (!empty($post->image)) : ?>
            <img class=" my-1" src="<?= get_image($post->image) ?>" alt="" style="width: 100%; height: 200px; object-fit: cover;">
        <?php endif; ?>

        <?php if (user('id') == $post->user_id) : ?>
            <div>
                <button class="btn-sm m-1 btn btn-warning">Edit</button>
                <button class="btn-sm m-1 btn btn-danger">Delete</button>
            </div>
        <?php endif; ?>
    </div>

    <hr>
</div>