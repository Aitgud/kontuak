<menu label="Opciones">
    <ul>
    <li><?=$this->Html->link('Nuevo gasto periódico',array('action'=>'add'))?></li>
    </ul>
</menu>

<table>
    <thead>
    <tr>
        <th>Nombre</th>
        <th>cantidad</th>
        <th>periodo</th>
    </tr>
    </thead>
    <tbody>
    <?foreach($movements as $movement):?>
    <tr>
        <td><?=$this->Html->link($movement['PeriodicalMovement']['name'],array('controller'=>'accounts','action'=>'edit_movement',$movement['PeriodicalMovement']['id']))?></td>
        <td><?=$movement['PeriodicalMovement']['amount']?></td>
        <td>
            <?if($movement['PeriodicalMovement']['period_type']=='month_day'):?>
            d�a <?=$movement['PeriodicalMovement']['period_value']?> de más
            <?elseif($movement['PeriodicalMovement']['period_type']=='day_interval'):?>
            cada <?=$movement['PeriodicalMovement']['period_value']?> días
            <?elseif($movement['PeriodicalMovement']['period_type']=='week_day'):?>
            <?=$movement['PeriodicalMovement']['period_value']?>
            <?endif?>
        </td>
    </tr>
        <?endforeach?>
    </tbody>
</table>