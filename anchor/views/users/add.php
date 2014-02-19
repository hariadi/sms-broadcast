<?php echo $header; ?>

<?php echo Html::link('admin/users', __('global.back'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>

<h1 class="page-header"><?php echo __('users.add_user'); ?></h1>

<?php echo $messages; ?>

<div class="row">
  <div class="col-lg-12">

  	<form class="form-horizontal" method="post" action="<?php echo Uri::to('admin/users/add'); ?>" novalidate  autocomplete="off" enctype="multipart/form-data">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

			<fieldset class="col-lg-8">

				<div class="form-group">
	        <label class="col-lg-2 control-label" for="real_name"><?php echo __('users.real_name'); ?></label>
	        <div class="col-lg-10">
	          <?php echo Form::text('real_name', Input::previous('real_name'), array(
							'placeholder' => __('users.real_name_explain'),
							'class' => 'form-control',
							'id' => 'real_name',
						)); ?>
	        </div>
	      </div>

	      <div class="form-group">
	        <label class="col-lg-2 control-label" for="bio"><?php echo __('users.bio'); ?></label>
	        <div class="col-lg-10">
	          <?php echo Form::textarea('bio', Input::previous('bio'), array(
							'placeholder' => __('users.bio_explain'),
							'rows' => 2,
							'class' => 'form-control',
							'id' => 'bio'
						)); ?>
	        </div>
	      </div>

	      <div class="form-group">
	        <label class="col-lg-2 control-label" for="status"><?php echo __('users.status'); ?></label>
	        <div class="col-lg-4">
	          <?php echo Form::select('status', $statuses, Input::previous('status'), array(
							'class' => 'form-control ',
							'id' => 'status',
						)); ?>
						<p class="help-block"><?php echo __('users.status_explain'); ?></p>
	        </div>
	      </div>

	      <div class="form-group">
	        <label class="col-lg-2 control-label" for="role"><?php echo __('users.role'); ?></label>
	        <div class="col-lg-4">
	          <?php echo Form::select('role', $roles, Input::previous('role'), array(
							'class' => 'form-control ',
							'id' => 'role',
						)); ?>
						<p class="help-block"><?php echo __('users.role_explain'); ?></p>
	        </div>
	      </div>

	      <div class="form-group">
	        <label class="col-lg-2 control-label" for="credit"><?php echo __('users.credit'); ?></label>
	        <div class="col-lg-5">
	          <?php echo Form::text('credit', Input::previous('credit'), array(
							'class' => 'form-control',
							'id' => 'credit',
						)); ?>
						<p class="help-block"><?php echo __('users.credit_explain'); ?></p>
	        </div>
	      </div>
			<?php
	      	$expired = new DateTime(Date::mysql('now'));;
	      	$expired->modify('+3 month');
	      	?>
	      <div class="form-group">
	      		

		      <label class="col-md-2 control-label" for="expired"><?php echo __('users.credit_expired'); ?></label>

		      <div class="col-lg-4">
		        <div class="input-group date">
		          <?php echo Form::text('expired', Input::previous('expired'), array(
		            'value' => $expired->format('Y-m-d H:i:s'),
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
		        <?php echo Form::button(__('global.create'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
		      </div>
		    </div>

			</fieldset>
			<fieldset class="col-lg-4">

				<div class="form-group">
	        <label class="col-lg-2 control-label" for="username"><?php echo __('users.username'); ?></label>
	        <div class="col-lg-10">
	          <?php echo Form::text('username', Input::previous('username'), array(
							'placeholder' => __('users.username_explain'),
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
	          <?php echo Form::email('email', Input::previous('email'), array(
							'placeholder' => __('users.email_explain'),
							'class' => 'form-control',
							'id' => 'email',
						)); ?>
	        </div>
	      </div>

	      <?php foreach($fields as $field): ?>
				<p>
					<label for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
					<?php echo Extend::html($field); ?>
				</p>
				<?php endforeach; ?>

			</fieldset>

		</form>
	</div>
</div>


<?php echo $footer; ?>