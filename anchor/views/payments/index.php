<?php echo $header; ?>

<?php echo Html::link( Uri::current() . '/xls', __('global.export_xls'), array('class' => 'btn btn-success pull-right')); ?>

<h1 class="page-header"><?php echo __('payments.payments'); ?></h1>

<div class="row placeholder">
    <div class="col-md-12 table-responsive">

    

        <table class="table table-striped">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Client</th>
                  <th>Purchase Date</th>
                  <th>Expired Date</th>
                  <th>Credit</th>
                  <th>Used</th>
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

                    $use = Broadcast::where('account', '=', $profile->id)->sum('credit');
                    $profile->topup = Topup::where('client', '=', $profile->id)->sort('created', 'desc')->take(1)->column(array('credit'));
                    
                    $balance = money($profile->topup - $use);
                    
                    $total_credit += $profile->topup; 
                    $total_use += $use;
                    $total_expired += $profile->expire;
                    $total_balance += $balance;

                    $topup = $profile->topup ? $profile->topup : money(0);
                    $expire = $profile->expire ? $profile->expire : money(0);
                ?>
                <tr>
                <td><?php echo $key+1; ?></td>
                  <td><a href="<?php echo Uri::to('admin/broadcasts/add'); ?>"><?php echo $profile->real_name; ?></a></td>
                  <td><?php echo Date::format($profile->created); ?></td>
                  <td><?php echo Date::format($profile->expired); ?></td>
                  <td><?php echo money($topup); ?></td>
                  <td><?php echo money($use); ?></td>
                  <td><?php echo money($expire); ?></td>                  
                  <td><?php echo money($balance); ?></td>
                </tr>
              <?php endforeach; ?>
                <tr>
                  <th colspan="3">&nbsp;</th>
                  <th>TOTAL</th>
                  <th><?php echo money($total_credit); ?></th>
                  <th><?php echo money($total_use); ?></th>
                  <th><?php echo money($total_expired); ?></th>
                  <th><?php echo money($total_balance); ?></th>
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