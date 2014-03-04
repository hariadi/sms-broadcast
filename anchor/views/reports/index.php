<?php echo $header; ?>

<h1 class="page-header"><?php echo __('reports.report', 'Dashboard'); ?></h1>

<?php echo $messages; ?>

<?php //echo Uri::current(); ?>


<form class="form-horizontal" method="post" action="<?php echo Uri::to('admin/reports/search'); ?>" novalidate enctype="multipart/form-data" role="form">
  <input name="token" type="hidden" value="<?php echo $token; ?>">

  <fieldset>

    <div class="form-group">

      <label class="col-md-2 control-label" for="from_date"><?php echo __('reports.from_date'); ?></label>

      <div class="col-lg-4">
        <div class="input-group date">
          <?php echo Form::text('from_date', Input::previous('from_date', $search->from), array(
            'placeholder' => 'yyyy-mm-dd',
            'class' => 'form-control',
            'id' => 'from_date',
            )); ?>
          <div class="input-group-btn">
            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-calendar"></span></button>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">

      <label class="col-md-2 control-label" for="to_date"><?php echo __('reports.to_date'); ?></label>

      <div class="col-lg-4">
        <div class="input-group date">
          <?php echo Form::text('to_date', Input::previous('to_date', $search->to), array(
            'placeholder' => 'yyyy-mm-dd',
            'class' => 'form-control',
            'id' => 'to_date',
            )); ?>
          <div class="input-group-btn">
            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-calendar"></span></button>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <?php echo Form::button(__('global.search'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>

         <?php echo Html::link( Uri::current() . '/xls', __('global.export_xls'), array('class' => 'btn btn-success')); ?>
      </div>
    </div>
  </fieldset>
</form>       


          <h2 class="sub-header">Latest Activities</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Client</th>
                  <th>Message</th>
                  <th>Sender</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php if($reports->count): ?>
                <?php foreach($reports->results as $report): ?>
                <tr>
                  <!--td><a href="<?php echo Uri::to('admin/reports/view/')  . $report->id; ?>"><?php echo $report->id; ?></a></td-->
                  <td><?php echo $report->id; ?></td>
                  <td><?php echo  User::find($report->account)->real_name; ?></td>
                  <td><?php echo $report->message; ?></td>
                  <td><?php echo $report->client_name; ?></td>
                  <td><?php echo Date::format($report->created, 'jS F Y h:i A'); ?></td>
                  <td><span class="label label-<?php
                  $search  = array('success', 'pending', 'failed');
                  $replace = array('success', 'primary', 'warning');
                  echo str_replace($search, $replace, $report->status); 
                  ?>"><?php echo __('broadcasts.' . $report->status); ?></span></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
            <tr>
              <td colspan="7"><?php echo __('reports.no_reports'); ?></td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <?php if ($reports->links()) : ?>
      <ul class="pagination">
        <?php echo $reports->links(); ?>
      </ul>
    <?php endif; ?>

  </div>

  <?php echo $footer; ?>