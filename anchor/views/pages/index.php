<?php echo $header; ?>

<?php echo Html::link('admin/pages/add', __('pages.create_page'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>

<h1 class="page-header"><?php echo __('pages.pages'); ?></h1>

<?php echo $messages; ?>

<div class="row">
	<div class="col col-lg-9">

	<nav class="sidebar statuses">
		<?php echo Html::link('admin/pages', '<span class="icon"></span> ' . __('global.all'), array(
			'class' => ($status == 'all') ? 'active' : ''
		)); ?>
		<?php foreach(array('published', 'draft', 'archived') as $type): ?>
		<?php echo Html::link('admin/pages/status/' . $type, '<span class="icon"></span> ' . __('global.' . $type), array(
			'class' => ($status == $type) ? 'active' : ''
		)); ?>
		<?php endforeach; ?>
	</nav>

	<?php if($pages->count): ?>
	<div class="table-responsive">

		<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Title</th>
						<th>Created</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>

		<?php foreach($pages->results as $key => $page): ?>
		<tr class="status draft">
			<td><?php echo $key+1; ?></td>
			<td><a href="<?php echo Uri::to('admin/pages/edit/' . $page->id); ?>" title=""><?php echo $page->name; ?></a></td>
			<td><?php echo Date::format($page->created); ?></td>
			<td><span class="label label-<?php
			$search  = array('published', 'draft', 'archived');
			$replace = array('success', 'primary', 'info');
			echo str_replace($search, $replace, $page->status); 
			?>"><?php echo __('global.' . $page->status); ?></span></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	</table>
	</div>

	<ul class="pagination"><?php echo $pages->links(); ?></ul>
	<?php else: ?>
	<aside class="empty pages">
		<span class="icon"></span>
		<?php echo __('pages.nopages_desc'); ?><br>
		<?php echo Html::link('admin/pages/add', __('pages.create_page'), array('class' => 'btn')); ?>
	</aside>
	<?php endif; ?>
</div>
</div>

<?php echo $footer; ?>