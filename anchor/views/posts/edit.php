<?php echo $header; ?>

<h1 class="page-header"><?php echo __('posts.editing_post', $article->title); ?></h1>

<?php echo $messages; ?>

<form class="form-horizontal" method="post" action="<?php echo Uri::to('admin/posts/edit/' . $article->id); ?>" enctype="multipart/form-data" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<fieldset class="header">

		 <div class="form-group">
        <label class="col-lg-2 control-label" for="title"><?php echo __('posts.title'); ?></label>
        <div class="col-lg-10">
          <?php echo Form::text('title', Input::previous('title', $article->title), array(
						'placeholder' => __('posts.title'),
						'class' => 'form-control',
						'id' => 'title',
					)); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="html"><?php echo __('posts.content'); ?></label>
        <div class="col-lg-10">
        	<?php echo Form::textarea('html', Input::previous('html', $article->html), array(
						'placeholder' => __('posts.content_explain'),
						'rows' => 3,
						'class' => 'form-control',
						'id' => 'html'
					)); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="slug"><?php echo __('posts.slug'); ?></label>
        <div class="col-lg-10">
        	<?php echo Form::text('slug', Input::previous('slug', $article->slug), array(
						'class' => 'form-control',
						'id' => 'slug',
					)); ?>
					<p class="help-block"><?php echo __('posts.slug_explain'); ?></p>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="description"><?php echo __('posts.description'); ?></label>
        <div class="col-lg-10">
        	<?php echo Form::textarea('description', Input::previous('description', $article->description), array(
						'rows' => 3,
						'class' => 'form-control',
						'id' => 'description'
					)); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="status"><?php echo __('posts.status'); ?></label>
        <div class="col-lg-4">
          <?php echo Form::select('status', $statuses, Input::previous('status', $article->status), array(
            'class' => 'form-control',
            'id' => 'status',
          )); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="category"><?php echo __('posts.category'); ?></label>
        <div class="col-lg-4">
          <?php echo Form::select('category', $categories, Input::previous('category', $article->category), array(
            'class' => 'form-control',
            'id' => 'category',
          )); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="comments"><?php echo __('posts.allow_comments'); ?></label>
        <div class="col-lg-1">
          <?php echo Form::checkbox('comments', 1, Input::previous('comments', $article->comments == 1), array(
            'class' => 'checkbox',
            'id' => 'comments',
          )); ?>
        </div>
      </div>

      <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <div class="checkbox">
		        <label for="comments"><?php echo __('posts.allow_comments'); ?>
		          <?php $checked = Input::previous('comments', $article->comments == 1) ? ' checked' : ''; ?>
		          <?php echo Form::checkbox('comments', 1, $checked, array(
								'id' => 'comments'
							)); ?>
		        </label>
		      </div>
		    </div>
		  </div>

      <p>
				<label><?php echo __('posts.allow_comments'); ?>:</label>
				<?php echo Form::checkbox('comments', 1, Input::previous('comments', $article->comments) == 1); ?>
				<em><?php echo __('posts.allow_comments_explain'); ?></em>
			</p>


		<div class="wrap">

			<?php echo Form::text('title', Input::previous('title', $article->title), array(
				'placeholder' => __('posts.title'),
				'autocomplete'=> 'off',
				'autofocus' => 'true'
			)); ?>

			<aside class="buttons">
				<?php echo Form::button(__('global.save'), array(
					'type' => 'submit',
					'class' => 'btn'
				)); ?>

				<?php echo Html::link('admin/posts/delete/' . $article->id, __('global.delete'), array(
					'class' => 'btn delete red'
				)); ?>
			</aside>
		</div>
	</fieldset>

	<fieldset class="main">
		<div class="wrap">
			<?php echo Form::textarea('html', Input::previous('html', $article->html), array(
				'placeholder' => __('posts.content_explain')
			)); ?>

			<?php echo $editor; ?>
		</div>
	</fieldset>

	<fieldset class="meta split">
		<div class="wrap">
			
			<p>
				<label><?php echo __('posts.allow_comments'); ?>:</label>
				<?php echo Form::checkbox('comments', 1, Input::previous('comments', $article->comments) == 1); ?>
				<em><?php echo __('posts.allow_comments_explain'); ?></em>
			</p>
			<p>
				<label><?php echo __('posts.custom_css'); ?>:</label>
				<?php echo Form::textarea('css', Input::previous('css', $article->css)); ?>
				<em><?php echo __('posts.custom_css_explain'); ?></em>
			</p>
			<p>
				<label for="js"><?php echo __('posts.custom_js'); ?>:</label>
				<?php echo Form::textarea('js', Input::previous('js', $article->js)); ?>
				<em><?php echo __('posts.custom_js_explain'); ?></em>
			</p>
			<?php foreach($fields as $field): ?>
			<p>
				<label for="<?php echo $field->key; ?>"><?php echo $field->label; ?>:</label>
				<?php echo Extend::html($field); ?>
			</p>
			<?php endforeach; ?>
		</div>
	</fieldset>
</form>

<script src="<?php echo asset('anchor/views/assets/js/dragdrop.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/text-resize.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/editor.js'); ?>"></script>
<script>
	$('textarea[name=html]').editor();
</script>

<?php echo $footer; ?>