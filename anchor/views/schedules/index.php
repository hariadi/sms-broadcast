<?php echo $header; ?>

<?php echo Html::link('admin/broadcasts/add', __('schedules.create_schedule'), array('class' => 'btn btn-lg btn-primary pull-right')); ?>

<h1 class="page-header"><?php echo __('schedules.schedules'); ?></h1>

<?php echo $messages; ?>

<div class="row">
	<div class="col col-lg-12">

		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Description</th>
						<th>Schedule</th>
						<th>Created</th>
						<th>Message</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($schedules->results as $key => $schedule): ?>
					<tr class="status draft">
						<td><a href="<?php echo Uri::to('admin/schedules/edit/' . $schedule->id); ?>"><?php echo $schedule->id; ?></a></td>
						<td><?php echo $schedule->description; ?></td>
						<td><?php echo $schedule->schedule; ?></td>
						<td><?php echo Date::format($schedule->created, 'jS F, Y h:i A'); ?></td>
						<td><?php echo $schedule->message; ?></td>
						<td>
							<p>At <?php echo Date::format($schedule->start, 'h:i A'); ?> every <?php echo schedule_name($schedule->week, 'weekly'); ?></p>
							<p><?php echo schedule_name($schedule->month, 'monthly'); ?></p>
							<?php if ($schedule->schedule == 'onetime') : ?><p><?php echo abbreviation($schedule->day); ?></p><?php endif; ?>
							<p>Starting <?php echo Date::format($schedule->start, 'jS F, Y'); ?></p>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php if ($schedules->links()) : ?>
		<ul class="pagination">
			<?php echo $schedules->links(); ?>
		</ul>
		<?php endif; ?>
		
		</div>

	</div>
</div>

<?php echo $footer; ?>