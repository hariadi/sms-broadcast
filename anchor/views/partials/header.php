<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo __('global.manage'); ?> <?php echo Config::meta('sitename'); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSS -->
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/bootstrap.css')?>">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/bootstrap-theme.css'); ?>">

    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/custom.css'); ?>">

    <?php if ( Uri::current() == 'admin/broadcasts/add' or Uri::current() == 'admin/reports' ) : ?>
    <link rel="stylesheet" href="<?php echo asset('anchor/views/assets/css/bootstrap-datetimepicker.min.css'); ?>">
		<?php endif; ?> 
	</head>
	<body class="<?php echo Auth::guest() ? 'login' : 'admin'; ?>">

		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">

				<div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo Uri::to('/'); ?>"><?php echo Config::meta('sitename'); ?></a>
        </div>

        <nav class="collapse navbar-collapse" role="navigation">
        	<?php if(Auth::user()): ?>
	        <ul class="nav navbar-nav">
	        	
	        	<?php 
						$menu = array('dashboard', 'broadcasts', 'schedules', 'profiles');
						$admin = array(/*'posts', 'comments', 'pages', 'menu', 'categories', 'extend', */'users', 'reports');
						?>
						<?php foreach($menu as $url): ?>
						<li <?php if(strpos(Uri::current(), $url) !== false) echo 'class="active"'; ?>>
							<a href="<?php echo Uri::to('admin/' . $url); ?>">
								<?php echo ucfirst(__($url . '.' . $url)); ?>
							</a>
						</li>
						<?php endforeach; ?>
						<?php if(Auth::user()->role == 'administrator'): ?>
						<li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo __('global.administration'); ?> <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <?php foreach($admin as $url): ?>
								<li <?php if(strpos(Uri::current(), $url) !== false) echo 'class="active"'; ?>>
									<a href="<?php echo Uri::to('admin/' . $url); ?>">
										<?php echo ucfirst(__($url . '.' . $url)); ?>
									</a>
								</li>
								<?php endforeach; ?>
              </ul>
            </li>
						<?php endif; ?>
	        </ul>
	        <?php endif; ?>

	        <ul class="nav navbar-nav navbar-right">
	        	<?php if(Auth::user()): ?>
	        	<li><a href="<?php echo Uri::to('admin/logout'); ?>"><span class="glyphicon glyphicon-off"></span>
 <?php echo __('global.logout'); ?></a></li>
	        	<?php else: ?>
	        	<?php if ( Uri::current() != 'admin/login' ) : ?>
            <li>
							<a href="<?php echo Uri::to('admin/login'); ?>"><?php echo __('global.login'); ?></a>
						</li><?php endif; ?><?php endif; ?>
          </ul>
					
        </nav>

			</div> <!-- //.container -->
		</div> <!-- //.navbar -->

		<div class="container">
      <div class="row">
        <div class="col-lg-12">
            <div class="well">