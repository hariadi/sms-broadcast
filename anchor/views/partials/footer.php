					<?php if(Auth::user()): ?>
					<footer class="wrap bottom">
						<small><?php echo __('global.copyright', date("Y")); ?>.</small>
						<em><?php echo __('global.make_broadcast_easier'); ?></em>
					</footer>
					<?php endif; ?>
	    </div>
    </div>
    <script src="//code.jquery.com/jquery-2.0.3.min.js"></script>
    <script src="<?php echo asset('anchor/views/assets/js/bootstrap.min.js')?>"></script>
    <?php $parts = pathinfo(Uri::current()); if ( Uri::current() == 'admin/broadcasts/add' or $parts['dirname'] == 'admin/schedules/edit' or Uri::current() == 'admin/reports') : ?>
    <script src="<?php echo asset('anchor/views/assets/js/bootstrap-filestyle.min.js'); ?>"></script>
    <script src="<?php echo asset('anchor/views/assets/js/bootstrap-datetimepicker.min.js'); ?>"></script>
		<script type="text/javascript">
	    $('#start_date, #to_date, #from_date').datetimepicker({
	    	<?php $format = (Uri::current() == 'admin/reports') ? 'yyyy-mm-dd' : 'yyyy-mm-dd hh:ii'; ?>
		    format: '<?php echo $format; ?>'
	    });
		</script>
		<?php endif; ?>
		<?php //if ( Uri::current() ==  Uri::remove('/', Uri::current())) : ?>
		<script type="text/javascript">

		$(document).ready(function() {
				
		    var text_max = 153;

		    $('#message_feedback').html(text_max + ' characters remaining');

		    $('#keyword, #message').keyup(function() {
		    		var text_keyword = $("#keyword").val().length;
		        var text_length = $('#message').val().length;
		        var text_remaining = text_max - (text_length + text_keyword);

		        $('#message_feedback').html(text_remaining + ' characters remaining');
		    });

		    <?php
		    $hide = '#date, #description, #weekly, #monthly, #days';
		    $parts = pathinfo(Uri::current());
		    $current = $parts['dirname'];
		    if ( $current == 'admin/schedules/edit') :
			    if ($schedule->schedule) :
			    	switch ($schedule->schedule) :
			    		case 'daily':
			    			$hide = '#weekly, #monthly, #days';
			    			break;

			    		case 'weekly':
			    			$hide = '"#monthly, #days';
			    			break;

			    		case 'monthly':
			    			$hide = '#weekly';
			    			break;

			    	endswitch;
			    endif;
		  	endif; 
		    ?>

		    $('<?php echo $hide; ?>').hide();

		    $('#schedule').change(function() {
			    if($(this).val() == 'daily') {
			    	$("#date, #description").show();
			    	$('#weekly, #monthly, #days').hide();
			    } else if($(this).val() == 'weekly') {
			    	$("#weekly, #description").show();
			    	$("#monthly, #days").hide();
			    } else if($(this).val() == 'monthly') {
			    	$("#weekly").hide();
			    	$("#description, #monthly, #days").show();
			    } else {
			      $('#description, #date, #weekly, #monthly, #days').hide();
			    }
			});

			$('#account').change(function() {
				var user = $(this).val();
				$.ajax({
					type: "GET",
					url: "/admin/broadcasts/credit/" + user,
					dataType: 'json',
					cache: false,
					success: function(html){
						$("#current_credit").html(html[user]);
					}
				});
			});

			$('#modalConfirmDelete').on('show', function() {
			    var url = $(this).data('url'),
			        removeBtn = $(this).find('.danger');
			    removeBtn.attr('href', url);
			});

			$('.confirm-delete').on('click', function(e) {
			    e.preventDefault();

			    var url = $(this).attr('href');
			    $('#modalConfirmDelete').data('url', url).modal('show');
			});

			$('#btnYes').click(function() {
			  	var url = $('#modalConfirmDelete').data('url');
			  	console.log(url);
			  	$(this).attr('href', url);
			  	$('#modalConfirmDelete').modal('hide');
			});

		});

	    $(function() {

	    	var select = $('#field'), attrs = $('.hide');

				select.click(function(){
					var value = $(this).val();
					console.log(value);

					//$("#my-div").removeClass('hide');
					if(value == 'image') {
						attrs.removeClass('hide');
						$("#my-div").removeClass('hide');
						//attrs.show();
					}
					else if(value == 'file') {
						$('.attributes_type').removeClass('hide');
					}

				});

			});
		</script>
		<?php //endif; ?>
		<!-- Modal -->
		<div class="modal fade" id="modalConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-sm">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel">Delete</h4>
		      </div>
		      <div class="modal-body">
		       <p>You are about to delete, this procedure is irreversible. Do you want to proceed?</p>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        <a class="btn btn-danger" id="btnYes" href="#">Delete</a>
		      </div>
		    </div>
		  </div>
		</div>
	</body>
</html>