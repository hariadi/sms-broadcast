					<?php if(Auth::user()): ?>
					<footer class="wrap bottom">
						<small><?php echo __('global.copyright', date("Y")); ?>.</small>
						<em><?php echo __('global.make_broadcast_easier'); ?></em>
					</footer>

					<script>
						// Confirm any deletions
						$('.delete').on('click', function() {return confirm('<?php echo __('global.confirm_delete'); ?>');});
					</script>
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
	</body>
</html>