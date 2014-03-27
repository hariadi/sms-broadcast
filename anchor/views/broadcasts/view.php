<?php echo $header; ?>

<?php echo Html::link('admin/broadcasts', __('global.back'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>
<h1 class="page-header"><?php echo __('broadcasts.view_broadcast', $broadcast->id); ?></h1>

<?php echo $messages; ?>

<div class="row">
	<div class="col col-lg-9">
  	<div class="panel panel-default">
      <div class="panel-heading"><?php echo __('broadcasts.broadcast_id', $broadcast->id); ?></div>
  	  <!-- List group -->
  	  <table class="table table-bordered">
          <thead>
            <tr>
              <th>Title</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th><?php echo __('global.status'); ?></th>
              <td><span class="label label-<?php
              $search  = array('success', 'pending', 'failed');
              $replace = array('success', 'primary', 'warning');
              echo str_replace($search, $replace, $broadcast->status); 
              ?>"><?php echo __('broadcasts.' . $broadcast->status); ?></span></td>
            </tr>
            <tr>
              <th><?php echo __('broadcasts.sender'); ?></th>
              <td><?php echo $broadcast->sender; ?></td>
            </tr>
            <?php
            $recipients = Json::decode($broadcast->recipient);

            if (!empty($recipients)) { 
              $count = count($recipients);
              $recipients = (is_array($recipients)) ? array_slice($recipients, 0, 5) : $recipients;
            ?>
  					<?php foreach($recipients as $key => $recipient): ?>
            <tr>
              <?php if($key === 0) :?><th rowspan="<?php echo $count; ?>"><?php echo __('broadcasts.recipient'); ?></th><?php endif?>
              <td><code><?php echo $recipient ?></code></td>
            	
            </tr><?php endforeach; ?><?php } ?>
            <tr>
              <th><?php echo __('broadcasts.message'); ?></th>
              <td><?php echo $broadcast->message; ?></td>
            </tr>
            <tr>
              <th><?php echo __('global.created'); ?></th>
              <td><?php echo Date::format($broadcast->created, 'jS F Y h:i A'); ?></td>
            </tr>
          </tbody>
        </table>
  	</div>

			<?php if ($count > 5) :?><em>Display only 5 from <?php echo $count ?> numbers</em><?php endif; ?>
	</div>
</div>

<?php echo $footer; ?>