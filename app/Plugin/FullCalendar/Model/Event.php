<?php
/*
 * Model/Event.php
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */
 
class Event extends FullCalendarAppModel {
	var $name = 'Event';
	
	var $validate = array(
		'start' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'end' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
	);
	public $belongsTo = array(
		'EventParent' => array(
			'className' => 'FullCalendar.EventParent',
			'foreignKey' => 'parent_id'
		)
	);

}
?>
