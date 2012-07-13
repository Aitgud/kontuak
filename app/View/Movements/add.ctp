<script>
    $(function(){

        dataEmpty = function(){
            var targets = $('#MovementDateMonth, #MovementDateDay, #MovementDateYear');
            if($('#MovementDateEmpty')[0].checked){
                targets.attr('disabled','disabled');
                $('#MovementConfirmed').attr('disabled','disabled');
            } else {
                targets.attr('disabled',null);
                $('#MovementConfirmed').attr('disabled',null);
            }
        }
        $('#MovementDateEmpty').change(dataEmpty);
        dataEmpty();

        maxMinAmount = function(){
            if($('#MovementAmountMaxmin')[0].checked){
                $('#max_min_amount').show();
                $('#MovementAmount').attr('disabled','disabled');
            } else {
                $('#max_min_amount').hide();
                $('#MovementAmount').attr('disabled',null);
            }
        }
        $('#MovementAmountMaxmin').change(maxMinAmount);
        maxMinAmount();
    })
</script>
<?
$this->Html->addCrumb('');
?>
<?=$this->Form->create('Movement')?>
<?=$this->Form->input('id')?>
<?=$this->Form->input('name',array('label'=>'Concepto'))?>
<?=$this->Form->input('tags',array('label'=>'Etiquetas'))?>
<?=$this->Form->input('account_id',array('empty'=>'ninguna','default'=>$defaultAccountId,'label'=>'Cuenta'))?>
<?=$this->Form->input('amount',array('label'=>'Cantidad'))?>
<?=$this->Form->input('Movement.amount_maxmin',array(
    'type'=>'checkbox',
    'label'=>__('Definir un máximo y un mínimo')
))?>
<div id="max_min_amount">
<?=$this->Form->input('min_amount',array('label'=>'Mínimo'))?>
<?=$this->Form->input('max_amount',array('label'=>'Máximo'))?>
</div>
<?=$this->Form->input('confirmed',array('default'=>true,'label'=>'Confirmado'))?>
<?=$this->Form->input('date',array(
    'empty'=>true,
    'label'=>'Fecha',
    'selected'=>empty($this->request->data['Movement'])?date('Y-m-d'):null
))?>
<?=$this->Form->input('Movement.date_empty',array(
    'type'=>'checkbox',
    'label'=>__('Movimiento sin fecha')
))?>

<?=$this->Form->end('Guardar')?>