<!DOCTYPE>
<html>

<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
        echo $this->Html->css('base');
        echo $this->Html->css('AitWin');

		echo $this->fetch('meta');
		echo $this->fetch('css');
        echo $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
        echo $this->Html->script('jQuery.AitWin');
		echo $this->fetch('script');
	?>
</head>

<body>
	<div id="container">
		<div id="header">
			<p id="website-name"><?=$this->Html->link('Contable.org',array('controller'=>'users','action'=>'index'))?></p>
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">

		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>