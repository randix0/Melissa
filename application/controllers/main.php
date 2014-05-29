<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

    public function __construct()
    {
        parent::__construct();
        /*
        $TEST_MODE = $this->config->item('TEST_MODE');

        if (!$TEST_MODE && (!$this->user->logged() || $this->user->access_level < 50 || $this->user->is_deleted)) {
            redirect('/pages/login/');
        }
        */
        $this->load->model('m_model');

    }

	public function index($order_by = '')
	{
        $instagram_url = 'https://api.instagram.com/v1/users/191389854/media/recent?access_token=487886679.5b9e1e6.7a9786b26b9042baa2f5b728981ab4aa';
        $instagram_json = @file_get_contents($instagram_url);
        $instagram = array();
        if ($instagram_json)
            $instagram = json_decode($instagram_json, true);

        $music = $this->m_model->getItems(array(),'music');
        $video = $this->m_model->getItems(array('order'=>array('id'=>'desc')),'video');
        $about = $this->m_model->getItem(array(
            'where' => array(
                'code' => 'about'
            ),
            'limit'=> 1
        ), 'pages');

        $ps = array(
            '__PAGE' => 'main',
            'about' => $about,
            'instagram' => $instagram,
            'music' => $music,
            'video' => $video
        );
        $this->mysmarty->view('global/main/index.tpl', $ps);
	}

    public function exchange()
    {
        if (!$this->settings->exchange) {
            $exchange_url = 'https://privat24.privatbank.ua/p24/accountorder?oper=prp&PUREXML&apicour&country=ua';
            $exchange_raw = @file_get_contents($exchange_url);
            if (!$exchange_raw) return die('file_get_contents error');
            if (!function_exists('simplexml_load_string')) return die('simplexml_load_string doesn`t exists');

            $exchange = simplexml_load_string($exchange_raw);

            $x = array();
            foreach($exchange as $e)
            {
                $ccy = (string)$e['ccy'];
                $buy = ((int)$e['buy']/(10000*(int)$e['unit']));
                $x[$ccy] = $buy; //($e['buy']/(10000*$e['unit']));
            }
            $this->settings->set(array('exchange'=>$x['USD']));
        }

        echo $this->settings->exchange;
    }
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */