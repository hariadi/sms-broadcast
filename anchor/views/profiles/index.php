<?php echo $header; ?>

<?php echo Html::link('admin/profiles/edit/' . $user->id, __('global.edit'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>

<h1 class="page-header"><?php echo __('users.editing_user', $user->username); ?></h1>

<div class="row placeholder">
    <div class="col-md-2 placeholder">
      <img src="<?php echo asset('anchor/views/assets/img/jobs-malaysia.png'); ?>" class="img-responsive" alt="Generic placeholder thumbnail">
    </div>
    <div class="col-md-4 placeholder">
        <h3><?php echo $user->real_name; ?></h3>
        <h5><?php echo __('users.email'); ?>: <span class="badge"><?php echo $user->email; ?></span></h5>
        <h5><?php echo __('users.bio'); ?>: <?php echo $user->bio; ?></h5>
        <h5><?php echo __('users.since'); ?>: <?php echo Date::format($user->created); ?></h5>
    </div>
    <div class="col-md-6 placeholder">
        <h3><?php echo __('dashboard.credit'); ?></h3>
        <ul class="list-group">
            <li class="list-group-item">
            <span class="badge"><?php echo $credits['available']; ?></span>
            <?php echo __('dashboard.available'); ?>
            </li>
            <li class="list-group-item">
            <span class="badge"><?php echo $credits['used']; ?></span>
            <?php echo __('dashboard.used'); ?>
            </li>
            <li class="list-group-item">
            <span class="badge"><?php echo $credits['balance']; ?></span>
            <?php echo __('dashboard.total'); ?>
            </li>
        </ul>
    </div>
</div>


<?php echo $footer; ?>