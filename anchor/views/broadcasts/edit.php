<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('broadcasts.edit_broadcast', $broadcast->client_name); ?></h1>
</hgroup>
<?php print_r($broadcast); ?>
<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/broadcasts/edit/' . $broadcast->id); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label><?php echo __('broadcasts.sender'); ?>:</label>
				<?php echo Form::text('sender', Input::previous('sender', $broadcast->sender)); ?>
				<em><?php echo __('broadcasts.sender_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('broadcasts.slug'); ?>:</label>
				<?php echo Form::text('slug', Input::previous('slug', $broadcast->slug)); ?>
				<em><?php echo __('broadcasts.slug_explain'); ?></em>
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

<script src="<?php echo asset('anchor/views/assets/js/slug.js'); ?>"></script>

<?php echo $footer; ?>