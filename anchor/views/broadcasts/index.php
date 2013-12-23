<?php echo $header; ?>

<?php echo Html::link('admin/broadcasts/add', __('broadcasts.create_broadcast'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>

<h1 class="page-header"><?php echo __('broadcasts.broadcasts'); ?></h1>

<?php echo $messages; ?>

<div class="row">
	<div class="col col-lg-9">

		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Client</th>
						<th>Sender</th>
						<th>Date</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($broadcasts->results as $key => $broadcast): ?>
					<tr class="status draft">
						<td><?php echo $key+1; ?></td>
						<td><a href="<?php echo Uri::to('admin/broadcasts/view/' . $broadcast->id); ?>" title=""><?php echo $broadcast->client_name; ?></a></td>
						<td><?php echo $broadcast->sender; ?></td>
						<td><?php echo Date::format($broadcast->created); ?></td>
						<td><span class="label label-<?php
						$search  = array('success', 'pending', 'failed');
						$replace = array('success', 'primary', 'warning');
						echo str_replace($search, $replace, $broadcast->status); 
						?>"><?php echo __('broadcasts.' . $broadcast->status); ?></span></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php if ($broadcasts->links()) : ?>
		<ul class="pagination">
			<?php echo $broadcasts->links(); ?>
		</ul>
		<?php endif; ?>
		
		</div>

	</div>
	<div class="col col-lg-3">  

		<nav class="list-group sidebar">

			<?php echo Html::link('admin/broadcasts', '<span class="icon"></span> ' . __('global.all'), array(
				'class' => ($status == 'all') ? 'list-group-item active' : 'list-group-item'
				)); ?>

			<?php 
			foreach(array('success', 'pending', 'failed') as $type):

				$status_count = Query::table(Base::table('broadcasts'))->where('status', '=', $type)->count();
			?>
			<?php echo Html::link('admin/broadcasts/status/' . $type, '<span class="icon"></span> ' . __('global.' . $type), array(
				'class' => ($status == $type) ? 'list-group-item active' : 'list-group-item',
				'badge' => $status_count
				)); ?>
			<?php endforeach; ?>
		</nav>
	</div>
</div>

<?php echo $footer; ?>