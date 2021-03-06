<?php if (!defined('BASEPATH'))     exit('No direct script access allowed');

class Kia extends kiaController {

	private $home;
	
	public function __construct () {
		parent::__construct ();	
        $this->load->model ('mMRHistory');
	}
	
	public function index () {
	}
	
	public function patient () {
		$id_unit = $this->session->userdata['telah_masuk']['idunit'];
		$data['rrm'] = $this->mMRHistory->getHistoryRRM ($id_unit);
		$this->display('patient', $data);
	}
	
	// halaman antrian pasien
	public function queue () {
		$data['units'] = $this->mUnit->getUnitByHC ();
		$this->display('queue', $data);
	}	
}