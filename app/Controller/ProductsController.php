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
        $this->autoRender = false; // no view to render
        $this->response->type('json');
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
        $products = Set::extract('/Product/.', $products);
        $json = json_encode($products);
        $this->response->body($json);
    }

    public function order($code, $phone) {
        p(preg_match('/^(?:\+?88)?01[15-9]\d{8}$/', $phone));die;
        $this->autoRender = false; // no view to render
        $this->response->type('json');
        $products = $this->Product->find('first', array(
        	'conditions' => array('Product.code' => $code),
        	'recursive' => -1
    	));
    	if (count($products)) {
            $message = array();
    		if ($products['Product']['quantity'] > 0) {
	    		$data = array();
	    		$data['product_code'] = $code;
	    		$data['phone'] = $phone;
	    		$this->Order->create();
	    		if ($this->Order->save($data, array('validate' => 'first'))) {
                    $message['status'] = 'OK';
	    			$message['message'] = 'Order placed! Our customer manager will communicate with you shortly.';
	    		} else {
                    $message['status'] = 'FAILED';
	    			$message['message'] = 'Error Occurred! Please try again.';
	    		}
	    	} else {
                $message['status'] = 'FAILED';
	    		$message['message'] = 'Item not available! Please order something else.';
	    	}
    	} else {
            $message['status'] = 'FAILED';
    		$message['message'] = 'Invalid product code!';
    	}
        $json = json_encode($message);
        $this->response->body($json);
    }

}
