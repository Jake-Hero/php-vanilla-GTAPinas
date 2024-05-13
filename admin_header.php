<div class="mb-5">
    <div class="btn-group btn-group-sm mr-2" role="group">
        <a href="<?php echo SITE_URL; ?>/admin/index.php" class="btn btn-dark"><span class="fas fa-home"></span> Dashboard</a>
    </div>

    <div class="btn-group btn-group-sm mr-2" role="group">
        <?php if($obj->isUserAdmin() >= 2): ?>
            <a href="<?php echo SITE_URL; ?>/admin/ban_list.php" class="btn btn-dark"><span class="fas fa-gavel"></span> Ban List</a>
        <?php endif; ?>
    </div>

    <div class="btn-group btn-group-sm mr-2" role="group">
        <a href="<?php echo SITE_URL; ?>/admin/groups.php" class="btn btn-dark"><span class="fas fa-users"></span> Groups</a>
    </div>

    <div class="btn-group btn-group-sm mr-2" role="group">
        <a href="<?php echo SITE_URL; ?>/admin/turfs.php" class="btn btn-dark"><span class="fas fa-map-pin"></span> Turfs</a>
    </div>
</div>