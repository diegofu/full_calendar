<?php
/*
 * Views/EventTypes/edit.ctp
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */
?>
<div class="eventParents form">
<?php echo $this->Form->create('EventParent');?>
	<fieldset>
 		<legend><?php __('Edit Events'); ?></legend>
	<?php

		echo $this->Form->input('id');
		echo $this->Form->input('title', array('default'=>$event['EventParent']['title']));
		echo $this->Form->input('start', array('default'=>$event['EventParent']['start']));
		echo $this->Form->input('recurrence type', 
					array('options' => array(
						'null' => 'Not Recurring',
						'daily' => 'Daily',
						'weekly' => 'Weekly',
						'onthly' => 'Monthly',
					)));
		echo $this->Form->input('recur end', array('default'=>$event['EventParent']['recur_end'], 'type'=>'time',));
		// echo $this->Form->input('color', 
		// 			array('options' => array(
		// 				'Blue' => 'Blue',
		// 				'Red' => 'Red',
		// 				'Pink' => 'Pink',
		// 				'Purple' => 'Purple',
		// 				'Orange' => 'Orange',
		// 				'Green' => 'Green',
		// 				'Gray' => 'Gray',
		// 				'Black' => 'Black',
		// 				'Brown' => 'Brown'
		// 			)));

	?>
	
	<?php
	echo $this->Html->script(array('/full_calendar/js/jquery-1.5.min', '/full_calendar/js/jquery-ui.min', '/full_calendar/js/tag-it.js'), array('inline' => 'false'));
	echo $this->Html->css('/full_calendar/css/jquery.tagit.css', null, array('inline' => false));
	?>
	<script type="text/javascript">
	    $(document).ready(function() {
	        $('#singleFieldTags').tagit({
            });
	    });
	</script>
	<input name="tags" id="singleFieldTags" value="
		<?php
		foreach($event['Tag'] as $tag) {
			echo $tag['name'];
		}
		?>
	">
	
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('View Event', true), array('plugin' => 'full_calendar', 'action' => 'view', $event['EventParent']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Event', true), array('plugin' => 'full_calendar', 'action' => 'delete', $event['EventParent']['id']), null, sprintf(__('Are you sure you want to delete this %s event?', true))); ?> </li>
		
	</ul>
</div>
