<?php echo $header; ?>

<h1 class="page-header"><?php echo __('pages.editing_page', $page->title); ?></h1>

<?php echo $messages; ?>

<form class="form-horizontal" method="post" action="<?php echo Uri::to('admin/pages/edit/' . $page->id); ?>" enctype="multipart/form-data" novalidate>

	<input name="token" type="hidden" value="<?php echo $token; ?>">

	<fieldset class="header">

		<div class="form-group">
      <label class="col-lg-2 control-label" for="title"><?php echo __('pages.title'); ?></label>
      <div class="col-lg-10">
        <?php echo Form::text('title', Input::previous('title', $page->title), array(
					'placeholder' => __('pages.title'),
					'class' => 'form-control',
					'autofocus' => 'true',
					'id' => 'title',
				)); ?>
      </div>
    </div>

    <div class="form-group<?php echo ($page->redirect) ? ' show' : ''; ?>">
      <label class="col-lg-2 control-label" for="title"><?php echo __('pages.redirect'); ?></label>
      <div class="col-lg-10">
        <?php echo Form::text('redirect', Input::previous('redirect', $page->redirect), array(
					'placeholder' => __('pages.redirect_url'),
					'class' => 'form-control',
					'id' => 'redirect',
				)); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="html"><?php echo __('pages.content'); ?></label>
      <div class="col-lg-10">
      	<?php echo Form::textarea('content', Input::previous('content', $page->content), array(
					'rows' => 3,
					'class' => 'form-control',
					'id' => 'content'
				)); ?>
				<p class="help-block"><?php echo __('pages.content_explain'); ?></p>
      </div>

    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="show_in_menu"><?php echo __('pages.show_in_menu'); ?></label>
      <div class="col-lg-1">
        <?php echo Form::checkbox('show_in_menu', 1, Input::previous('show_in_menu', $page->show_in_menu == 1), array(
          'class' => 'checkbox',
          'id' => 'show_in_menu',
        )); ?>
        <p class="help-block"><?php echo __('pages.show_in_menu_explain'); ?></p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="name"><?php echo __('pages.name'); ?></label>
      <div class="col-lg-10">
        <?php echo Form::text('name', Input::previous('name', $page->name), array(
					'placeholder' => __('pages.name_explain'),
					'class' => 'form-control',
					'id' => 'name',
				)); ?>
				<p class="help-block"><?php echo __('pages.name_explain'); ?></p>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="slug"><?php echo __('pages.slug'); ?></label>
      <div class="col-lg-10">
        <?php echo Form::text('slug', Input::previous('slug', $page->slug), array(
					'placeholder' => __('pages.slug_explain'),
					'class' => 'form-control',
					'id' => 'slug',
				)); ?>
				
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="status"><?php echo __('pages.status'); ?></label>
      <div class="col-lg-4">
        <?php echo Form::select('status', $statuses, Input::previous('status', $page->status), array(
          'class' => 'form-control',
          'id' => 'status',
        )); ?>
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-2 control-label" for="parent"><?php echo __('pages.parent'); ?></label>
      <div class="col-lg-4">
        <?php echo Form::select('parent', $pages, Input::previous('parent', $page->parent), array(
          'class' => 'form-control',
          'id' => 'parent',
        )); ?>
        <p class="help-block"><?php echo __('pages.parent_explain'); ?></p>
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
        <?php echo Form::button(__('global.update'), array('type' => 'submit', 'class' => 'btn btn-primary')); ?>

        <?php echo Html::link('admin/pages/delete/' . $page->id, __('global.delete'), array('class' => 'btn btn-danger')); ?>
      </div>
    </div>
		
	</fieldset>


</form>

<script src="<?php echo asset('anchor/views/assets/js/redirect.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/upload-fields.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/text-resize.js'); ?>"></script>
<script src="<?php echo asset('anchor/views/assets/js/editor.js'); ?>"></script>
<script>
	$('textarea[name=content]').editor();
</script>

<?php echo $footer; ?>
