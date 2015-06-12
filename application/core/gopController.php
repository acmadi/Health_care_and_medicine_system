<?php if (!defined('BASEPATH'))     exit('No direct script access allowed');

/* controller di setiap modul dapat extends pada controller admin. Berfungsi untuk membatasi hak akses*/
class gopController extends MY_Controller{
    function __construct() {
        
        parent::__construct();
        $this->top_navbar = 'lay-top-navbar/gopNavbar';
        $this->script_header = 'lay-scripts/header_samples';
        $this->script_footer = 'lay-scripts/footer_samples';
//        $this->script_header = 'lay-scripts/header_table';
//        $this->script_footer = 'lay-scripts/footer_table';
               
        if(!isset($this->session->userdata['telah_masuk'])&& !isset($this->session->userdata['telah_masuk']["idha"])){
            $this->session->set_userdata(array('last_url' => current_url()));
            redirect('index.php/login/notAuthorized');
        }
        if(2!=$this->session->userdata['telah_masuk']["idha"])
        {   
            $this->session->set_userdata(array('last_url' => current_url()));
            redirect('index.php/login/notAuthorized');
        }
    }   
}
?>