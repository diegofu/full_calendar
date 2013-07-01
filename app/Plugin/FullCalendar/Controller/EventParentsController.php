<?php
/*
 * Controllers/EventTypesController.php
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */
 
class EventParentsController extends FullCalendarAppController {

	var $name = 'EventParents';
    var $paginate = array(
        'limit' => 15
    );

    //public $uses = array('Event', 'EventParent');

    function index() {
		$this->EventParent->recursive = 1;
		$first_event = $this->EventParent->Event->find('first');
		$last_event = $this->EventParent->Event->find('first', array('id'=>'order ASCE'));
		$this->set(array('event_parents', 'first_event', 'last_event'), array($this->paginate(), $first_event, $last_event));

	}

	function add() {
		if (!empty($this->data)) {
			$this->EventParent->create();
			if ($data = $this->EventParent->save($this->data)) {
				$this->loadModel('Event');
				$event_parent_id = $this->EventParent->id;
				$event_parent = $data['EventParent'];
				$start_date = $this->parseDatetime($this->data['EventParent']['start']);
				$end_date = $this->parseDatetime($this->data['EventParent']['end']);
				
				if($event_parent['recurring']) {
					$recur_end = $this->parseDate($this->data['EventParent']['recur_end']);
					while($recur_end > $start_date) {
						$this->Event->create();
						$this->Event->set('start', $start_date);
						$this->Event->set('end', $end_date);
						$this->Event->set('parent_id', $event_parent_id);
						$this->Event->save();
						switch($event_parent['recurrence_type']) {
							case 'daily':
								$start_date = date('Y/m/d H:i:s', strtotime("+1 day", strtotime($start_date)));
								$end_date = date('Y/m/d H:i:s', strtotime("+1 day", strtotime($end_date)));
							break;
							case 'weekly':
								$start_date = date('Y/m/d H:i:s', strtotime("+1 week", strtotime($start_date)));
								$end_date = date('Y/m/d H:i:s', strtotime("+1 week", strtotime($end_date)));
							break;
							case 'monthly':
								$start_date = date('Y/m/d H:i:s', strtotime("+1 month", strtotime($start_date)));
								$end_date = date('Y/m/d H:i:s', strtotime("+1 month",strtotime($end_date)));
							break;
							default:
							// invalid input, do something
								exit;
							break;
						}
						
					}
				}

				// tags 
				$this->loadModel('Tag');
				$this->loadModel('EventTag');
				$tags = explode(',', $this->data['tags']);
				foreach($tags as $tag) {
					$this->Tag->find('first', array('name'=>$tag));
					
					if(empty($this->Tag->data)) {
						$this->Tag->create();
						$this->Tag->set('name', $tag);
						$this->Tag->save();
					} 

					$tag_id = $this->Tag->id;
					$this->EventTag->create();
					$this->EventTag->set('tag_id', $tag_id);
					$this->EventTag->set('event_parent_id', $id);
					$this->EventTag->save();
				}

				$this->Session->setFlash(__('The event has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.', true));
			}
		}

	}

	private function parseDatetime($time) {
		return $time['year'] . '/' . $time['month'] . '/' . $time['day'] . ' ' . $time['hour'] . ':' . $time['min'] . ':00';
	}

	private function parseDate($time) {
		return $time['year'] . '/' . $time['month'] . '/' . $time['day'] . ' 00:00:00';
	}


	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid event', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('event', $this->EventParent->read(null, $id));
	}

	function edit($id = null) {
		$this->loadModel('Tag');
		$this->loadModel('EventTag');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid event', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->EventTag->deleteAll(array('event_parent_id'=>$id), false);

			// tags
			$tags = explode(',', $this->data['tags']);
			foreach($tags as $tag) {
				$this->Tag->find('first', array('name'=>$tag));
				
				if(empty($this->Tag->data)) {
					$this->Tag->create();
					$this->Tag->set('name', $tag);
					$this->Tag->save();
				} 

				$tag_id = $this->Tag->id;
				$this->EventTag->create();
				$this->EventTag->set('tag_id', $tag_id);
				$this->EventTag->set('event_parent_id', $id);
				$this->EventTag->save();
			}

			// events
			if ($this->EventParent->save($this->data)) {
				$this->Session->setFlash(__('The event has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->EventParent->read(null, $id);
			$this->set('event', $this->data);
		}
	
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for event', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Event->delete($id)) {
			$this->Session->setFlash(__('Event deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Event was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}

}
?>