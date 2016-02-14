<?php
App::uses('AppModel', 'Model');
/**
 * Order Model
 *
 */
class Order extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'product_code';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'product_code' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'phone' => array(
			'phone' => array(
				'rule' => array('phone', '/^(?:\+?88)?01[15-9]\d{8}$/', 'bd'),
				'message' => 'malformed phone',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
}
