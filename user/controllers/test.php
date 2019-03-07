<?php 
			
class Test extends Controller {
	public function index() {
		echo "Test controller'ının index() methodu.";
	}

	public function ornek() {
		$this->view('test');
	}
}