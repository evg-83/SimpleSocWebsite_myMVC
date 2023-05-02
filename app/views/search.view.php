<?php $this->view('header') ?>

<center>
    <h4>Search</h4>
</center>

<div class="row col-md-10 my-3 mx-auto justify-content-center">

    <?php if (!empty($rows)) : ?>
        <?php foreach ($rows as $row) : ?>
            <?php $this->view('user-small', ['row' => $row]) ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- кнопки пагинации -->
    <?php $pager->display() ?>

</div>

<?php $this->view('footer') ?>