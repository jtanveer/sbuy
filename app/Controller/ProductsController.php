<?php
App::uses('AppController', 'Controller');
/**
 * Products Controller
 */
class ProductsController extends AppController {

	public $components = array('RequestHandler');

	public $uses = array('Product','Order');

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;

	public function view($code) {
		$this->autoRender = false; // no view to render
    	$this->response->type('json');
        $product = $this->Product->find('first', array(
        	'conditions' => array('Product.code' => $code),
        	'recursive' => -1
    	));
    	// Remove Product from array and encode
	    if (count($product)) {
	    	$product = $product['Product'];
	    }
	    $json = json_encode($product);
	    $this->response->body($json);
    }

    public function search($tag) {
        $products = $this->Product->find('all', array(
        	'joins' => array(
        		array('table' => 'tags',
			        'alias' => 'Tag',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'Tag.product_id = Product.id',
			        )
		    	)
    		), 
        	'conditions' => array('MATCH(Tag.tag) AGAINST(? IN BOOLEAN MODE)' => $tag),
        	'recursive' => -1
    	));
        $this->set(array(
            'products' => $products,
            '_serialize' => array('products')
        ));
    }

    public function order($code, $phone) {
        $products = $this->Product->find('first', array(
        	'conditions' => array('Product.code' => $code),
        	'recursive' => -1
    	));
    	if (count($products)) {
    		if ($products['Product']['quantity'] > 0) {
	    		$data = array();
	    		$data['product_code'] = $code;
	    		$data['phone'] = $phone;
	    		$this->Order->create();
	    		if ($this->Order->save($data, array('validate' => 'first'))) {
	    			$message = 'Order placed! Our customer manager will communicate with you shortly.';
	    		} else {
	    			$message = 'Error Occurred! Please try again.';
	    		}
	    	} else {
	    		$message = 'Item not available! Please order something else.';
	    	}
    	} else {
    		$message = 'Invalid product code!';
    	}
        $this->set(array(
            'message' => $message,
            '_serialize' => array('message')
        ));
    }

}
