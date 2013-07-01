<?php
/*
 * Controller/EventsController.php
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */

class EventsController extends FullCalendarAppController {

	var $name = 'Events';

    var $paginate = array(
        'limit' => 15
    );

    //public $uses = array('Event', 'EventParent');

    function index() {
		$this->Event->recursive = 1;
		$this->set('events', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid event', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('event', $this->Event->read(null, $id));
	}

	/*
	Column 1: start date of event, yyyy-mm-dd hh:mm:ss, required
	Column 2: end date of event, yyyy-mm-dd hh:mm:ss, required
	Column 3: whether this event is recurring. 1 for yes, 0 for no, required
	Column 4: type of recurrence. daily, weekly, or monthly only, required if recur is 1
	Column 5: the date the event will recur until, yyyy-mm-dd, required if recur is 1
	Column 6: title of event, up to 50 characters, required
	Column 7: details of the event, as many characters as you'd like, required
	Column 8: name of location of event, up to 50 characters
	Column 9: address of event, up to 100 characters)
	Column 10: city of event, up to 50 characters
	Column 11: state of event, up to 50 characters
	Column 12: zip code of event, up to 20 characters
	*/
	function import() {
		
		$this->loadModel('EventParent');
		// have to clear up to use tmp file instead of creating files!!!
		$event_array = array(
			'start', 'end', 'recurring', 'recurrence_type', 'recur_end', 'title', 'detail', 'location', 'address', 'city', 'state', 'zip_code'
		);

		$event_records = null;
		$this->set('sidebar', false);
		if(!empty($this->data)) {
			$file = $this->request->data['Event']['file']['name'];
			$tmp_name = $this->request->data['Event']['file']['tmp_name'];
			
			$file_parts = pathinfo($file);
			
			$tmp_file = 'calendar.'.$file_parts['extension'];
			// !!!! why can't i read tmp ???? //
			move_uploaded_file($tmp_name, $tmp_file);

			// excel file
			if($file_parts['extension'] == 'xlsx') {
				
				App::import('Vendor', 'PHPExcel/Classes/PHPExcel/IOFactory');
				$objReader = new PHPExcel_Reader_Excel2007();
				//$objReader->setReadDataOnly(true);
				$objReader = $objReader->load($tmp_file);

				$sheet = $objReader->getSheet(0);
				$data =  array();
				//$objReader->getActiveSheet()->getStyle('A1')->getNumberFormat()->setFormatCode('yyyy-mm-dd');
				$worksheet = $objReader->getActiveSheet();
				$event_records = $worksheet->toArray(null, false, false);

				// really stupid
				for($i=0;$i<count($event_records);$i++){
					$dateObj = PHPExcel_Shared_Date::ExcelToPHPObject($event_records[$i][0]);
					$event_records[$i][0] = $dateObj->format('Y-m-d H:i:s');
					$dateObj = PHPExcel_Shared_Date::ExcelToPHPObject($event_records[$i][1]);
					$event_records[$i][1] = $dateObj->format('Y-m-d H:i:s');
					if(isset($event_records[$i][4])) {
						$dateObj = PHPExcel_Shared_Date::ExcelToPHPObject($event_records[$i][4]);
						$event_records[$i][4] = $dateObj->format('Y-m-d H:i:s');
					}
				}
			} 
			// tab deliminated file
			elseif($file_parts['extension'] == 'txt') {
				$event_records = file($file);
				for($i = 0; $i<count($event_records);$i++) {
					$event_records[$i] = explode("\t", $event_records[$i]);
					
				}
			} 
			else {
				// wrong file type
			}
			
			if(!isset($event_records)) {
				// something wrong
			}

			foreach($event_records as $event) {
				$record = $this->bind_key_value($event_array, $event);
				$this->Event->create();
				$this->EventParent->create();

				// this should be a transaction

				$this->EventParent = $this->assign_model_data($this->EventParent, $record);
				
				$this->EventParent->save();

				$parent_id = $this->EventParent->id;

				$this->Event = $this->assign_model_data($this->Event, $record);
				$this->Event->set('parent_id', $parent_id);

				// if is recurrence events, we insert multiple records into events table
				if($record['recurring'] == '1') {
					$start_date = date('Y/m/d H:i:s', strtotime($record['start']));
					$end_date = date('Y/m/d H:i:s', strtotime($record['end']));
					$recur_end = date('Y/m/d H:i:s', strtotime($record['recur_end']));
					
					while($recur_end > $start_date) {
						$this->Event->set('start', $start_date);
						$this->Event->set('end', $end_date);
						
						switch($record['recurrence_type']) {
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
							break;
						}

						$this->Event->save();
						$this->Event->create();
						$this->Event = $this->assign_model_data($this->Event, $record);
						$this->Event->set('parent_id', $parent_id);
						
					}
				}
				// else a single record would be inserted
				else {
					$this->Event->save();
				}
			}
		}
		
	}

	private function bind_key_value($keys, $values) {
		if(!is_array($keys) or !is_array($values)) {
			return false;
		}
		if(count($keys) != count($values)) {
			return false;
		}
		$array =  array();
		for($i = 0; $i < count($keys); $i++) {
			$array[$keys[$i]] = $values[$i];
		}

		return $array;
	}

	private function assign_model_data($model, $record) {
		foreach($model->getColumnTypes() as $key=>$value) {
			if(!array_key_exists($key, $record)) {
				continue;
			}

			if($value=='datetime' and $record[$key] > 0) {
				$date = strtotime($record[$key]);
				$record[$key] = date('Y/m/d H:i:s', $date);
			} 
			else if ($value =='datetime') {
				$record[$key] = NULL;
			}
			$model->set($key, $record[$key]);
		}
		return $model;
	}
	// function add() {
	// 	if (!empty($this->data)) {
	// 		$this->Event->create();
	// 		if ($this->Event->save($this->data)) {
	// 			$this->Session->setFlash(__('The event has been saved', true));
	// 			$this->redirect(array('action' => 'index'));
	// 		} else {
	// 			$this->Session->setFlash(__('The event could not be saved. Please, try again.', true));
	// 		}
	// 	}

	// }


    // The feed action is called from "webroot/js/ready.js" to get the list of events (JSON)
	function feed($id=null) {
		$this->layout = "ajax";
		$vars = $this->params['url'];
		$conditions = array('conditions' => array('UNIX_TIMESTAMP(Event.start) >=' => $vars['start'], 'UNIX_TIMESTAMP(end) <=' => $vars['end']));
		$events = $this->Event->find('all', $conditions);
		
		
		foreach($events as $event) {
			$data[] = array(
					'allDay' => false,
					'id' => $event['Event']['id'],
					'start'=>$event['Event']['start'],
					'end' => $event['Event']['end'],
					'title'=>$event['EventParent']['title'],
					'details' => $event['EventParent']['detail'],
					'location' => $event['EventParent']['location'],
					'address' => $event['EventParent']['address'],
					'city' => $event['EventParent']['city'],
					'state' => $event['EventParent']['state'],
					'zip_code' => $event['EventParent']['zip_code'],
					'url' => Router::url('/') . 'full_calendar/event_parents/view/'.$event['EventParent']['id'],
			);
		}
		$this->set("json", json_encode($data));
	}

        // The update action is called from "webroot/js/ready.js" to update date/time when an event is dragged or resized
	function update() {
		$this->autoRender = false;
		$vars = $this->params['url'];
		$this->Event->id = $vars['id'];
		$this->Event->saveField('start', $vars['start']);
		$this->Event->saveField('end', $vars['end']);

	}

}
?>
