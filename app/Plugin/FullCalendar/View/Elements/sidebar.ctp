<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('New Event', true), array('plugin' => 'full_calendar', 'controller'=>'event_parents', 'action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Manage Events', true), array('plugin' => 'full_calendar', 'controller' => 'event_parents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('View Calendar', true), array('plugin' => 'full_calendar', 'controller' => 'full_calendar')); ?></li>
	</ul>
</div>