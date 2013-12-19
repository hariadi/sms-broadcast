<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('broadcasts.create_broadcast'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/broadcasts/add'); ?>" novalidate enctype="multipart/form-data">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label for="sender"><?php echo __('broadcasts.sender'); ?>:</label>
				<input id="sender" name="sender" value="<?php echo Input::previous('sender'); ?>">
				<em><?php echo __('broadcasts.sender_explain'); ?></em>
			</p>
			<p>
				<label for="recipient"><?php echo __('broadcasts.recipient'); ?>:</label>
				<input id="recipient" name="recipient" value="<?php echo Input::previous('recipient'); ?>">
				<em><?php echo __('broadcasts.recipient_explain', 'The recipient for your broadcast.'); ?></em>
			</p>
			<p>
				<label for="fromfile"><?php echo __('broadcasts.fromfile'); ?>:</label>
				<input type="file" id="fromfile" name="fromfile">
				<em><?php echo __('broadcasts.fromfile_explain', 'Or upload recipient for your broadcast.'); ?></em>
			</p>
			<p>
				<label for="message"><?php echo __('broadcasts.message'); ?>:</label>
				<textarea id="message" name="message"><?php echo Input::previous('message'); ?></textarea>
				<em><?php echo __('broadcasts.message_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.broadcast'), array('type' => 'submit', 'class' => 'btn')); ?>
		</aside>

	</form>
</section>


<?php echo $footer; ?>