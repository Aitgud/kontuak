<?$this->Html->script('Graph',false)?>

<script>
<?$this->Html->scriptStart(array('inline'=>false))?>
$(function(){

    var graph = new Graph().setData(jQuery.parseJSON('<?=json_encode($planning)?>'));
    $('#graphic').html(graph.draw());

    /*
    $('#graphic-data-selector a').click(function(){
        graph.setData(graphData[/^#graph-(.*)$/.exec($(this).attr('href'))[1]]);
        $('#graphic').html(graph.draw());
    });
    */

});
<?$this->Html->scriptEnd()?>
</script>

<style>

    #graphic {height:400px; border:1px solid; position:relative;}
    #graphic .day {height:100%; position:absolute;}
    #graphic .day .block {position:absolute; background:#00D2FF; bottom:0; left:0; bottom:0; margin:0 1%; width:98%;}
    #graphic .day .block.diff {background:red; opacity:0.3;}
    #graphic .day .block.estimated {background: #ff2900; opacity:0.5}
    #graphic .day.today .block {background: #00a8cc;}
    #graphic .day .amount {position:absolute; width:100%; text-align:center; top:-1.3em; display:none;}
    #graphic .day:hover .amount {display:block;}
    #graphic .day .date {position:absolute; width:100%; text-align:center; bottom:0;}
    #graphic .day .date .day {position:static}

    table {clear:none;}
    ul {list-style: none; padding:0; margin:0;}
    li {margin:0;}

    .clear {clear:both}

    #content {background:none; padding:0;}
    .box {background:#fff; padding:1%; margin: 0% 1% 1% 0; border-radius:1em;}
    .box:last-child {margin-right:0}

    #main_info {clear:both; margin-right:0;}
    #main_info .column.right{
        float: right;
        width: 240px;
    }
    #main_info .column.left{
        margin-right:250px;
    }
    .columns{clear:both}

    .column {
        overflow: hidden;

    }

    .columns-4 .column {
        width:22.2%;
        min-width: 24em;
        height:30em;
        float:left;
    }


    table .today {background:#eee;}

    .movement-list>li {margin:0; padding: 0.5em 1%;}
    .movement-list>li:hover {background:#ffd;}

    .movement-list a {text-decoration: none;}
    .movement-list .amount {border-left: 1px solid #BBBBBB; float: right; text-align: right; width: 6em;}
    .movement-list .date {font-size:0.8em; color:#aaa;}
    .movement-list ul.tagList {display: inline; font-size: 0.6em;}
    .movement-list ul.tagList li {background: none repeat scroll 0 0 #edffff;
        border-radius: 1em 1em 1em 1em;
        box-shadow: 1px 1px 0 0 #93A8CF;
        display: inline;
        padding: 0 0.5em;}

    .max-min {font-size:0.8em;}
    .money.plus {color:green;}
    .money.minus {color:red;}

    ul.buttons.vertical li {margin:0.5em 0;}
    ul.buttons.vertical li:first-child {margin-top: 0;}
    ul.buttons.vertical li:last-child {margin-bottom: 0;}


</style>



<div id="main_info" class="box">

    <div class="column right">

        <ul id="graphic-data-selector" class="buttons vertical">
            <li><?=$this->Html->link('Datos estimados','#graph-estimated',array('class'=>'button'))?></li>
            <li><?=$this->Html->link('Datos reales','#graph-real',array('class'=>'button'))?></li>
        </ul>

        <?/*
        <div id="accounts-filter">
            <?foreach($accounts as $accountId=>$accountName):?>
            <?=
            $this->Form->input('account_id.'.$accountId, array(
                'type'=>'checkbox',
                'label'=> $accountName
            ))
            ?>
            <?endforeach?>
        </div>
        */?>
    </div>

    <div id="graphics" class="column left">

        <div id="graphic"></div>

    </div>

    <div style="clear:both">&nbsp;</div>

</div>

<div class="columns columns-4">

    <div class="column box">
        <h2>Movimientos por confirmar</h2>
        <div>
            <ul class="movement-list">
                <?foreach($user['UnconfirmedMovement'] as $movement):?>
                <li>
                    <?=$this->Html->link($movement['name'],array('controller'=>'movements','action'=>'edit',$movement['id']))?>
                    <?if(!empty($movement['Tag'])):?>
                    <ul class="tagList">
                        <?foreach($movement['Tag'] as $tag):?>
                            <li><?=$tag['name']?></li>
                        <?endforeach?>
                    </ul>
                    <?endif?>
                    <div class="amount"><?=mo($movement)?>&nbsp</div>
                    <div class="date"><?=$movement['date']?></div>
                    <div class="clear"></div>
                </li>
                <?endforeach?>
            </ul>
        </div>
    </div>

    <div class="column box">
        <h2>Movimientos pendientes</h2>
        <div>
            <ul class="movement-list">
                <?foreach($user['PendingMovement'] as $movement):?>
                <li>
                    <?=$this->Html->link($movement['name'],array('controller'=>'movements','action'=>'edit',$movement['id']))?>
                    <?if(!empty($movement['Tag'])):?>
                    <ul class="tagList">
                        <?foreach($movement['Tag'] as $tag):?>
                        <li><?=$tag['name']?></li>
                        <?endforeach?>
                    </ul>
                    <?endif?>
                    <div class="amount"><?=mo($movement)?>&nbsp</div>
                    <div class="clear"></div>
                </li>
                <?endforeach?>
            </ul>
        </div>
    </div>


    <div class="column box">
        <h2>Últimos movimientos</h2>
        <div>
            <ul class="movement-list">
                <?foreach($user['LastMovement'] as $movement):?>
                <li>
                    <?=$this->Html->link($movement['name'],array('controller'=>'movements','action'=>'edit',$movement['id']))?>
                    <?if(!empty($movement['Tag'])):?>
                    <ul class="tagList">
                        <?foreach($movement['Tag'] as $tag):?>
                        <li><?=$tag['name']?></li>
                        <?endforeach?>
                    </ul>
                    <?endif?>
                    <div class="amount"><?=mo($movement)?></div>
                    <div class="date"><?=$movement['date']?></div>
                    <div class="clear"></div>
                </li>
                <?endforeach?>
            </ul>
        </div>
    </div>

    <div class="column box">
        <h2>Próximos movimientos</h2>
        <div>
            <ul class="movement-list">
                <?foreach($user['ComingMovement'] as $movement):?>
                <li>
                    <?=$this->Html->link($movement['name'],array('controller'=>'movements','action'=>'edit',$movement['id']))?>
                    <?if(!empty($movement['Tag'])):?>
                    <ul class="tagList">
                        <?foreach($movement['Tag'] as $tag):?>
                        <li><?=$tag['name']?></li>
                        <?endforeach?>
                    </ul>
                    <?endif?>
                    <div class="amount"><?=m($movement['amount'])?>&nbsp</div><br/>
                    <div class="date"><?=$movement['date']?></div>
                    <div class="clear"></div>
                </li>
                <?endforeach?>
            </ul>
        </div>
    </div>

</div>