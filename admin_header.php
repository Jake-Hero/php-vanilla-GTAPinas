<div class="row d-flex mb-5">
    <div class="col">
        <a href="<?php echo SITE_URL; ?>/admin/index.php" class="btn btn-dark">Dashboard</a>
    </div>

    <?php if($obj->isUserAdmin() >= 2): ?>
    <div class="col">
        <a href="<?php echo SITE_URL; ?>/admin/ban_list.php" class="btn btn-dark">Ban List</a>
    </div>
    <?php endif; ?>
</div>