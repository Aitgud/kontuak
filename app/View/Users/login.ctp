<script type="text/javascript">
$(function(){
    $('#UserLoginForm').submit(function(){
        if(($('#UserKeepConnected')[0].checked)){
            return confirm("¿Estás seguro de querer mantenerte conectado en este equipo?\nHazlo solo si este es tu equipo personal.");
        }
    });
});
</script>

<div id="login">
    <?=$this->Session->flash('auth')?>
    <?=$this->Form->create('User')?>
    <?=$this->Form->input('email')?>
    <?=$this->Form->input('password')?>
    <?=$this->Form->input('keepConnected',array('type'=>'checkbox','label'=>__('Mantenerme conectado')))?>
    <?=$this->Form->input('Accede',array('type'=>'submit','label'=>false,'name'=>'action_login'))?>
    <?=$this->Form->input('Nueva cuenta',array('type'=>'submit','label'=>false,'name'=>'action_add'))?>
    <?=$this->Form->end()?>
</div>