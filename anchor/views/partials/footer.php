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
    <?php if ( Uri::current() == 'admin/broadcasts/add') : ?>
    <script src="<?php echo asset('anchor/views/assets/js/bootstrap-filestyle.min.js'); ?>"></script>
    <script src="<?php echo asset('anchor/views/assets/js/bootstrap-datetimepicker.min.js'); ?>"></script>
		<script type="text/javascript">
	    $('#schedule').datetimepicker({
		    format: 'yyyy-mm-dd hh:ii'
	    });
		</script>
		<?php endif; ?> remove($value, $uri)
		<?php //if ( Uri::current() ==  Uri::remove('/', Uri::current())) : ?>
		<script type="text/javascript">
	    $(function() {

	    	var select = $('#field'), attrs = $('.hide');

				select.click(function(){
					var value = $(this).val();
					console.log(value);

					//$("#my-div").removeClass('hide');
					if(value == 'image') {
						attrs.removeClass('hide');
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