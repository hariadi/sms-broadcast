<?php echo $header; ?>

<?php echo Html::link('admin/posts/add', __('posts.create_post'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>

<h1 class="page-header"><?php echo __('posts.posts'); ?></h1>

<?php echo $messages; ?>

<div class="row">
	<div class="col col-lg-9">

		<?php if($posts->count): ?>
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
					<?php foreach($posts->results as $key => $article): ?>
					<tr class="status draft">
						<td><?php echo $key+1; ?></td>
						<td><a href="<?php echo Uri::to('admin/posts/edit/' . $article->id); ?>" title=""><?php echo $article->title; ?></a></td>
						<td><?php echo Date::format($article->created); ?></td>
						<td><span class="label label-<?php
						$search  = array('published', 'draft', 'archived');
						$replace = array('success', 'primary', 'info');
						echo str_replace($search, $replace, $article->status); 
						?>"><?php echo __('global.' . $article->status); ?></span></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		</div>
		<?php else: ?>

	<p class="empty posts">
		<span class="icon"></span>
		<?php echo __('posts.noposts_desc'); ?><br>
		<?php echo Html::link('admin/posts/add', __('posts.create_post'), array('class' => 'btn')); ?>
	</p>

	<?php endif; ?>
	</div>
	<div class="col col-lg-3">
	<nav class="list-group sidebar">
		<?php echo Html::link('admin/posts', __('global.all'), array(
			'class' => isset($category) ? 'list-group-item' : 'list-group-item active'
		)); ?>
	    <?php foreach($categories as $cat): ?>
		<?php echo Html::link('admin/posts/category/' . $cat->slug, $cat->title, array(
			'class' => (isset($category) and $category->id == $cat->id) ? 'list-group-item active' : 'list-group-item'
		)); ?>
	    <?php endforeach; ?>
	</nav>

	
</div>
</div>

<?php echo $footer; ?>