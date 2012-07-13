<?=$this->Form->create()?>
<?=$this->Form->input('name')?>
<?=$this->Form->input('amount')?>
<?=$this->Form->input('start_date')?>
<?=$this->Form->input('have_end_date',array('type'=>'checkbox'))?>
<?=$this->Form->input('end_date')?>
<?=$this->Form->input('require_accept')?>
<?=$this->Form->input('account_id',array(
    'type'=>isset($accounts)?'select':'hidden'
))?>
<?=$this->Form->input('period_type',array('options'=>array(
    'month_day'=> 'Día del mes',
    'week_day'=> 'Día de la semana',
    'day_interval' => 'Intervalo de días'
)))?>
<?=$this->Form->input('period_value')?>
<?=$this->Form->end('Guardar')?>