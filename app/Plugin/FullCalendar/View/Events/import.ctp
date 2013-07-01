<?php 
echo $this->Form->create('Event', array('type'=>'file')); 
?>
	<?php
	echo $this->Form->file('file');
	?>

<?php
echo $this->Form->end('Submit');
?>