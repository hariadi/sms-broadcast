					<?php if(Auth::user()): ?>
					<footer class="wrap bottom">
						<small><?php echo __('global.copyright', date("Y")); ?>.</small>
						<em><?php echo __('global.make_broadcast_easier'); ?></em>
					</footer>
					<?php endif; ?>
					</div>
					<div class="col-md-3">
		        <ul class="list-unstyled">
		          <li>GitHub<li>
		          <li><a href="#">About us</a></li>
		          <li><a href="#">Blog</a></li>
		          <li><a href="#">Contact &amp; support</a></li>
		        </ul>
		      </div>
		      <div class="col-md-3">
		        <ul class="list-unstyled">
		          <li>Product<li>
		          <li><a href="#">SMS Broadcast</a></li>
		          <li><a href="#">Email Broadcast</a></li>
		          <li><a href="#">WhatApps Broadcast</a></li>          
		        </ul>
		      </div>
		      <div class="col-md-3">
		        <ul class="list-unstyled">
		          <li>Services<li>
		          <li><a href="#">Broadcast</a></li>
		          <li><a href="#">Presentations</a></li>
		          <li><a href="#">Code snippets</a></li>      
		        </ul>
		      </div>
		      <div class="col-md-3">
		        <ul class="list-unstyled">
		          <li>Documentation<li>
		          <li><a href="#">Help</a></li>
		          <li><a href="#">Developer API</a></li>
		          <li><a href="#">Product Markdown</a></li>          
		        </ul>
		      </div>  
		    </div>
	    </div>
    </div>
    <script src="//code.jquery.com/jquery-2.0.3.min.js"></script>
    <script src="<?php echo asset('anchor/views/assets/js/bootstrap.min.js')?>"></script>
    <?php  $parts = pathinfo(Uri::current()); if ( Uri::current() == 'admin/broadcasts/add' or $parts['dirname'] == 'admin/schedules/edit') : ?>
    <script src="<?php echo asset('anchor/views/assets/js/bootstrap-filestyle.min.js'); ?>"></script>
    <script src="<?php echo asset('anchor/views/assets/js/bootstrap-datetimepicker.min.js'); ?>"></script>
		<script type="text/javascript">
	    $('#start_date').datetimepicker({
		    format: 'yyyy-mm-dd hh:ii'
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



				/*/select.change(function() {
				    if($(this).val() == 'image') {
				        $('label[for=indOther], #indOther').show();
				    } else {
				        $('label[for=indOther], #indOther').hide();
				    }
				});*/

			});
		</script>
		<?php //endif; ?> 
	</body>
</html>