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
            <menu>
                <ul>
                    <li><?=$this->Html->link('Nuevo movimiento',array('controller'=>'movements','action'=>'add','type'=>'minus'),array('id'=>'new_movement'))?></li>
                    <li><?=$this->Html->link('Index',array('controller'=>'users','action'=>'index'))?></li>
                    <li><?=$this->Html->link('Cuentas','/accounts')?></li>
                    <?/*<li><?=$this->Html->link('Movimientos periódicos','/PeriodicalMovements')?></li>*/?>
                    <li><?=$this->Html->link('Logout','/users/logout')?></li>
                </ul>
            </menu>
		</div>
		<div id="content">
            <?if(!isset($noDrawHeader)):?>
            <div id="page-title">
                <h1><?=$title_for_layout?></h1>
                <div id="crumbs">
                    <?=$this->Html->getCrumbList(null, array(
                        'text' => 'Inicio',
                        'url' => array('controller'=>'users','action'=>'index'),
                        'escape' => false
                    ))?>
                </div>
            </div>
            <?endif?>
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">

		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>