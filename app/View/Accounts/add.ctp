<?
$this->Html->addCrumb('Cuentas', array('action'=>'index'))
?>

<?=$this->Form->create()?>
<?=$this->Form->input('id')?>
<?=$this->Form->input('name',array('label'=>__('Nombre de la cuenta')))?>
<?=$this->Form->input('entity_name',array('label'=>__('Nombre de la entidad')))?>
<?if(empty($this->request->data['Account']['id'])):?>
    <?=$this->Form->input('Movement.amount',array('label'=>__('Dinero en la cuenta')))?>
<?endif?>
<?=$this->Form->input('default',array('label'=>__('Cuenta principal')))?>
<?=$this->Form->end('Crear')?>