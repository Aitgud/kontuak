<?
$this->Html->addCrumb('Cuentas',array('controller'=>'accounts','action'=>'index'));
$this->Html->addCrumb($account['Account']['name'],array('action'=>'view',$account['Account']['id']))
?>

<?=$this->Form->create('Movement')?>
<?=$this->Form->input('amount')?>
<?=$this->Form->end('Actualizar')?>

<script>

    /*
    function toTitleCase(str) {
        return str.replace(/(?:^|\s)\w/g, function(match) {
            return match.toUpperCase();
        });
    }


    $('#MovementChangeAmountForm').submit(function(){
        var form = this;
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            success: function(r){
                if(r.result){
                    console.log($(form).parents('.aitwin').data('AitWin').caller);
                    $(form).parents('.aitwin').data('AitWin').kill();
                } else {
                    $.each(r.errors,function(model){
                        $.each(this,function(field){
                            var input = $('#'+model+toTitleCase(field));
                            input
                                .addClass('form-error')
                                .parent()
                                    .addClass('error')
                                    .append(
                                        $('<div>')
                                            .addClass('error-message')
                                            .html(this[0])
                                    );
                        });
                    });
                }
            }
        });
        return false;
        */
    });

</script>