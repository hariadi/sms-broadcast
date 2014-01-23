<?php echo $header; ?>

<h1 class="page-header"><?php echo __('posts.create_post'); ?></h1>

<?php echo $messages; ?>

<form class="form-horizontal" method="post" action="<?php echo Uri::to('admin/posts/add'); ?>" enctype="multipart/form-data" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<fieldset class="header">

		<div class="form-group">
      <label class="col-lg-2 control-label" for="title"><?php echo __('posts.title'); ?></label>
      <div class="col-lg-10">
        <?php echo Form::text('title', Input::previous('title'), array(
					'placeholder' => __('posts.title'),
					'autocomplete'=> 'off',
					'class' => 'form-control',
					'id' => 'title',
					'autofocus' => 'true'
				)); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="html"><?php echo __('posts.content'); ?></label>
      <div class="col-lg-10">
      	<?php echo Form::textarea('html', Input::previous('html'), array(
					'placeholder' => __('posts.content_explain'),
					'rows' => 3,
					'class' => 'form-control',
					'id' => 'html'
				)); ?>
				<?php echo $editor; ?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="slug"><?php echo __('posts.slug'); ?></label>
      <div class="col-lg-10">
      	<?php echo Form::text('slug', Input::previous('slug'), array(
					'class' => 'form-control',
					'id' => 'slug',
				)); ?>
				<p class="help-block"><?php echo __('posts.slug_explain'); ?></p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="description"><?php echo __('posts.description'); ?></label>
      <div class="col-lg-10">
      	<?php echo Form::textarea('description', Input::previous('description'), array(
					'rows' => 3,
					'class' => 'form-control',
					'id' => 'description'
				)); ?>
      </div>
    </div>

     <div class="form-group">
      <label class="col-lg-2 control-label" for="status"><?php echo __('posts.status'); ?></label>
      <div class="col-lg-4">
        <?php echo Form::select('status', $statuses, Input::previous('status'), array(
          'class' => 'form-control',
          'id' => 'status',
        )); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="category"><?php echo __('posts.category'); ?></label>
      <div class="col-lg-4">
        <?php echo Form::select('category', $categories, Input::previous('category'), array(
          'class' => 'form-control',
          'id' => 'category',
        )); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="comments"><?php echo __('posts.allow_comments'); ?></label>
      <div class="col-lg-1">
        <?php echo Form::checkbox('comments', 1, Input::previous('comments', 0) == 1, array(
          'class' => 'checkbox',
          'id' => 'comments',
        )); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="css"><?php echo __('posts.custom_css'); ?></label>
      <div class="col-lg-10">
      	<?php echo Form::textarea('css', Input::previous('css'), array(
					'placeholder' => __('posts.custom_css_explain'),
					'rows' => 3,
					'class' => 'form-control',
					'id' => 'css'
				)); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="js"><?php echo __('posts.custom_js'); ?></label>
      <div class="col-lg-10">
      	<?php echo Form::textarea('js', Input::previous('js'), array(
					'placeholder' => __('posts.custom_js_explain'),
					'rows' => 3,
					'class' => 'form-control',
					'id' => 'js'
				)); ?>
      </div>
    </div>

    <?php foreach($fields as $field): ?>
     <div class="form-group">
      <label class="col-lg-2 control-label" for="extend_<?php echo $field->key; ?>"><?php echo $field->label; ?></label>
      <div class="col-lg-4">
        <?php echo Extend::html($field); ?>
      </div>
    </div>
    <?php endforeach; ?>

    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
        <?php echo Form::button(__('global.save'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
      </div>
    </div>

	</fieldset>


</form>

<script src="<?php echo asset('anchor/views/assets/js/slug.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/dragdrop.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/text-resize.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/editor.js'); ?>"></script>
<script>
	$('textarea[name=html]').editor();
</script>

<?php echo $footer; ?>