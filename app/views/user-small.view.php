<div class="col-3 bg-light text-center p-4 m-2 shadow">
    <a href="<?= ROOT ?>/profile/<?= $row->id ?>">
        <img class="profile-image rounded-circle m-1" src="<?= get_image($row->image ?? '') ?>" alt="" style="width: 100px; height: 100px; object-fit: cover;">
        <h5><?= esc($row->username ?? 'Unknown') ?></h5>
    </a>
</div>