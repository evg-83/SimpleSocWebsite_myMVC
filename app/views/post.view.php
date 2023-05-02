<?php $this->view('header') ?>

<div class="row p-2 col-md-8 shadow mx-auto border rounded">

    <div class="col-md-2 text-center">

        <a href="<?= ROOT ?>/profile/<?= $row->id ?>">
            <span>
                <img class="profile-image rounded-circle m-4" src="<?= get_image($row->image) ?>" style="width: 100px; height: 100px; object-fit: cover;">
            </span>

            <h5><?= esc($row->username) ?></h5>
        </a>

    </div>

    <div class="col-md-10 my-3">

        <?php if (!empty($posts)) : ?>
            <?php foreach ($posts as $post) : ?>
                <?php $this->view('post-small', ['post' => $post]) ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- кнопки пагинации -->
        <?php $pager->display() ?>

    </div>
</div>

<?php $this->view('footer') ?>