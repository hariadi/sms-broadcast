<?php echo $header; ?>
<h1 class="page-header"><?php echo __('dashboard.dashboard', 'Dashboard'); ?></h1>

<?php echo $messages; ?>

<div class="row placeholder">
  <div class="col-md-2 placeholder">
    <?php $img = ($fields && isset($fields[0]->value->filename)) ? $fields[0]->value->filename : 'jobs-malaysia.png'; ?>
    <img src="<?php echo asset('content/avatar/' . $img); ?>" class="img-responsive" alt="Generic placeholder thumbnail">
  </div>
  <div class="col-md-4 placeholder">
    <h3><?php echo $client->client_name; ?></h3>
    <h5><?php echo __('users.email'); ?>: <span class="badge"><?php echo $client->email; ?></span></h5>
    <h5><?php echo __('users.bio'); ?>: <?php echo $client->bio; ?></h5>
    <h5><?php echo __('users.since'); ?>: <?php echo Date::format($client->created); ?></h5>
  </div>
  <div class="col-md-6 placeholder">

    <div class="panel panel-default">
      <!-- Default panel contents -->
      <div class="panel-heading"><?php echo __('dashboard.credit'); ?></div>
      <!-- List group -->
      <ul class="list-group">
      <?php if (Auth::user()->role == 'administrator') : ?>
        <li class="list-group-item">
          <span class="badge" id="ismsUpdate"><?php echo abs($ismsbalance); ?></span>
          <?php echo __('dashboard.isms_balance'); ?>  <a href="#" id="ismsBalance"><i class="glyphicon glyphicon-refresh"></i> &nbsp;</a>
        </li>
      <?php endif; ?>
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
</div>
<div class="row">
  <div class="col-md-6">
    
    <div class="table-responsive">
      <h2 class="sub-header">Latest Broadcast</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Quantity</th>
            <th>Debit/Credit</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if($broadcasts->count): ?>
            <?php foreach($broadcasts->results as $broadcast): ?>
              <tr>
                <td><?php echo $broadcast->quantity; ?></td>
                <td><?php echo $broadcast->credit; ?></td>
                <td><a href="<?php echo Uri::to('admin/broadcasts/view/')  . $broadcast->id; ?>"><?php echo Date::format($broadcast->created, 'jS F Y h:i A'); ?></a></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5"><?php echo __('dashboard.no_reports'); ?></td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <?php if ($broadcasts->links()) : ?>
        <ul class="pagination">
          <?php echo $broadcasts->links(); ?>
        </ul>
      <?php endif; ?>

    </div>
  </div>
  <div class="col-md-6">

    <div class="table-responsive">
      <h2 class="sub-header">Top-up History</h2>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Debit/Credit</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if($topups->count): ?>
            <?php foreach($topups->results as $topup): ?>
              <tr>
                <td><?php echo $topup->credit; ?></td>
                <td><?php echo Date::format($topup->created, 'jS F Y h:i A'); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5"><?php echo __('dashboard.no_reports'); ?></td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <?php if ($topups->links()) : ?>
        <ul class="pagination">
          <?php echo $topups->links(); ?>
        </ul>
      <?php endif; ?>

    </div>
  </div>
</div>



<?php echo $footer; ?>