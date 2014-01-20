<?php echo $header; ?>

<?php echo Html::link('admin/reports/sync', __('report.sync_report'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>


<h1 class="page-header"><?php echo __('report.report', 'Dashboard'); ?></h1>

<?php echo $messages; ?>

<form method="post" action="<?php echo Uri::to('admin/report/update'); ?>" novalidate enctype="multipart/form-data" role="form">

  <div class="form-group">
  <label class="sr-only" for="start_date"><?php echo __('broadcasts.start_date'); ?></label>

    <div class="input-group date col-sm-6">
      <?php echo Form::text('start_date', Input::previous('start_date'), array(
      'class' => 'form-control',
      'id' => 'start_date',
      )); ?>
      <div class="input-group-btn">
        <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-calendar"></span></button>
      </div>
    </div>
</div>
  <?php echo Form::button(__('global.submit'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
</form>       


<h2 class="sub-header">Latest Activities</h2>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Destination</th>
            <th>Message</th>
            <th>Charge</th>
            <th>Type</th>
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
          <td><?php echo $report->destination; ?></td>
          <td><?php echo $report->message; ?></td>
          <td><?php echo $report->charge; ?></td>
          <td><?php echo $report->type; ?></td>
          <td><?php echo Date::format($report->date, 'jS F Y h:i A'); ?></td>
          <td><?php echo $report->status; ?></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="7"><?php echo __('report.no_reports'); ?></td>
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