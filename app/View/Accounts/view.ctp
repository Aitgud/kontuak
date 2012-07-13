<h1>Cuenta: <?=$account['Account']['name']?></h1>

<dl>
    <dt>Entidad</dt>
        <dd><?=$account['Account']['entity_name']?></dd>
    <dt>Disponible</dt>
        <dd><?=$account['Account']['amount']?></dd>
    <dt>Cuenta por defecto</dt>
    <dd><?=$account['Account']['default']?'SI':$this->Html->link('NO',array('action'=>'set_default',$account['Account']['id']))?></dd>
</dl>


<ul>
    <li><?=$this->Html->link('Nuevo gasto',array('action'=>'movement','account_id'=>$account['Account']['id'],'type'=>'minus'))?></li>
    <li><?=$this->Html->link('Nuevo ingreso',array('action'=>'movement','account_id'=>$account['Account']['id'],'type'=>'plus'))?></li>
</ul>

<div>
    <h2>Últimos movimientos</h2>
    <div>
        <?if(!empty($account['LastMovement'])):?>
            <table>
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                <?foreach($account['LastMovement'] as $movement):?>
                <tr>
                    <td>
                        <?if(empty($movement['name']) && $movement['type']=='absolute'):?>
                            <span class="update"><?=__('Actualización')?></span>
                        <?else:?>
                            <?=$movement['name']?>
                        <?endif?>
                    </td>
                    <td><?=$movement['amount']?></td>
                    <td><?=$movement['date']?></td>
                </tr>
                    <?endforeach?>
                </tbody>
            </table>
        <?else:?>
            <div class="empty-list">No hay movimientos</div>
        <?endif?>
    </div>
</div>

<div>
    <h2>Movimientos pendientes</h2>
    <?if(!empty($account['PendingMovement'])):?>
    <div>
        <table>
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
            </tr>
            </thead>
            <tbody>
                <?foreach($account['PendingMovement'] as $movement):?>
                <tr>
                    <td><?=$movement['name']?></td>
                    <td><?=$movement['amount']?></td>
                </tr>
                <?endforeach?>
            </tbody>
        </table>
    </div>
    <?else:?>
    <div class="empty-list">No hay movimientos pendientes</div>
    <?endif?>
</div>