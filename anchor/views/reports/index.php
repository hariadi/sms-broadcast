<?php echo $header; ?>

<?php echo Html::link('admin/reports/sync', __('reports.sync_report'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>


<h1 class="page-header"><?php echo __('reports.report', 'Dashboard'); ?></h1>

<?php echo $messages; ?>

<form class="form-horizontal" method="post" action="<?php echo Uri::to('admin/report/update'); ?>" novalidate enctype="multipart/form-data" role="form">

  <div class="form-group">
    <label class="col-md-1 control-label" for="from_date"><?php echo __('reports.from_date'); ?></label>

      <div class="input-group date col-sm-4">
        <?php echo Form::text('from_date', Input::previous('from_date'), array(
        'class' => 'form-control',
        'id' => 'from_date',
        )); ?>
        <div class="input-group-btn">
          <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-calendar"></span></button>
        </div>
      </div>
  </div>

<div class="form-group">
  <label class="col-md-1 control-label" for="to_date"><?php echo __('reports.to_date'); ?></label>

    <div class="input-group date col-sm-4">
      <?php echo Form::text('to_date', Input::previous('to_date'), array(
      'class' => 'form-control',
      'id' => 'to_date',
      )); ?>
      <div class="input-group-btn">
        <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-calendar"></span></button>
      </div>
    </div>
</div>

<div class="form-group">
  <label class="col-lg-2 control-label" for="sort"><?php echo __('reports.sort'); ?></label>
  <div class="col-lg-4">
    <?php echo Form::select('sort', $sorts, Input::previous('sort'), array(
      'class' => 'form-control ',
      'id' => 'sort',
    )); ?>
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
          <td><?php echo $report->client_name; ?></td>
          <td><?php echo $report->message; ?></td>
          <td><?php echo $report->sender; ?></td>
          <td><?php echo Date::format($report->date, 'jS F Y h:i A'); ?></td>
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