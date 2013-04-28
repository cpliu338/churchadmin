<?php
/**
 * Description of Entry
 *
 * @author Administrator
 */
class Entry extends AppModel {

    const startDateKey = 'Entry.startDate';
    const createDateKey = 'Entry.createDate';
    const filter = 'Entry.filter';
	
    public function getYearStart($date1) {
        if (preg_match('/^20[1-9][1-9]/', $date1, $matches)) {
            return $matches[0].'-01-01';
        }
        return '2010-01-01';
    }
    
	public function setDetail($filter1) {
		App::uses('CakeSession', 'Model/Datasource');
		CakeSession::write(self::filter, $filter1);
	}
	
	public function getDetail() {
		App::uses('CakeSession', 'Model/Datasource');
		if (CakeSession::check(self::filter))
			return CakeSession::read(self::filter);
		CakeSession::write(self::filter, ' ');
		return ' ';
	}
	
	public function setStartDate($date2) {
		if (preg_match('/^20[0-4][0-9]-[0-9]{1,2}-[0-9]{1,2}$/', $date2)) {
			App::uses('CakeSession', 'Model/Datasource');
			CakeSession::write(self::startDateKey, $date2);
		}
	}
	
	public function getStartDate() {
		App::uses('CakeSession', 'Model/Datasource');
		if (CakeSession::check(self::startDateKey))
			return CakeSession::read(self::startDateKey);
		$startDate = date('Y-m-d');
		CakeSession::write(self::startDateKey, $startDate);
		return $startDate;
	}
	
	public function getCreateDate() {
		App::uses('CakeSession', 'Model/Datasource');
		if (CakeSession::check(self::createDateKey))
			return CakeSession::read(self::createDateKey);
		$createDate = $this->getStartDate();
		CakeSession::write(self::createDateKey, $createDate);
		return $createDate;
	}
	
	public function setCreateDate($date2) {
		if (preg_match('/^20[0-4][0-9]-[0-9]{1,2}-[0-9]{1,2}$/', $date2)) {
			App::uses('CakeSession', 'Model/Datasource');
			CakeSession::write(self::createDateKey, $date2);
		}
	}
	
	public function getEndDate($d) {
		//$d = $this->getStartDate(); //e.g. 2013-01-01
		return substr($d,0,4).'-12-31';
	}

    public function nextTransref() {
        $entry = $this->find('first',array(
            'order'=>'transref DESC'
        ));
        return $entry['Entry']['transref']+1;
    }
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'detail';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
            'amount' => array(
                'numberOnly' => array(
                    'rule' => array('numeric'),
                ),
            ),
            'transref' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                ),
            ),
		'date1' => array(
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'account_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		// 'detail' => array(
			// 'notempty' => array(
				// 'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			// ),
		// ),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Account' => array(
			'className' => 'Account',
			'foreignKey' => 'account_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}