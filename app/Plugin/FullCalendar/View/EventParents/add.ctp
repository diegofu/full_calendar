<?php
/*
 * Views/EventTypes/add.ctp
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */
?>
<div class="eventParent form">
<?php echo $this->Form->create('EventParent');?>
	<fieldset>
 		<legend><?php __('Add Event'); ?></legend>
	<?php
		echo $this->Form->input('start', array(
			'type'=>'datetime',
			'timeFormat'=> '24',
			));
		echo $this->Form->input('end', array(
			'type'=>'datetime',
			'timeFormat'=> '24',
			));
		echo $this->Form->input('recurring', array(
			'type'=>'checkbox',
			));
		echo $this->Form->input('recurrence_type', 
					array('options' => array(
						'null' => 'Not Recurring',
						'daily' => 'Daily',
						'weekly' => 'Weekly',
						'onthly' => 'Monthly',
					)));
		echo $this->Form->input('recur_end', array(
			'type'=>'date',
			));
		echo $this->Form->input('title');
		echo $this->Form->input('detail', array('type'=>'textarea'));
		echo $this->Form->input('location');
		echo $this->Form->input('address');
		echo $this->Form->input('city');
		echo $this->Form->input('state');
		echo $this->Form->input('zip_code');

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
	<label for="tags">Tags</label>
	<input name="tags" id="singleFieldTags" value="
	">
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Manage Event Types', true), array('plugin' => 'full_calendar', 'action' => 'index'));?></li>
		<li><li><?php echo $this->Html->link(__('View Calendar', true), array('plugin' => 'full_calendar', 'controller' => 'full_calendar')); ?></li>
	</ul>
</div>
