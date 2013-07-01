<?php
/*
 * View/Events/view.ctp
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */
?>
<div class="events view">
<h2><?php echo __('Event'); ?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Event Type'); ?></dt>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Title'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $event['EventParent']['title']; ?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Recurrence Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>

		<?php 
		echo $event['EventParent']['recurrence_type'] ? $event['EventParent']['recurrence_type'] : 'Not Recurring'; 
		?>
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Start'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $event['EventParent']['start']; ?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Recurrence End Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php 
		echo $event['EventParent']['recur_end'] ? $event['EventParent']['recur_end'] : 'Not Recurring'; 
		?>
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Tags'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php 
		foreach($event['Tag'] as $tag)
			echo "<dl class='tabs'>".$tag['name']."</dl>";
		?>
		</dd>
        
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Edit Event', true), array('plugin' => 'full_calendar', 'action' => 'edit', $event['EventParent']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Event', true), array('plugin' => 'full_calendar', 'action' => 'delete', $event['EventParent']['id']), null, sprintf(__('Are you sure you want to delete this %s event?', true))); ?> </li>
		
	</ul>
</div>
