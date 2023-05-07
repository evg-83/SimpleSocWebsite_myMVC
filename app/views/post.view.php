<?php $this->view('header') ?>

<div class="row p-2 col-md-8 shadow mx-auto border rounded">

    <div class="my-3">

        <?php if (!empty($post)) : ?>
                <?php $this->view('post-full', $data) ?>
        <?php endif; ?>

        <!-- кнопки пагинации -->
        <?php $pager->display() ?>

    </div>
</div>

<?php $this->view('footer') ?>