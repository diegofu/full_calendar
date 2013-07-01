<?php

class Tag extends FullCalendarAppModel {
	var $name = 'Tag';
	

	public $hasMany = array(
        'EventTag' => array(
            'className'     => 'EventTag',
            'foreignKey'    => 'tag_id',
        )
    );


}
?>
