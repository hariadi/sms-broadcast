<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('broadcasts.edit_broadcast', $broadcast->client_name); ?></h1>
</hgroup>

<section class="wrap">

	<?php echo $messages; ?>
	<dl>
		<dt><?php echo __('global.status'); ?></dt>
		<dd><?php echo ucfirst($broadcast->status); ?></dd>

		<dt><?php echo __('broadcasts.sender'); ?></dt>
		<dd><?php echo $broadcast->sender; ?></dd>

		
		<?php
		$recipients = Json::decode($broadcast->recipient);
		if (!empty($recipients)) { 
		$count = count($recipients);
		$recipients = (is_array($recipients)) ? array_slice($recipients, 0, 5) : $recipients;
		?>
		<dt><?php echo __('broadcasts.recipient'); ?></dt>
		<?php if ($count > 1) :?><em>Display only 5 from <?php echo $count ?> numbers</em><?php endif; ?>
		<?php foreach($recipients as $key => $recipient): ?>
		<dd><?php echo $recipient ?></dd>
		<?php endforeach; ?>
		<?php } ?>
		<dt><?php echo __('broadcasts.message'); ?></dt>
		<dd><?php echo $broadcast->message; ?></dd>

		<dt><?php echo __('global.created'); ?></dt>
		<dd><?php echo $broadcast->created; ?></dd>
	</dl>

	<aside class="buttons">
		<?php echo Html::link('admin/broadcasts/', __('global.back'), array(
				'class' => 'btn'
			)); ?>
	</aside>


</section>

<?php echo $footer; ?>