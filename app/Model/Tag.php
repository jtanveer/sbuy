<?php
App::uses('AppModel', 'Model');
/**
 * Tag Model
 *
 */
class Tag extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'tag';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'tag' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 10),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
}
