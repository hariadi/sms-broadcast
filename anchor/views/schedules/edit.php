<?php echo $header; ?>
            
<?php echo Html::link('admin/schedules', __('global.back'), array('class' => 'btn btn-primary pull-right')); ?>

<h1 class="page-header"><?php echo __('schedules.edit_schedule', $schedule->id); ?></h1>

<?php echo $messages; ?>

<div class="row">
  <div class="col-lg-8">
    <form class="form-horizontal" method="post" action="<?php echo Uri::to('admin/schedules/edit/' . $schedule->id); ?>" novalidate enctype="multipart/form-data">
      <input name="token" type="hidden" value="<?php echo $token; ?>">
      <input name="broadcast" type="hidden" value="<?php echo $schedule->broadcast; ?>">
      <fieldset>
        <legend>Broadcast Details</legend>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="sender"><?php echo __('broadcasts.sender'); ?></label>
          <div class="col-lg-10">
            <?php echo Form::text('sender', Input::previous('sender', $schedule->sender), array(
              'class' => 'form-control',
              'id' => 'sender',
            )); ?>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="keyword"><?php echo __('broadcasts.keyword'); ?></label>
          <div class="col-lg-10">
            <?php echo Form::text('keyword', Input::previous('keyword', $schedule->keyword), array(
              'class' => 'form-control',
              'id' => 'keyword',
            )); ?>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="recipient"><?php echo __('broadcasts.recipient'); ?></label>
          <div class="col-lg-10">
            <?php $recipients = implode(', ', Json::decode($schedule->recipient)); ?>
            <?php echo Form::text('recipient', Input::previous('recipient', $recipients), array(
              'class' => 'form-control',
              'id' => 'recipient',
            )); ?>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="fromfile"><?php echo __('broadcasts.fromfile'); ?>:</label>
          <div class="col-lg-10">
            <?php echo Form::file('fromfile', array(
              'class' => 'filestyle',
              'data-classButton' => 'btn btn-primary',
              'data-classIcon' => 'glyphicon-folder-open',
              'id' => 'fromfile'
            )); ?>
            <p class="help-block">The file must in .txt, .doc, .pdf, and .xls.</p>
          </div>

        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="message"><?php echo __('broadcasts.message'); ?></label>
          <div class="col-lg-10">
            <?php echo Form::textarea('message', Input::previous('message', $schedule->message), array(
              'rows' => 3,
              'class' => 'form-control',
              'id' => 'message'
            )); ?>
            <p class="help-block" id="message_feedback"></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-2 control-label" for="schedule"><?php echo __('broadcasts.schedule'); ?></label>
          <div class="col-lg-4">
            <?php echo Form::select('schedule', $schedules, Input::previous('schedule', $schedule->schedule), array(
              'class' => 'form-control ',
              'id' => 'schedule',
            )); ?>
          </div>
        </div>

        <div class="form-group" id="description">
          <label class="col-lg-2 control-label" for="description"><?php echo __('broadcasts.description'); ?></label>
          <div class="col-lg-10">
            <?php echo Form::textarea('description', Input::previous('description', $schedule->description), array(
              'rows' => 3,
              'class' => 'form-control',
              'id' => 'description'
            )); ?>
            <p class="help-block" id="message_feedback"></p>
          </div>
        </div>

        <div class="form-group" id="date">

          <label class="col-lg-2 control-label" for="start_date"><?php echo __('broadcasts.start_date'); ?></label>

          <div class="col-lg-4">
            <div class="input-group date">
              <?php echo Form::text('start_date', Input::previous('start_date', $schedule->start), array(
              'class' => 'form-control',
              'id' => 'start_date',
              )); ?>
              <div class="input-group-btn">
                <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-calendar"></span></button>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group" id="weekly">
          <div class="control-label col-lg-2">
            <label class="control-label"><?php echo __('broadcasts.weekdays'); ?></label>
          </div>
          <div class="col-lg-10">
            <?php $weekdays = explode(',', $schedule->week); ?>
            <?php foreach(array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') as $key => $weekday): ?>
              <label class="col-lg-4">
                <?php $checked = in_array($key+1 , $weekdays) ? ' checked' : '';?>
                <?php echo Form::checkbox('weekdays[]', $key+1, $checked, array(
                'id' => $weekday
              )); ?> <?php echo __('broadcasts.' . $weekday); ?> </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="form-group" id="monthly">
          <div class="control-label col-lg-2">
            <label class="control-label"><?php echo __('broadcasts.monthly'); ?></label>
          </div>
          <div class="col-lg-10">
            <?php $months = explode(',', $schedule->month); ?>
            <?php foreach(array('jan', 'feb', 'mac', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec') as $key => $monthly):
            ?>
              <label class="col-lg-4">
                <?php $checked = in_array($key+1 , $months) ? ' checked' : '';?>
                <?php echo Form::checkbox('monthly[]', $key+1, $checked, array(
                'id' => $monthly
              )); ?> <?php echo __('broadcasts.' . $monthly); ?> </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="form-group" id="days">
          <div class="control-label col-lg-2">
            <label class="control-label"><?php echo __('broadcasts.days'); ?></label>
          </div>
          <div class="col-lg-10">
            <?php $daily = explode(',', $schedule->day); ?>
            <?php for($days=1; $days<32; $days++): $day = ($days < 10) ? '0' . $days : $days; ?>
            
              <label class="col-lg-2">
                <?php $checked = in_array($days , $daily) ? ' checked' : ''; ?>
                <?php echo Form::checkbox('days[]', $days, $checked); ?> <?php echo $day; ?> </label>
            <?php endfor; ?>
          </div>
        </div>
        
        <div class="form-group">
          <div class="col-lg-10 col-lg-offset-2">
            <?php echo Form::button(__('global.update'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>

            <?php echo Html::link('admin/schedules/delete/' . $schedule->id, __('global.delete'), array(
              'class' => 'btn btn-danger'
            )); ?>
          </div>
        </div>
      </fieldset>
    </form>
  </div>
  <div class="col-lg-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Info</h3>
      </div>
      <div class="panel-body">
        Please ensure you are meet following criteria:
      </div>
      <!-- List group -->
      <ul class="list-group">
        <li class="list-group-item">Format number: 012 345 6789, 6012 345 6789</li>
        <li class="list-group-item">Message no longer than 140 character</li>
        <li class="list-group-item">Only <code>.xls</code>, <code>.xlsx</code> and <code>.csv</code> file upload suppoerted.</li>
      </ul>
    </div>
  </div>
</div>

<?php echo $footer; ?>