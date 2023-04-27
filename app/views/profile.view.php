<?php $this->view('header') ?>

<div class="p-2 col-md-6 shadow mx-auto border rounded">
    <center><h2>User Profile</h2></center>

    <div class="text-center">

        <span>
            <img class="profile-image rounded-circle m-4" src="<?= get_image($row->image) ?>" alt="" style="width: 200px; height: 200px; object-fit: cover;">
            <label>
                <i style="position: absolute; cursor: pointer;" class="h1 text-primary bi bi-image"></i>
                <input onchange="display_image(this.files[0])" type="file" class="d-none">
            </label>
        </span>

        <h3><?= esc($row->username) ?></h3>

        <script>
            function display_image(file) {
                document.querySelector(".profile-image").src = URL.createObjectURL(file);
            }
        </script>

    </div>
    <div>
        <form method="post" action="">
            <div class="bg-secondary p-2">
                <textarea rows="6" class="form-control" placeholder="Whats on your mind?"></textarea>
                <button class="btn btn-warning mt-1 float-end">Post</button>
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
    <div class="my-3">

        <?php if (!empty($posts)) : ?>
            <?php foreach ($posts as $post) : ?>
                <?php $this->view('post-small', ['post' => $post]) ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

</div>

<?php $this->view('footer') ?>