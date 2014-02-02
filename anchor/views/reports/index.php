<?php echo $header; ?>

<h1 class="page-header"><?php echo __('reports.report', 'Dashboard'); ?></h1>

<?php echo $messages; ?>

<form class="form-horizontal" method="post" action="<?php echo Uri::to('admin/reports'); ?>" novalidate enctype="multipart/form-data" role="form">
  <input name="token" type="hidden" value="<?php echo $token; ?>">

  <fieldset>
    <legend>Search</legend>

    <div class="form-group">
      <label class="col-md-2 control-label" for="type"><?php echo __('reports.type'); ?></label>
      <div class="row">
        <div class="col-lg-1">
          <input type="radio" name="use" value="interval" id="interval" checked="checked">
        </div>
        <div class="col-lg-7">
          <div class="input-group col-md-4">
            <?php echo Form::select('type', $types, Input::previous('type'), array(
            'class' => 'form-control ',
            'id' => 'type',
            'onclick' => 'document.getElementById(\'interval\').checked = true',
            'onfocus' => 'document.getElementById(\'interval\').checked = true',
            )); ?>
          </div>
        </div>
     </div>
   </div>
   <div class="form-group"><label class="col-md-2 control-label" for="from_date"><?php echo __('reports.from_date'); ?></label>
    <div class="row">
       <div class="col-lg-1">
        <input type="radio" name="use" value="daterange" id="daterange">
      </div>

       <div class="col-lg-2">
        <div class="input-group date">
          <?php echo Form::text('from_date', Input::previous('from_date'), array(
            'class' => 'form-control',
            'id' => 'from_date',
            'onclick' => 'document.getElementById(\'daterange\').checked = true',
            'onfocus' => 'document.getElementById(\'daterange\').checked = true',
            )); ?>
          <div class="input-group-btn">
            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-calendar"></span></button>
          </div>
        </div>
      </div>

      <label class="col-md-2 control-label" for="to_date"><?php echo __('reports.to_date'); ?></label>
      
      <div class="col-lg-2">
        <div class="input-group date">
          <?php echo Form::text('from_date', Input::previous('from_date'), array(
            'class' => 'form-control',
            'id' => 'to_date',
            'onclick' => 'document.getElementById(\'daterange\').checked = true',
            'onfocus' => 'document.getElementById(\'daterange\').checked = true',
            )); ?>
            <div class="input-group-btn">
              <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-calendar"></span></button>
            </div>
          </div>
        </div>

      </div>
    </div>


    <div class="form-group">
      <label class="col-md-2 control-label" for="sort"><?php echo __('reports.sort'); ?></label>
      <div class="row">
        <div class="col-lg-2 col-md-offset-1">

          <?php echo Form::select('sort', $sorts, Input::previous('sort'), array(
            'class' => 'form-control ',
            'id' => 'sort',
            )); ?>

        </div>
        <label class="sr-only" for="order"><?php echo __('reports.order'); ?></label>
        <div class="col-lg-2">
          <?php echo Form::select('order', $orders, Input::previous('order'), array(
            'class' => 'form-control ',
            'id' => 'order',
            )); ?></div>
        </div>
      
        </div>

              <div class="form-group">
                <div class="col-md-offset-2">
                  <?php echo Form::button(__('global.search'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>

                  <?php echo Html::link('admin/posts/export/xls', __('global.export_xls'), array('class' => 'btn btn-success')); ?>
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
                  <td><?php echo $report->client_name; ?></td>
                  <td><?php echo $report->message; ?></td>
                  <td><?php echo $report->sender; ?></td>
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