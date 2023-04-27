<?php $this->view('header') ?>

<div class="p-2 col-md-6 shadow mx-auto border rounded">
    <h1>profile page view</h1>

    <div class="text-center">
        <img class="rounded-circle m-4" src="<?= get_image($row->image)?>" alt="" style="width: 200px; height: 200px; object-fit: cover;">
        <h3>Username</h3>
    </div>

    <div>
        <form action=""></form>
    </div>
    
    <image src="<?= ROOT ?>/assets/images/Bender_Futurama_multfilm_33609.jpg"></image>
</div>

<?php $this->view('footer') ?>
