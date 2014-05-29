<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

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

    function __construct()
    {
        parent::__construct();
        $this->load->library('json');
    }

    public function login()
    {
        $ps = array(
            'ADMIN' => true,
            '__PAGE' => 'main',
            'SUBPARTITION__PAGE' => '',
            //'stat' => $this->m_admin->getStatistic()
        );

        if ($this->user->logged()) {
            if ($this->user->access_level >= 50 && !$this->user->is_deleted)
                redirect('/');
            else
                $this->mysmarty->view('global/pages/accessDenied/index.tpl', $ps);
        }
        else
            $this->mysmarty->view('modals/login/index.tpl', $ps);
    }

}