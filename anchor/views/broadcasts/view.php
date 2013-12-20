<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('broadcasts.edit_broadcast', $broadcast->client_name); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>
	<style>
dl {
  overflow: hidden;
	width: 700px;
}

dt {
	float: left;
	clear: right;
	width: 190px;
	background: none repeat scroll 0% 0% rgb(230, 233, 237);
	border-radius: 5px 0px 0px 5px;
}

dd {
	float: right;
	width: 510px;
	margin-left: 0;
	margin-bottom: 5px;
	color: #444;
	border-left: none;
	background-color: white;
}

dt, dd {
	height: 56px;
	line-height: 56px;
	padding-left: 14px;
	font-size: 20px;
}
        </style>

	<dl>

		<dt><?php echo __('global.status'); ?></dt>
		<dd><?php $broadcast->status; ?></dd>

		<dt><?php echo __('broadcasts.sender'); ?></dt>
		<dd><?php $broadcast->sender; ?></dd>

		<dt><?php echo __('broadcasts.recipient'); ?></dt>
		<dd><?php echo $broadcast->recipient; ?></dd>

		<dt><?php echo __('broadcasts.message'); ?></dt>
		<dd><?php echo $broadcast->message; ?></dd>

		<dt><?php echo __('global.created'); ?></dt>
		<dd><?php echo $broadcast->created; ?></dd>
	</dl>


	<form method="post" action="<?php echo Uri::to('admin/broadcasts/edit/' . $broadcast->id); ?>" novalidate enctype="multipart/form-data">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label><?php echo __('broadcasts.sender'); ?>:</label>
				<?php echo Form::text('sender', Input::previous('sender', $broadcast->sender)); ?>
				<em><?php echo __('broadcasts.sender_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('broadcasts.recipient'); ?>:</label>
				<?php echo Form::text('recipient', Input::previous('recipient', $broadcast->recipient)); ?>
				<em><?php echo __('broadcasts.recipient_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('broadcasts.description'); ?>:</label>
				<?php echo Form::textarea('description', Input::previous('description', $broadcast->description)); ?>
				<em><?php echo __('broadcasts.description_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.save'), array('type' => 'submit', 'class' => 'btn')); ?>

			<?php echo Html::link('admin/broadcasts/delete/' . $broadcast->id, __('global.delete'), array(
				'class' => 'btn delete red'
			)); ?>
		</aside>
	</form>
</section>

<script src="<?php echo asset('anchor/views/assets/js/recipient.js'); ?>"></script>

<?php echo $footer; ?>