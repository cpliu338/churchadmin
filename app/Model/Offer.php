<?php
App::uses('AppModel', 'Model');
/**
 * Description of Offer
 *
 * @author Administrator
 */
class Offer extends AppModel {
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Member' => array(
            'className' => 'Member',
            'foreignKey' => 'member_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Account' => array(
                'className' => 'Account',
                'foreignKey' => 'account_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
        )
    );
    var $validate = array(
        'amount' => array(
            'Currency' => array(
	    	'rule' => array('custom', '/^[0-9]+(\.\d{1,2})?$/'),
                'required' => true,
                'message' => 'Valid money format only'
                ),
            'Positive' => array(
                'rule' => array('comparison', '>=', 0),
                'message' => 'Non negative numbers only'
            )
        ),
    );
	
	function getLastSunday() {
		$today = getdate();
		$wkday = $today['wday']; // 0=Sunday
		return date('Y-m-d',  time()- $today['wday']*86400);
	}

}
?>
