<?php
/*
 * View/Events/index.ctp
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */
?>
<div class="events index">
	<h2><?php __('Events');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			
			<th><?php echo $this->Paginator->sort('title');?></th>
			
			<th><?php echo $this->Paginator->sort('start');?></th>
            <th><?php echo $this->Paginator->sort('end');?></th>
            <th><?php echo $this->Paginator->sort('location');?></th>
            <th><?php echo $this->Paginator->sort('address');?></th>
            <th><?php echo $this->Paginator->sort('city');?></th>
            <th><?php echo $this->Paginator->sort('state');?></th>
            <th><?php echo $this->Paginator->sort('zip_code');?></th>
			<th class="actions"></th>
	</tr>
	<?php
	$i = 0;
	foreach ($events as $event):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		
		<td><?php echo $event['EventParent']['title']; ?></td>
		
		<td><?php echo $event['Event']['start']; ?></td>
        
   		<td><?php echo $event['Event']['end']; ?></td>
        <td><?php echo $event['EventParent']['location']; ?></td>
       	<td><?php echo $event['EventParent']['address']; ?></td>
       	<td><?php echo $event['EventParent']['city']; ?></td>
       	<td><?php echo $event['EventParent']['state']; ?></td>
       	<td><?php echo $event['EventParent']['zip_code']; ?></td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $event['Event']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $event['Event']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>

