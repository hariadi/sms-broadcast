<?php echo $header; ?>

<hgroup class="wrap">
	<h1><?php echo __('broadcasts.broadcasts'); ?></h1>

	<nav>
		<?php echo Html::link('admin/broadcasts/add', __('broadcasts.create_broadcast'), array('class' => 'btn')); ?>
	</nav>
</hgroup>
<section class="wrap">
	<?php echo $messages; ?>

	<?php echo Auth::user()->role; ?>

	<nav class="sidebar statuses">
		<?php echo Html::link('admin/broadcasts', '<span class="icon"></span> ' . __('global.all'), array(
			'class' => ($status == 'all') ? 'active' : ''
		)); ?>
		<?php foreach(array('success', 'pending', 'failed') as $type): ?>
		<?php echo Html::link('admin/broadcasts/status/' . $type, '<span class="icon"></span> ' . __('global.' . $type), array(
			'class' => ($status == $type) ? 'active' : ''
		)); ?>
		<?php endforeach; ?>
	</nav>

	<ul class="main list">
		<?php foreach($broadcasts->results as $broadcast): ?>
		<li>
			<a href="<?php echo Uri::to('admin/broadcasts/view/' . $broadcast->id); ?>">
				<strong><?php echo $broadcast->client_name; ?>: <?php echo $broadcast->id; ?></strong>
				<span>
					<time><?php echo Date::format($broadcast->created); ?></time>

					<em class="status <?php echo $broadcast->status; ?>" title="<?php echo __('global.' . $broadcast->status); ?>">
						<?php echo __('global.' . $broadcast->status); ?>
					</em>
				</span>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<aside class="paging"><?php echo $broadcasts->links(); ?></aside>
</section>

<?php echo $footer; ?>