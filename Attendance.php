<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Attendance extends CI_Controller {
  function __construct() {
    parent::__construct();
    error_reporting(0);
    $this->load->model("m_attendance");
    $this->load->model("m_employee");
    $this->load->model("m_skpd");
    $this->load->model("m_setting");
    $this->load->model("m_api");
    if (!($this->session->userdata('user_id'))) {
      redirect('home');
    }
  }

  public function reindex() {
    $id        = $this->input->post('skpd');
    $url_seg2  = $this->input->post('url_seg2');
    $url_seg3  = $this->input->post('url_seg3');
    
    $this->session->set_userdata('sess_skpd', $id);
    if($url_seg2 == ""){
      redirect('attendance');
    }else{
      redirect('attendance/'.$url_seg2.'/'.$url_seg3);
    }
  }

  /* Absen Pagi */
  public function index() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->fetch_data_today(date('Y-m-d'),$this->session->userdata('sess_skpd'));
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function allData() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->distinct_date_pagi();
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance_tanggal", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function allDataDetail() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->fetch_data_today($this->uri->segment(3),$this->session->userdata('sess_skpd'));
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance", $data);
    $this->load->view("attribute/footer", $setting);
  }


  /* Absen Pagi Lapangan */
  public function pagi_lapangan() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->fetch_data_today_lapangan(date('Y-m-d'),$this->session->userdata('sess_skpd'));
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance_lapangan", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function allDataLapangan() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->distinct_date_pagi_lapangan();
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance_tanggal", $data);
    $this->load->view("attribute/footer", $setting);
  }


  public function allDataLapanganDetail() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->fetch_data_today_lapangan($this->uri->segment(3),$this->session->userdata('sess_skpd'));
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance_lapangan", $data);
    $this->load->view("attribute/footer", $setting);
  }


  /* Absen Sore */
  public function sore() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->fetch_data_today_sore(date('Y-m-d'),$this->session->userdata('sess_skpd'));
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function allDataSore() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->distinct_date_sore();
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance_tanggal", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function allDataSoreDetail() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->fetch_data_today_sore($this->uri->segment(3),$this->session->userdata('sess_skpd'));
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance", $data);
    $this->load->view("attribute/footer", $setting);
  }


  /* Absen Sore Lapangan */
  public function sore_lapangan() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->fetch_data_today_sore_lapangan(date('Y-m-d'),$this->session->userdata('sess_skpd'));
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance_lapangan", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function allDataLapanganSore() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->distinct_date_sore_lapangan();
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance_tanggal", $data);
    $this->load->view("attribute/footer", $setting);
  }


  public function allDataLapanganDetailSore() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance']      = $this->m_attendance->fetch_data_today_sore_lapangan($this->uri->segment(3),$this->session->userdata('sess_skpd'));
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['nama_skpd']       = $this->m_skpd->get($this->session->userdata('sess_skpd'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/attendance_lapangan", $data);
    $this->load->view("attribute/footer", $setting);
  }



  /* Daftar Belum Absen */
  public function notAttendance() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['attendance'] = $this->m_attendance->getNotAttendance($this->session->userdata('skpd_id'), date('Y-m-d'));
    $this->load->view("attribute/header",$setting);
    $this->load->view("attribute/menus",$setting);
    $this->load->view("admin/master_data/attendance/belum_absen", $data);
    $this->load->view("attribute/footer",$setting);
  }




  /**
   * SECTION FORM IZIN
   * 
   * 1 = CUTI SAKIT
   * 2 = CUTI BESAR/ BERSALIN / KARNA HAL PENTING
   * 3 = TANPA KETERANGAN (ALPA)
   * 4 = SAKIT / IZIN DENGAN KETERANGAN
   * 5 = LIBUR
   */

  public function formizin() {

    if($this->input->post('tanggal')){
      $tanggal = $this->input->post('tanggal');
    }else{
      $tanggal = date('Y-m-d');
    }

    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data   ['employee']     = $this->m_employee->fetch_data($this->session->userdata('skpd_id'));
    $data   ['formizin']     = $this->m_attendance->getFormIzin($this->session->userdata('skpd_id'), $tanggal);
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/form_izin", $data);
    $this->load->view("attribute/footer", $setting);
  }


  public function formizin_backup() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data   ['employee']     = $this->m_employee->fetch_data_backup($this->session->userdata('skpd_id'));
    $data   ['formizin']     = $this->m_attendance->getFormIzin($this->session->userdata('skpd_id'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/attendance/form_izin", $data);
    $this->load->view("attribute/footer", $setting);

    //var_dump($setting['settings_app']);

  }

  public function input_izin() {

    // $pegawai = explode("-",$this->input->post('pegawai'));
    // $cekNIP  = $this->m_attendance->cekDouble($pegawai[0], $this->input->post('tanggal'));

    // if($cekNIP){
    //   // UPDATE 
    //   $dataxx['nip']              = $pegawai[0];
    //   $dataxx['absensi_tanggal']  = $this->input->post('tanggal');
    //   $dataxx['status_kehadiran'] = $this->input->post('izin_status');

    //   $this->m_attendance->edit_absen($dataxx);

    // }else{
    //   // INPUT
    //   $hari    = $this->getDay($this->input->post('tanggal'));

    //   if($hari == 'Sabtu' OR $hari == 'Minggu'){
    //     $status_hari  = 'Extra Day';
    //   }else{
    //     $status_hari  = 'Work Day';
    //   }


    //   $dataxx['absensi_id']       = "";
    //   $dataxx['absensi_tanggal']  = $this->input->post('tanggal');
    //   $dataxx['nip']              = $pegawai[0];
    //   $dataxx['kd_skpd']          = $pegawai[1];
    //   $dataxx['status_berkantor'] = 1;
    //   $dataxx['status_kehadiran'] = $this->input->post('izin_status');
    //   $dataxx['hari']             = $hari;
    //   $dataxx['status_hari']      = $status_hari;

    //   $this->m_attendance->input_absen($dataxx);
    // }

    // $data['izin_id']     = "";
    // $data['izin_status'] = $this->input->post('izin_status');
    // $data['izin_date']   = $this->input->post('tanggal');
    // $data['nip']         = $pegawai[0];
    // $data['kd_skpd']     = $pegawai[1];
    // $data['keterangan']  = $this->input->post('keterangan');
    // $this->session->set_flashdata('add', 'Berhasil Tambah Izin ' . $data['nama']);

    // $log['log_id']      = "";
    // $log['log_time']    = date('Y-m-d H:i:s');
    // $log['log_message'] = "Menambah Data Izin Pegawai " . $this->input->post('nip');
    // $log['user_id']     = $this->session->userdata('user_id');
    // $this->m_setting->create_log($log);


    // $cekIzin = $this->m_attendance->cekDoubleIzin($pegawai[0], $this->input->post('tanggal'));
    // if($cekIzin){
    //   //doNothing
    // }else{
    //   $this->m_attendance->input_izin($data);
    // }
    
    // redirect('attendance/formizin');



    $begin = new DateTime( $this->input->post('tanggal'));
		$end   = new DateTime( $this->input->post('tanggal_akhir') );
		$end   = $end->modify( '+1 day' );

    $interval  = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);
    
    $pegawai = explode("-",$this->input->post('pegawai'));

    if($end < $begin ){
      $this->session->set_flashdata('add', 'Gagal Tambah Data Karena Tanggal Akhir Lebih Kecil dari Tangga Mulai ');
    }else{
      foreach($daterange as $date){

        
        $cekNIP  = $this->m_attendance->cekDouble($pegawai[0], $date->format("Y-m-d"));
  
        if($cekNIP){
          // UPDATE 
          $dataxx['nip']              = $pegawai[0];
          $dataxx['absensi_tanggal']  = $date->format("Y-m-d");
          $dataxx['status_kehadiran'] = $this->input->post('izin_status');
  
          $this->m_attendance->edit_absen($dataxx);
  
        }else{
          // INPUT
          $hari    = $this->getDay($date->format("Y-m-d"));
  
          if($hari == 'Sabtu' OR $hari == 'Minggu'){
            $status_hari  = 'Extra Day';
          }else{
            $status_hari  = 'Work Day';
          }
  
  
          $dataxx['absensi_id']       = "";
          $dataxx['absensi_tanggal']  = $date->format("Y-m-d");
          $dataxx['nip']              = $pegawai[0];
          $dataxx['kd_skpd']          = $pegawai[1];
          $dataxx['status_berkantor'] = 1;
          $dataxx['status_kehadiran'] = $this->input->post('izin_status');
          $dataxx['hari']             = $hari;
          $dataxx['status_hari']      = $status_hari;
  
          $this->m_attendance->input_absen($dataxx);
        }
  
        $data['izin_id']     = "";
        $data['izin_status'] = $this->input->post('izin_status');
        $data['izin_date']   = $date->format("Y-m-d");
        $data['nip']         = $pegawai[0];
        $data['kd_skpd']     = $pegawai[1];
        $data['keterangan']  = $this->input->post('keterangan');
        $this->session->set_flashdata('add', 'Berhasil Tambah Izin ' . $data['nama']);
  
        $log['log_id']      = "";
        $log['log_time']    = date('Y-m-d H:i:s');
        $log['log_message'] = "Menambah Data Izin Pegawai " . $this->input->post('nip');
        $log['user_id']     = $this->session->userdata('user_id');
        $this->m_setting->create_log($log);
  
  
        $cekIzin = $this->m_attendance->cekDoubleIzin($pegawai[0], $date->format("Y-m-d"));
        if($cekIzin){
          //doNothing
        }else{
          $this->m_attendance->input_izin($data);
        }
      
      }
    }

		
    
    redirect('attendance/formizin');
  }



  public function edit_izin() {

    $pegawai = explode("-",$this->input->post('pegawai'));

    $data['izin_id']     = $this->input->post('izin_id');
    $data['izin_status'] = $this->input->post('izin_status');
    $data['izin_date']   = $this->input->post('tanggal');
    $data['nip']         = $pegawai[0];
    $data['kd_skpd']     = $pegawai[1];
    $data['keterangan']  = $this->input->post('keterangan');
    $this->session->set_flashdata('update', 'Berhasil Update Izin ' . $data['nip']);

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Mengubah Data Izin " . $this->input->post('nip');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);



    // UPDATE ABSENSI
    $dataxx['nip']              = $pegawai[0];
    $dataxx['absensi_tanggal']  = $this->input->post('tanggal');
    $dataxx['status_kehadiran'] = $this->input->post('izin_status');

    $this->m_attendance->edit_absen($dataxx);

    $this->m_attendance->edit_izin($data);
    redirect('attendance/formizin');

  }

  public function delete_izin() {
    $this->m_attendance->delete_izin($this->input->post('izin_id'));
    $this->session->set_flashdata('delete', 'Pegawai ' . $this->input->post('nip') . " telah dihapus !");

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menghapus Data SKPD " . $this->input->post('nama');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    redirect('attendance/formizin');
  }


  // FUNCTION DAY
  public function getDay($date){
    $daftar_hari = array(
      'Sunday'    => 'Minggu',
      'Monday'    => 'Senin',
      'Tuesday'   => 'Selasa',
      'Wednesday' => 'Rabu',
      'Thursday'  => 'Kamis',
      'Friday'    => 'Jumat',
      'Saturday'  => 'Sabtu'
    );
    
    $namahari = date('l', strtotime($date));
    
    return $daftar_hari[$namahari];
  }


  public function libur_tanggal_merah() {
    $tanggal       = $this->input->post('tanggal');
    $getTidakHadir = $this->m_api->getNotAttendanceNormal($tanggal);

    $hari    = $this->getDay($tanggal);

    if($hari == 'Sabtu' OR $hari == 'Minggu'){
      $status_hari      = 'Extra Day';
      $status_kehadiran = 5;
    }else{
      $status_hari      = 'Work Day';
      $status_kehadiran = 5;
    }


    foreach ($getTidakHadir as $g){

      $x = $this->m_api->cekDouble($g->nip , $tanggal);
      if($x){
        // do Nothing
      }else{

        // Absensi
        $explodeDate  = explode(' ',$g->checkin_time);

        $data['absensi_id']       = "";
        $data['absensi_tanggal']  = $tanggal;
        $data['nip']              = $g->nip;
        $data['kd_skpd']          = $g->kd_skpd;
        $data['status_berkantor'] = 1;
        $data['status_kehadiran'] = $status_kehadiran;
        $data['hari']             = $hari;
        $data['status_hari']      = $status_hari;

        $this->m_api->input($data);


        // Izin
        $datax['izin_id']     = "";
        $datax['izin_status'] = $status_kehadiran;
        $datax['izin_date']   = $tanggal;
        $datax['nip']         = $g->nip;
        $datax['kd_skpd']     = $g->kd_skpd;
        $datax['keterangan']  = 'Libur Tanggal Merah';

        $cekIzin = $this->m_attendance->cekDoubleIzin($g->nip, $tanggal);
        if($cekIzin){
          //doNothing
        }else{
          $this->m_attendance->input_izin($datax);
        }

      }
      
    }
  
    $this->session->set_flashdata('absensi', 'Berhasil Menambahkan Izin Libur Tanggal Merah pada tanggal '.$tanggal.' untuk semua pegawai dengan kategori absensi normal');
    redirect('home');
  }
  

}
?>
