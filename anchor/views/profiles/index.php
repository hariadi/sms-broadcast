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
            <span class="badge"><?php echo $credits['available']; ?></span>
            <?php echo __('dashboard.available'); ?>
            </li>
            <li class="list-group-item">
            <span class="badge"><?php echo $credits['used']; ?></span>
            <?php echo __('dashboard.used'); ?>
            </li>
        </ul>
    </div>

    <div class="col-md-12 table-responsive">
        <table class="table table-striped">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Client</th>
                  <th>Credit</th>
                  <th>Use</th>
                  <th>Expired</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody>
                <?php //print_r($profiles); ?>
                <?php if($profiles->count): 
                
                $total_credit = 0; 
                $total_use = 0;
                $total_expired = 0;
                $total_balance = 0;
                
                ?>
                <?php foreach($profiles->results as $key => $profile):

                    $use = Broadcast::where('client', '=', $profile->id)->sum('credit');
                    $balance = number_format((float) abs($profile->topup - $use), 2, '.', '');
                    
                    $total_credit += $profile->topup; 
                    $total_use += $use;
                    $total_expired += $profile->expire;
                    $total_balance += $balance;
                ?>
                <tr>
                <td><?php echo $key+1; ?></td>
                  <td><a href="<?php echo Uri::to('admin/profiles/view/')  . $profile->id; ?>"><?php echo $profile->real_name; ?></a></td>
                  <td><?php echo $profile->topup; ?></td>
                  <td><?php echo $use; ?></td>
                  <td><?php echo $profile->expire; ?></td>
                  <td><?php echo $balance; ?></td>
                </tr>
              <?php endforeach; ?>
                <tr>
                  <th colspan="2"></th>
                  <th><?php echo $total_credit; ?></th>
                  <th><?php echo $total_use; ?></th>
                  <th><?php echo $total_expired; ?></th>
                  <th><?php echo $total_balance; ?></th>
                </tr>
                <?php else: ?>
                <tr>
                  <td colspan="7"><?php echo __('profiles.no_reports'); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
      </table>
    </div>

</div>


<?php echo $footer; ?>