<table>
	<thead>
	<tr>
		<th><?=__('Fecha de movimiento')?></th>
		<th><?=__('Confirmado')?></th>
		<th><?=__('Cantidad')?></th>
		<th><?=__('Resultado')?></th>
	</tr>
	</thead>
	<tbody>
	<?foreach($movements as $movement):?>
	<tr>
		<td><?=$movement['Movement']['date']?></td>
		<td><?=$movement['Movement']['confirmed']?></td>
		<td><?=$movement['Movement']['amount']?></td>
		<td><?=$movement['Movement']['account_amount']+$movement['Movement']['amount']?></td>
	</tr>
		<?endforeach?>
	</tbody>
</table>