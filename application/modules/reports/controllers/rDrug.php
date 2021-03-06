<?php if (!defined('BASEPATH'))     exit('No direct script access allowed');

//dapat diganti extend dengan *contoh Admin_controller / Aplikan_controller di folder core. 
class Rdrug extends backendController {
    
    //1. seluruh fungsi yang tidak public, menggunakan awalan '_' , contoh: _getProperty()
    //2. di atas fungsi diberikan penjelasan proses apa yang dilakukan. dari mana ambil data, 
    //inputnya apa dan outputnya apa. contoh di fungsi index()
    //3. Penamaan fungsi harus PUNUK ONTA dengan awalan huruf kecil $ Menggunakan Bahasa Inggris
    //4. Penamaan nama fungsi maksimal 3 kata

    public function __construct() {
        parent::__construct();
        /*
        $this->theme_layout = 'template_login';
        $this->script_header = 'lay-scripts/header_login';
        $this->script_footer = 'lay-scripts/footer_login';
        $this->load->model('ppuskesmas');*/
		$this->load->model('ppuskesmas');
		$this->load->model('drugachieved_model');
		$this->load->model('drugused_model');
		$this->load->model('unit_model');
		$this->load->model('lplpo_model');
		$this->load->library('Pdf');
    }

    //penjelasan fungsi index, diletakkan disini... 
    public function index() {
    }
    
    function LPLPO()
    {
		$data['allUnit'] = $this->unit_model->getUnitByHC();
		if($this->session->userdata['telah_masuk']['idgedung'] == 19)
		$data['allHC'] = $this->ppuskesmas->getAllEntry();
		else
		$data['allHC'] = $this->ppuskesmas->selectById($this->session->userdata['telah_masuk']['idgedung']);
        $this->display('LPLPOview', $data);
    }
	
	function LPLPOpdf()
    {
		$form_data = $this->input->post(null, true);
		$idPuskesmas = $form_data['inputHC'];
		$idUnit = $form_data['inputUnit'];
		$from = $form_data['inputDari'];
		$till = $form_data['inputHingga'];
		if($idPuskesmas == '')
		{
			$idPuskesmas = $this->session->userdata['telah_masuk']['idgedung'];
			$data['idUnit'] = $this->session->userdata['telah_masuk']['idunit'];
			$data['namaUnit'] = $this->session->userdata['telah_masuk']['namaunit'];
		}
		else
		{
			echo $idPuskesmas;
			$unitDetail = $this->unit_model->getUnitById($idUnit);
			$data['idUnit'] = $idUnit;
			$data['namaUnit'] = $unitDetail[0]['NAMA_UNIT'];
		}
		$data['lplpo'] = $this->lplpo_model->getAllLplpo($idPuskesmas,$idUnit, $from, $till);
		$data['detailPuskesmas'] = $this->ppuskesmas->selectById($idPuskesmas);
		
		$this->load->view('LPLPO', $data);
    }
	
	function drugAchievedUnitdf()
    {
		$this->display('drugAchievedView');
    }
	
	function drugAchieved($idPuskesmas = '', $idUnit = '')
    {
        $form_data = $this->input->post(null, true);
		$from = $form_data['inputDari'];
		$till = $form_data['inputHingga'];
		if($idUnit == '')
		{
			$idPuskesmas = $this->session->userdata['telah_masuk']['idgedung'];
			$data['idUnit'] = $this->session->userdata['telah_masuk']['idunit'];
			$data['namaUnit'] = $this->session->userdata['telah_masuk']['namaunit'];
		}
		else
		{
			$unitDetail = $this->unit_model->getUnitById($idUnit);
			$data['idUnit'] = $idUnit;
			$data['namaUnit'] = $unitDetail[0]['NAMA_UNIT'];
		}
		
		$data['allDrugAchieved'] = $this->drugachieved_model->getAllAchievedDrugByDate($idPuskesmas,$data['idUnit'],$from, $till);
        $this->load->view('drugAchievedUnit', $data);
    }
	
	function drugUsedUnitdf()
	{
		$this->display('drugUsedView');
	}
	
	function drugUsed($idPuskesmas = '', $idUnit = '')
    {
        $form_data = $this->input->post(null, true);
		$from = $form_data['inputDari'];
		$till = $form_data['inputHingga'];
		if($idUnit == '')
		{
			$idPuskesmas = $this->session->userdata['telah_masuk']['idgedung'];
			$data['idUnit'] = $this->session->userdata['telah_masuk']['idunit'];
			$data['namaUnit'] = $this->session->userdata['telah_masuk']['namaunit'];
		}
		else
		{
			$unitDetail = $this->unit_model->getUnitById($idUnit);
			$data['idUnit'] = $idUnit;
			$data['namaUnit'] = $unitDetail[0]['NAMA_UNIT'];
		}
		
		$data['allDrugUsed'] = $this->drugused_model->getAllUsedDrugByDate($idPuskesmas,$data['idUnit'],$from, $till);
        $this->load->view('drugUsedUnit', $data);
    }
	
	function pRIPdf()
    {
        $this->load->view('pelayananRawatInap');
    }
	
	function pKesPdf()
    {
        $this->load->view('pelayananKesehatan');
    }
	
	function pPustuPdf()
    {
        $this->load->view('pelayananPustu');
    }
	
	function pPonkesdesPdf()
    {
        $this->load->view('pelayananPonkesdes');
    }
	
	function pPolindesPdf()
    {
        $this->load->view('pelayananPolindes');
    }
	
	function opnameStockPdf($idPuskesmas='', $idUnit = '')
    {
		if($idPuskemas == '')
		{
			$idPuskesmas = $this->session->userdata['telah_masuk']['idgedung'];
			$data['idUnit'] = $this->session->userdata['telah_masuk']['idunit'];
			$data['namaUnit'] = $this->session->userdata['telah_masuk']['namaunit'];
		}
		else
		{
			$unitDetail = $this->unit_model->getUnitById($idUnit);
			$data['idUnit'] = $idUnit;
			$data['namaUnit'] = $unitDetail[0]['NAMA_UNIT'];
		}
		
		$data['detailPuskesmas'] = $this->ppuskesmas->selectById($idPuskesmas);
        $this->load->view('opnameStock', $data);
    }
	
	function cardStockParentPdf()
    {
        $this->load->view('cardStockParent');
    }
	
	function cardStockHCarePdf()
    {
        $this->load->view('cardStockHCare');
    }
	
	function drugStockCardPdf()
    {
        $this->load->view('drugStockCard');
    }
	
	function drugAchievedPdf()
    {
        $this->load->view('drugAchieved');
    }
	
	function drugUsedPdf()
    {
        $this->load->view('drugUsed');
    }
	
}