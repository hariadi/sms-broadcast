<?php echo $header; ?>

<?php echo Html::link('admin/profiles/edit/', __('global.edit'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>

<h1 class="page-header"><?php echo __('users.editing_user', $user->username); ?></h1>

<div class="row placeholder">
    <div class="col-md-2 placeholder">
        <?php $img = ($fields && isset($fields[0]->value->filename)) ? $fields[0]->value->filename : 'jobs-malaysia.png'; ?>
      <img src="<?php echo asset('content/avatar/' . $img); ?>" class="img-responsive" alt="Generic placeholder thumbnail">
      
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
            <span class="badge"><?php echo abs($credits['available']); ?></span>
            <?php echo __('dashboard.available'); ?>
            </li>
            <li class="list-group-item">
            <span class="badge"><?php echo abs($credits['used']); ?></span>
            <?php echo __('dashboard.used'); ?>
            </li>
        </ul>
    </div>

</div>


<?php echo $footer; ?>