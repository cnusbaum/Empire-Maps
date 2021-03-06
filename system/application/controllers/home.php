<?php

class Home extends Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{

		$data['meta']['pagetitle'] = "EmpireMaps - Historical maps of ancient empires";

		// set page template properties
		$data['page'] = array(
			'page_content' => 'pages/home',
		);

		// forward data to view
		$this->load->view('templates/global', $data);
	}
}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */