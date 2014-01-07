<?php echo $header; ?>
<h1 class="page-header"><?php echo __('dashboard.dashboard', 'Dashboard'); ?></h1>

<?php echo $messages; ?>

<div class="row placeholder">
    <div class="col-md-2 placeholder">
      <img src="<?php echo asset('anchor/views/assets/img/jobs-malaysia.png'); ?>" class="img-responsive" alt="Generic placeholder thumbnail">
    </div>
    <div class="col-md-4 placeholder">
        <h3><?php echo $client->client_name; ?></h3>
        <h5><?php echo __('users.email'); ?>: <span class="badge"><?php echo $client->email; ?></span></h5>
        <h5><?php echo __('users.bio'); ?>: <?php echo $client->bio; ?></h5>
        <h5><?php echo __('users.since'); ?>: <?php echo Date::format($client->created); ?></h5>
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

<h2 class="sub-header">Latest Activities</h2>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
          <th>Job ID</th>
          <th>Quantity</th>
          <th>Debit/Credit</th>
          <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php if($transactions->count): ?>
        <?php foreach($transactions->results as $transaction): ?>
        <tr>
          <td><a href="<?php echo Uri::to('admin/broadcasts/view/')  . $transaction->id; ?>"><?php echo $transaction->id; ?></a></td>
          <td><?php echo $transaction->quantity; ?></td>
          <td><?php echo $transaction->credit; ?></td>
          <td><?php echo Date::format($transaction->created, 'jS F Y h:i A'); ?></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="5"><?php echo __('dashboard.no_transactions'); ?></td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php echo $footer; ?>