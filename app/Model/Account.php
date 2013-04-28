<?php
App::uses('AppModel', 'Model');
/**
 * Account Model
 *
 */
class Account extends AppModel {

/**
 * Display field
 *
 * @var string
 */
    public $displayField = 'name';

    public function getPatternUnder($code) {
            $c = $code;
            if (substr($c,-1)=='0') {
                    $c[strlen($c)-1]='%';
            }
            return $c;
    }
    /**
     * Check whether the account is a checking account.  Entry's extra1 attribute will be the cheque no.
     * @param type $id
     */
    public function isChecking($id) {
        return ($id==11201);
    }

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name_chi' => array(
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
}
