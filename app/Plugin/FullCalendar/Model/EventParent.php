<?php

class EventParent extends FullCalendarAppModel {
	var $name = 'EventParent';
	var $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
	);

	public $hasMany = array(
		'Event' => array(
			'className' => 'FullCalendar.Event',
			'foreignKey' => 'parent_id',
			'dependent' => false,
		),
        'EventTag' => array(
            'className'     => 'EventTag',
            'foreignKey'    => 'tag_id',
        ),
	);

	public $hasAndBelongsToMany = array(
        'Tag' =>
            array(
                'className'              => 'Tag',
                'joinTable'              => 'event_parent',
                'foreignKey'             => 'id',
                'associationForeignKey'  => 'id',
                'unique'                 => true,
                'conditions'             => '',
                'fields'                 => '',
                'order'                  => '',
                'limit'                  => '',
                'offset'                 => '',
                'finderQuery'            => '',
                'deleteQuery'            => '',
                'insertQuery'            => ''
            )
    );


}
?>
