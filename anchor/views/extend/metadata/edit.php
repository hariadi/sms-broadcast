<?php echo $header; ?>

<?php echo Html::link('admin/extend', __('extend.extend'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>

<h1 class="page-header"><?php echo __('metadata.metadata'); ?></h1>

<?php echo $messages; ?>

<div class="row">
	<div class="col col-lg-9">

	<form class="form-horizontal"  method="post" action="<?php echo Uri::to('admin/extend/metadata'); ?>" novalidate role="form">

		<input name="token" type="hidden" value="<?php echo $token; ?>">

		<fieldset>
			<legend><?php echo __('metadata.metadata'); ?></legend>
			<div class="form-group">
        <label class="col-lg-2 control-label" for="sitename"><?php echo __('metadata.sitename'); ?></label>
        <div class="col-lg-10">
          <?php echo Form::text('sitename', Input::previous('sitename', $meta['sitename']), array(
						'class' => 'form-control',
						'id' => 'sitename',
					)); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="description"><?php echo __('metadata.sitedescription'); ?></label>
        <div class="col-lg-10">
        	<?php echo Form::textarea('description', Input::previous('description', $meta['description']), array(
						'rows' => 3,
						'class' => 'form-control',
						'id' => 'description'
					)); ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="home_page"><?php echo __('metadata.homepage'); ?></label>
        <div class="col-lg-4">
        	<?php echo Form::select('home_page', $pages, Input::previous('home_page', $meta['home_page']), array(
						'class' => 'form-control',
						'id' => 'home_page'
					)); ?>
        </div>
      </div>

       <div class="form-group">
        <label class="col-lg-2 control-label" for="posts_page"><?php echo __('metadata.postspage'); ?></label>
        <div class="col-lg-4">
        	<?php echo Form::select('posts_page', $pages, Input::previous('posts_page', $meta['posts_page']), array(
						'class' => 'form-control',
						'id' => 'posts_page'
					)); ?>
        </div>
      </div>

       <div class="form-group">
        <label class="col-lg-2 control-label" for="posts_per_page"><?php echo __('metadata.posts_per_page'); ?></label>
        <div class="col-lg-6">
        	<?php echo Form::input('range', 'posts_per_page', Input::previous('posts_per_page', $meta['posts_per_page']), array(
        		'min' => 1,
        		'max' => 15,
						'id' => 'posts_per_page'
					)); ?>
        </div>
      </div>
		
		</fieldset>

		<fieldset>
			<legend><?php echo __('metadata.comment_settings'); ?></legend>

			<div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <div class="checkbox">
		        <label for="auto_published_comments">
		          <?php $checked = Input::previous('auto_published_comments', $meta['auto_published_comments']) ? ' checked' : ''; ?>
		          <?php echo Form::checkbox('auto_published_comments', 1, $checked, array(
								'id' => 'auto_published_comments'
							)); ?>

		          <?php echo __('metadata.auto_publish_comments'); ?>
		        </label>
		      </div>
		    </div>
		  </div>

		  <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <div class="checkbox">
		        <label for="comment_notifications">
		          <?php $checked = Input::previous('comment_notifications', $meta['comment_notifications']) ? ' checked' : ''; ?>
		          <?php echo Form::checkbox('comment_notifications', 1, $checked, array(
								'id' => 'comment_notifications'
							)); ?>

		          <?php echo __('metadata.comment_notifications'); ?>
		        </label>
		      </div>
		    </div>
		  </div>

		  <div class="form-group">
        <label class="col-lg-2 control-label" for="comment_moderation_keys"><?php echo __('metadata.sitedescription'); ?></label>
        <div class="col-lg-10">
        	<?php echo Form::textarea('comment_moderation_keys', Input::previous('comment_moderation_keys', $meta['comment_moderation_keys']), array(
						'rows' => 3,
						'class' => 'form-control',
						'id' => 'comment_moderation_keys'
					)); ?>
        </div>
      </div>
			
		</fieldset>

		<fieldset class="split">
			<legend><?php echo __('metadata.current_theme'); ?></legend>

			<div class="form-group">
        <label class="col-lg-2 control-label" for="theme"><?php echo __('metadata.current_theme'); ?></label>
        <div class="col-lg-4">
        	<select class="form-control" id="theme" name="theme">
						<?php foreach($themes as $theme => $about): ?>
						<?php $selected = (Input::previous('theme', $meta['theme']) == $theme) ? ' selected' : ''; ?>
						<option value="<?php echo $theme; ?>"<?php echo $selected; ?>>
							<?php echo $about['name']; ?> by <?php echo $about['author']; ?>
						</option>
						<?php endforeach; ?>
					</select>
        </div>
      </div>
		</fieldset>

		<fieldset>
			<legend><?php echo __('metadata.isms_setting'); ?></legend>
        
        <div class="form-group">
	        <label class="col-lg-2 control-label" for="isms_username"><?php echo __('metadata.isms_username'); ?></label>
	        <div class="col-lg-6">
	          <?php echo Form::text('isms_username', Input::previous('isms_username', $meta['isms_username']), array(
							'class' => 'form-control',
							'id' => 'isms_username',
						)); ?>
	        </div>
	      </div>

	      <div class="form-group">
	        <label class="col-lg-2 control-label" for="isms_password"><?php echo __('metadata.isms_password'); ?></label>
	        <div class="col-lg-6">
	          <?php echo Form::password('isms_password', array(
							'class' => 'form-control',
							'id' => 'isms_password',
						)); ?>
	        </div>
	      </div>

	      <div class="form-group">
          <label class="col-lg-2 control-label" for="credit_per_sms"><?php echo __('metadata.credit_per_sms'); ?></label>

          <div class="col-lg-4">

            <div class="input-group">
            	<span class="input-group-addon">Credit</span>
              <?php echo Form::text('credit_per_sms', Input::previous('credit_per_sms', $meta['credit_per_sms']), array(
              'class' => 'form-control',
              'id' => 'credit_per_sms',
              )); ?>
              <span class="input-group-addon">/ SMS</span>
            </div>

          </div>
        </div>


      
		</fieldset>

		<aside class="buttons">
			<?php echo Form::button(__('global.save'), array('type' => 'submit', 'class' => 'btn')); ?>
		</aside>
	</form>
	</div>
</div>

<?php echo $footer; ?>