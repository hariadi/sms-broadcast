<?php echo $header; ?>

<div class="text-center"><a href="http://transrec.my">
	<img src="<?php echo asset('anchor/views/assets/img/transrec-310x163.png'); ?>" border="0" width="300" height="100" alt="TransRec Solution"></a>
</div>

<form class="form-signin" role="form" method="post" action="<?php echo Uri::to('admin/login'); ?>">

	<?php echo $messages; ?>
	<?php $user = filter_var(Input::previous('user'), FILTER_SANITIZE_STRING); ?>

	<input name="token" type="hidden" value="<?php echo $token; ?>">
  <h2 class="form-signin-heading">Please sign in</h2>

  <?php echo Form::text('user', $user, array(
		'class' => 'form-control',
		'autocapitalize' => 'off',
		'autofocus' => 'true',
		'required' => 'true',
		'placeholder' => __('users.username')
	)); ?>

	<?php echo Form::password('pass', array(
		'class' => 'form-control',
		'required' => 'true',
		'placeholder' => __('users.password')
	)); ?>

	<p class="buttons"><a href="<?php echo Uri::to('admin/amnesia'); ?>"><?php echo __('users.forgotten_password'); ?></a></p>

  <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo __('global.login'); ?></button>
</form>

<?php echo $footer; ?>