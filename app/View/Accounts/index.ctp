<?=$this->Html->link('Nueva cuenta','add')?>
<table>
	<thead>
		<tr>
			<th><?=__('Nombre')?></th>
			<th><?=__('Entidad')?></th>
			<th><?=__('Disponible')?></th>
            <th><?=__('Principal')?></th>
		</tr>
	</thead>
	<tbody>
		<?foreach($accounts as $account):?>
			    <tr<?if($account['Account']['default']):?> class="default"<?endif?>>
				<td><?=$this->Html->link($account['Account']['name'],array('action'=>'view',$account['Account']['id']))?></td>
				<td><?=$account['Account']['entity_name']?></td>
				<td><?=$account['Account']['amount']?></td>
                <td><?=$account['Account']['default']?'SI':$this->Html->link('NO',array('action'=>'set_default',$account['Account']['id']))?></td>
			</tr>
		<?endforeach?>
	</tbody>
</table>

<script>
    /*
    $('.aitwin_launcher').each(function(){
        $(this).click(function(){
            var original = this;
            var input = $('<input>').blur(function(){
                $(original).show();
                $(this).hide();
            });
            input[0].value = $(this).data().basevalue;
            input.insertBefore($(this).hide()).focus();
            return false;
        });

    })
    */
</script>