<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('broadcasts.create_broadcast'); ?></h1>
</hgroup>

<section class="wrap">
	<?php echo $messages; ?>

	<form method="post" action="<?php echo Uri::to('admin/broadcasts/add'); ?>" novalidate>

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset class="split">
			<p>
				<label for="title"><?php echo __('broadcasts.title'); ?>:</label>
				<input id="title" name="title" value="<?php echo Input::previous('title'); ?>">
				<em><?php echo __('broadcasts.title_explain'); ?></em>
			</p>
			<p>
				<label for="slug"><?php echo __('broadcasts.slug'); ?>:</label>
				<input id="slug" name="slug" value="<?php echo Input::previous('slug'); ?>">
				<em><?php echo __('broadcasts.slug_explain', 'The slug for your broadcast.'); ?></em>
			</p>
			<p>
				<label for="description"><?php echo __('broadcasts.description'); ?>:</label>
				<textarea id="description" name="description"><?php echo Input::previous('description'); ?></textarea>
				<em><?php echo __('broadcasts.description_explain'); ?></em>
			</p>
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.save'), array('type' => 'submit', 'class' => 'btn')); ?>
		</aside>

	</form>
</section>

<script src="<?php echo asset('anchor/views/assets/js/slug.js'); ?>"></script>

<?php echo $footer; ?>