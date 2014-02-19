<?php echo $header; ?>

<?php echo Html::link('admin/users', __('global.back'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>

<h1 class="page-header"><?php echo __('users.editing_user', $user->username); ?></h1>

<?php echo $messages; ?>

<div class="row">
  <div class="col-lg-12">

  	<form class="form-horizontal" method="post" action="<?php echo Uri::to('admin/users/edit/' . $user->id); ?>" novalidate  autocomplete="off" enctype="multipart/form-data">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="col-lg-8">

			<div class="form-group">
        <label class="col-lg-2 control-label" for="real_name"><?php echo __('users.real_name'); ?></label>
        <div class="col-lg-10">
          <?php echo Form::text('real_name', Input::previous('real_name', $user->real_name), array(
						'class' => 'form-control',
						'id' => 'real_name',
					)); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="bio"><?php echo __('users.bio'); ?></label>
        <div class="col-lg-10">
          <?php echo Form::textarea('bio', Input::previous('bio', $user->bio), array(
						'rows' => 2,
						'class' => 'form-control',
						'id' => 'bio'
					)); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="status"><?php echo __('users.status'); ?></label>
        <div class="col-lg-4">
          <?php echo Form::select('status', $statuses, Input::previous('status', $user->status), array(
						'class' => 'form-control ',
						'id' => 'status',
					)); ?>
					<p class="help-block"><?php echo __('users.status_explain'); ?></p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="role"><?php echo __('users.role'); ?></label>
        <div class="col-lg-4">
          <?php echo Form::select('role', $roles, Input::previous('role', $user->role), array(
						'class' => 'form-control ',
						'id' => 'role',
					)); ?>
					<p class="help-block"><?php echo __('users.role_explain'); ?></p>
        </div>
      </div>

      <input name="current_credit" type="hidden" value="<?php echo $credit['balance']; ?>">

      <div class="form-group">
        <label class="col-lg-2 control-label" for="credit"><?php echo __('users.credit'); ?></label>
        <div class="col-lg-5">
          <?php echo Form::text('credit', Input::previous('credit'), array(
						'class' => 'form-control',
						'id' => 'credit',
					)); ?>
					<p class="help-block"><?php echo __('users.credit_explain'); ?></p>
        </div>
        <div class="col-lg-4">
        <p><?php echo __('users.current_credit', $credit['balance']); ?></p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-md-2 control-label" for="expired"><?php echo __('users.credit_expired'); ?></label>

        <div class="col-lg-4">
          <div class="input-group date">
            <?php echo Form::text('expired', Input::previous('expired', $user->expired), array(
              'class' => 'form-control',
              'id' => 'expired',
              )); ?>
            <div class="input-group-btn">
              <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-calendar"></span></button>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
	      <div class="col-lg-10 col-lg-offset-2">
	        <?php echo Form::button(__('global.update'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>

	        <?php echo Html::link('admin/users/delete/' . $user->id, __('global.delete'), array('class' => 'btn btn-danger')); ?>
	      </div>
	    </div>

		</fieldset>

		<fieldset class="col-lg-4">

			<div class="form-group">
        <label class="col-lg-2 control-label" for="username"><?php echo __('users.username'); ?></label>
        <div class="col-lg-10">
          <?php echo Form::text('username', Input::previous('username', $user->username), array(
						'class' => 'form-control',
						'id' => 'username',
					)); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="password"><?php echo __('users.password'); ?></label>
        <div class="col-lg-10">
          <?php echo Form::password('password', array(
						'class' => 'form-control',
						'id' => 'password',
					)); ?>
        </div>
      </div>

			<div class="form-group">
			  <label class="col-lg-2 control-label" for="email"><?php echo __('users.email'); ?></label>
			  <div class="col-lg-10">
			    <?php echo Form::email('email', Input::previous('email', $user->email), array(
						'class' => 'form-control',
						'id' => 'email',
					)); ?>
			  </div>
			</div>

			<?php if ($fields) : foreach($fields as $field) : ?>

			<div class="form-group">
        <label class="col-lg-2 control-label" for="<?php echo $field->key; ?>"><?php echo $field->label; ?></label>
        <div class="col-lg-10">
        	<?php echo Extend::html($field); ?>
        </div>
      </div>
    	<?php endforeach; endif; ?>

			
		</fieldset>

		
	</form>
	</div>
</div>

<?php echo $footer; ?>