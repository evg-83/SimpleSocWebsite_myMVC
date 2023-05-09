<?php $this->view('header') ?>

<div class="row p-2 col-md-8 shadow mx-auto border rounded">

    <div class="my-3">

        <?php if (!empty($comment)) : ?>
            <h5>
                <span>Comment View</span><a href="<?= ROOT ?>/post/<?= $comment->post_id ?>" class="float-end">View Post</a>
            </h5>
            <?php $this->view('comment-full', $data) ?>
        <?php else: ?>
        <div class="m-1 alert alert-danger text-center">That record was not found!</div>
        <?php endif; ?>

        <!-- кнопки пагинации -->
        <?php $pager->display() ?>

    </div>
</div>

<?php $this->view('footer') ?>