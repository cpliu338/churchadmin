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

}
?>
