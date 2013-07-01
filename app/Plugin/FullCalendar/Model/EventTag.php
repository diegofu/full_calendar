<?php

class EventTag extends FullCalendarAppModel {
    public $belongsTo = array(
        'EventParent' => array(
            'className'    => 'EventParent',
            'foreignKey'   => 'event_parent_id'
        ),
        'Tag' => array(
        	'className'		=> 'Tag',
        	'foreignKey'    => 'tag_id',
        	),

    );
}

?>