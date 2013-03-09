<?php
App::uses('AppModel', 'Model');
/**
 * Transaction Model
 *
 * @property Tran $Tran
 * @property Account $Account
 */
class Transaction extends AppModel {

	const startDateKey = 'Transaction.startDate';
	
	public function getStartDate() {
		App::uses('CakeSession', 'Model/Datasource');
		if (CakeSession::check(self::startDateKey))
			return CakeSession::read(self::startDateKey);
		$startDate = date('Y-m-d');
		CakeSession::write(self::startDateKey, $startDate);
		return $startDate;
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
		'tran_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
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
		'detail' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
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
