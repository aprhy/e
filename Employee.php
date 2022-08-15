<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Employee extends CI_Controller {
  function __construct() {
    parent::__construct();
    error_reporting(0);
    $this->load->model("m_employee");
    $this->load->model("m_rules");
    $this->load->model("m_skpd");
    $this->load->model("m_setting");
    $this->load->library('ciqrcode');
    if (!($this->session->userdata('user_id'))) {
      redirect('home');
    }
  }


  public function reindex() {
    $id = $this->input->post('skpd');
    redirect('employee/index/'.$id);
  }

  public function index() {
    if($this->uri->segment(3) == ""){
      if($this->session->userdata('skpd_id')==0){
        $skpd = 1;
      }else{
        $skpd = $this->session->userdata('skpd_id');
      }
      $data['nama_skpd'] = $this->m_skpd->get($skpd);
    }else{
      if($this->session->userdata('skpd_id')==0){
        $skpd = $this->uri->segment(3);
      }else{
        $skpd = $this->session->userdata('skpd_id');
      }
      
      $data['nama_skpd'] = $this->m_skpd->get($skpd);
    }
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->fetch_data($skpd);
    $data['skpd']            = $this->m_skpd->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/employee", $data);
    $this->load->view("attribute/footer", $setting);
  }


  public function searchemployee() {
    
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->fetch_data_search($this->input->post('key'));
    $data['skpd']            = $this->m_skpd->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/employee", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function input() {

    $exist = $this->m_employee->check_exist_pegawai($this->input->post('nip'));
    if ($exist) {
      $this->session->set_flashdata('add', 'Oops.. NIP  ' . $this->input->post('nip') . " sudah terdaftar sebelumnya");
    } else {

      $data['id']             = "";
      $data['nip']            = $this->input->post('nip');
      $data['nama']           = $this->input->post('nama');
      $data['kelamin']        = $this->input->post('kelamin');
      $data['alamat']         = $this->input->post('alamat');
      $data['status_pegawai'] = 'Aktif';
      $data['gol_terakhir']   = $this->input->post('gol_terakhir');
      $data['kd_skpd']        = $this->input->post('kd_skpd');
      $data['rules_id']       = 1;
      $this->session->set_flashdata('add', 'Berhasil Tambah Pegawai ' . $data['username']);

      $log['log_id']      = "";
      $log['log_time']    = date('Y-m-d H:i:s');
      $log['log_message'] = "Menambah Data Pegawai " . $this->input->post('nip');
      $log['user_id']     = $this->session->userdata('user_id');
      $this->m_setting->create_log($log);

      $this->m_employee->input($data);
    }

    redirect('employee');
  }

  public function edit() {

    $data['id']             = $this->input->post('id');
    $data['nip']            = $this->input->post('nip');
    $data['nama']           = $this->input->post('nama');
    $data['kelamin']        = $this->input->post('kelamin');
    $data['alamat']         = $this->input->post('alamat');
    $data['gol_terakhir']   = $this->input->post('gol_terakhir');
    $data['kd_skpd']        = $this->input->post('kd_skpd');
    $this->session->set_flashdata('update', 'Berhasil Update Pegawai ' . $data['nip']);

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Mengubah Data Pegawai " . $this->input->post('nip');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);

    //Edit Account with NIP
    $getAccount = $this->m_employee->get_account($this->input->post('old_nip'));
    $datab['id']       = $getAccount[0]->id;
    $datab['nip']      = $data['nip'];
    
    $params['data']     = $data['nip'];
    $params['level']    = 'H';
    $params['size']     = 5;
    $params['savename'] = "./upload/pegawai/qrcode/" . $this->input->post('nip') . ".png";
    $this->ciqrcode->generate($params);

    $this->m_employee->edit_account($datab);

    //Edit Admin Account with NIP
    $getAdminAccount = $this->m_employee->check_exist_skpd($this->input->post('old_nip'));
    if($getAdminAccount){
      $datac['id']       = $getAdminAccount[0]->id;
      $datac['nip']      = $data['nip'];
      $this->m_employee->edit_admin_skpd($datac);
    }




    $this->m_employee->edit($data);
    redirect('employee');

  }

  public function delete() {
    if($this->session->userdata('group_id')==1){
      unlink("./upload/pegawai/qrcode/" . $this->input->post('nip') . ".png");
      $this->m_employee->delete($this->input->post('id'));
      $this->m_employee->delete_account_bynip($this->input->post('nip'), $this->input->post('kd_skpd'));

      $this->session->set_flashdata('delete', 'Akun Pegawai ' . $this->input->post('nip') . "-" . $this->input->post('nama') . " telah dihapus !");

      $log['log_id']      = "";
      $log['log_time']    = date('Y-m-d H:i:s');
      $log['log_message'] = "Menghapus Data Pegawai " . $this->input->post('nip');
      $log['user_id']     = $this->session->userdata('user_id');
      $this->m_setting->create_log($log);
    }else{
      $this->session->set_flashdata('delete', 'ANDA TIDAK PUNYA HAK MENGHAPUS DATA !');
    }
      
    redirect('employee/nonactive');
  }


  


  /* =============== ACCOUNT ================= */


  public function generateNewAccount() {
    $getData = $this->m_employee->generateNewAccount();
    $no=0;
    foreach($getData as $gD){
      $exist = $this->m_employee->check_exist($gD->nip);
      if ($exist) {

      }else{
        /* echo $no++."-".$gD->nip."-".$gD->kd_skpd."<br>"; */
        $data['id']       = "";
        $data['nip']      = $gD->nip;
        $data['kd_skpd']  = $gD->kd_skpd;
        $data['status']   = 1;
        $data['username'] = $gD->nip;
        $data['password'] = md5($gD->nip);
        $this->m_employee->input_account($data);

        
        $params['data']     = $gD->nip;
        $params['level']    = 'H';
        $params['size']     = 5;
        $params['savename'] = "./upload/pegawai/qrcode/" . $gD->nip . ".png";
        $this->ciqrcode->generate($params);

        $no++;
      }
      
      
    }

    echo $no." has been executed";
  }

  public function reindex_account() {
    $id = $this->input->post('skpd');
    redirect('employee/account/'.$id);
  }


  public function generateQrcodeAccount(){
    $cek = $this->m_employee->fetch_data_account($this->uri->segment(3));
    foreach($cek as $c){
      /* unlink($_SERVER['DOCUMENT_ROOT']."/asn.kendarikota.go.id/upload/pegawai/qrcode/" . $c->nip . ".png"); */
      /* Generate Qrcode */
      $params['data']     = $c->nip;
      $params['level']    = 'H';
      $params['size']     = 5;
      $params['savename'] = "./upload/pegawai/qrcode/" . $c->nip . ".png";
      $this->ciqrcode->generate($params);
    }
  
    redirect('employee/account/'.$this->uri->segment(3));
  }

  public function account() {

    if($this->uri->segment(3) == ""){
      if($this->session->userdata('skpd_id')==0){
        $skpd = 1;
      }else{
        $skpd = $this->session->userdata('skpd_id');
      }
      $data['nama_skpd'] = $this->m_skpd->get($skpd);
    }else{
      if($this->session->userdata('skpd_id')==0){
        $skpd = $this->uri->segment(3);
      }else{
        $skpd = $this->session->userdata('skpd_id');
      }
      $data['nama_skpd'] = $this->m_skpd->get($skpd);
    }

    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->fetch_data_account($skpd);
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['rules']           = $this->m_rules->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/account", $data);
    $this->load->view("attribute/footer", $setting);
  }


  public function searchaccount() {

    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->fetch_data_account_search($this->input->post('key'));
    $data['skpd']            = $this->m_skpd->fetch_data();
    $data['rules']           = $this->m_rules->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/account", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function input_account() {

    $exist = $this->m_employee->check_exist($this->input->post('nip'));
    if ($exist) {
      $this->session->set_flashdata('add', 'Oops.. NIP  ' . $this->input->post('nip') . " sudah terdaftar sebelumnya");
    } else {
      $cek_skpd = $this->m_employee->check_skpd($this->input->post('nip'));

      $data['id']       = "";
      $data['nip']      = $this->input->post('nip');
      $data['kd_skpd']  = $cek_skpd[0]->kd_skpd;
      $data['status']   = $this->input->post('status');
      $data['username'] = $this->input->post('username');
      $data['password'] = md5($this->input->post('password'));
      $this->session->set_flashdata('add', 'Berhasil Tambah Pegawai ' . $data['username']);

      $log['log_id']      = "";
      $log['log_time']    = date('Y-m-d H:i:s');
      $log['log_message'] = "Menambah Data Akun Pegawai " . $this->input->post('nip');
      $log['user_id']     = $this->session->userdata('user_id');
      $this->m_setting->create_log($log);

      /* Generate Qrcode */
      $params['data']     = $data['nip'];
      $params['level']    = 'H';
      $params['size']     = 5;
      $params['savename'] = "./upload/pegawai/qrcode/" . $this->input->post('nip') . ".png";
      $this->ciqrcode->generate($params);

      $this->m_employee->input_account($data);
    }

    redirect('employee/account/'.$data['kd_skpd']);
  }

  public function edit_account() {
    $cek_skpd = $this->m_employee->check_skpd($this->input->post('nip'));
    
    
    $data['id']         = $this->input->post('id');
    //$data['nip']      = $this->input->post('nip');
    $data['kd_skpd']    = $cek_skpd[0]->kd_skpd;
    $data['status']     = $this->input->post('status');
    $data['username']   = $this->input->post('username');
    $data['coordinate'] = '';


    if($this->input->post('rules_id')){
      $dataxx['id']        = $this->input->post('pegawai_id');
      $dataxx['rules_id']  = $this->input->post('rules_id');
      $this->m_employee->edit($dataxx);
    }
    
    if($data['username'] == $this->input->post('username_old')){

      if ($this->input->post('password') != "") {
        $data['password'] = md5($this->input->post('password'));
      }
  
      $this->session->set_flashdata('update', 'Berhasil Update Pegawai ' . $this->input->post('nip'));
  
      $log['log_id']      = "";
      $log['log_time']    = date('Y-m-d H:i:s');
      $log['log_message'] = "[".$this->session->userdata('user_fullname')."] - Mengubah Data Akun Pegawai " . $this->input->post('nip');
      $log['user_id']     = $this->session->userdata('user_id');
      $this->m_setting->create_log($log);
  
      $params['data']     = $this->input->post('nip');
      $params['level']    = 'H';
      $params['size']     = 5;
      $params['savename'] = "./upload/pegawai/qrcode/" . $this->input->post('nip') . ".png";
      $this->ciqrcode->generate($params);
  
      $this->m_employee->edit_account($data);

    }else{
      $check_username = $this->m_employee->exist_username_user($data['username']);
      if($check_username){
        //jika username sudah terpakai
        $this->session->set_flashdata('update_gagal', 'Username Sudah terpakai.. Silahkan gunakan username lain');
      }else{
        //jika username belum terpakai
        if ($this->input->post('password') != "") {
          $data['password'] = md5($this->input->post('password'));
        }
    
        $this->session->set_flashdata('update', 'Berhasil Update Pegawai ' . $this->input->post('nip'));
    
        $log['log_id']      = "";
        $log['log_time']    = date('Y-m-d H:i:s');
        $log['log_message'] = "[".$this->session->userdata('user_fullname')."] - Mengubah Data Akun Pegawai " . $this->input->post('nip');
        $log['user_id']     = $this->session->userdata('user_id');
        $this->m_setting->create_log($log);
    
        $params['data']     = $this->input->post('nip');
        $params['level']    = 'H';
        $params['size']     = 5;
        $params['savename'] = "./upload/pegawai/qrcode/" . $this->input->post('nip') . ".png";
        $this->ciqrcode->generate($params);
    
        $this->m_employee->edit_account($data);

      }
    }


    
    redirect('employee/account/'.$data['kd_skpd']);

  }

  public function delete_account() {
    if($this->session->userdata('group_id')==1){
      unlink("./upload/pegawai/qrcode/" . $this->input->post('nip') . ".png");
      $this->m_employee->delete_account($this->input->post('id'));
      $this->session->set_flashdata('delete', 'Akun Pegawai ' . $this->input->post('nip') . "-" . $this->input->post('nama') . " telah dihapus !");

      $log['log_id']      = "";
      $log['log_time']    = date('Y-m-d H:i:s');
      $log['log_message'] = "Menghapus Data Pegawai " . $this->input->post('nip');
      $log['user_id']     = $this->session->userdata('user_id');
      $this->m_setting->create_log($log);
    }else{
      $this->session->set_flashdata('delete', 'ANDA TIDAK PUNYA HAK MENGHAPUS DATA !');
    }
      
    redirect('employee/account/'.$this->input->post('kd_skpd'));
  }

  public function cetak_qrcode() {
    $data['a'] = $this->input->post('a');
    $this->load->view("admin/master_data/employee/savepdf", $data);

  }

  /* PELANGGAR */
  public function violator() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->fetch_data_violator(date('Y-m-d'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/violator/violator", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function allDataViolator() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->distinct_date_violator();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/violator/violator_tanggal", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function allDataViolatorDetail() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->fetch_data_violator($this->uri->segment(3));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/violator/violator", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function map_violator() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->get($this->uri->segment(3));
    $data['data_peta']       = $this->m_employee->get_violator($this->uri->segment(3), $this->uri->segment(4));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/violator/violator_maps", $data);
    $this->load->view("attribute/footer", $setting);
  }


  /* ========== Akun Admin =========== */
  public function admin_skpd() {

    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->fetch_data_admin_skpd();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/admin_skpd", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function input_admin_skpd() {

    $exist = $this->m_employee->check_exist_skpd($this->input->post('nip'));
    if ($exist) {
      $this->session->set_flashdata('add', 'Oops.. NIP  ' . $this->input->post('nip') . " sudah terdaftar sebelumnya");
    } else {
      $cek_skpd = $this->m_employee->check_skpd($this->input->post('nip'));

      $data['id']       = "";
      $data['nip']      = $this->input->post('nip');
      $data['kd_skpd']  = $cek_skpd[0]->kd_skpd;
      $data['username'] = $this->input->post('username');
      $data['password'] = md5($this->input->post('password'));
      $this->session->set_flashdata('add', 'Berhasil Tambah Admin SKPD ' . $data['username']);

      $log['log_id']      = "";
      $log['log_time']    = date('Y-m-d H:i:s');
      $log['log_message'] = "Menambah Data Akun Admin SKPD " . $this->input->post('nip');
      $log['user_id']     = $this->session->userdata('user_id');
      $this->m_setting->create_log($log);

      
      $this->m_employee->input_admin_skpd($data);
    }

    redirect('employee/admin_skpd');
  }

  public function edit_admin_skpd() {
    $cek_skpd = $this->m_employee->check_skpd($this->input->post('nip'));

    $data['id']       = $this->input->post('id');
    //$data['nip']      = $this->input->post('nip');
    $data['kd_skpd']  = $cek_skpd[0]->kd_skpd;
    $data['username'] = $this->input->post('username');
    if($data['username'] == $this->input->post('username_old')){
      if ($this->input->post('password') != "") {
        $data['password'] = md5($this->input->post('password'));
      }

      $this->session->set_flashdata('update', 'Berhasil Update Akun Admin ' . $data['nip']);

      $log['log_id']      = "";
      $log['log_time']    = date('Y-m-d H:i:s');
      $log['log_message'] = "Mengubah Data Akun Admin " . $this->input->post('nip');
      $log['user_id']     = $this->session->userdata('user_id');
      $this->m_setting->create_log($log);

      $this->m_employee->edit_admin_skpd($data);
    }else{
      $check_username = $this->m_employee->exist_username_officer($data['username']);
      if($check_username){
        //jika username sudah terpakai
        $this->session->set_flashdata('update_gagal', 'Username Sudah terpakai.. Silahkan gunakan username lain');
      }else{
        //jika username belum terpakai
        if ($this->input->post('password') != "") {
          $data['password'] = md5($this->input->post('password'));
        }
  
        $this->session->set_flashdata('update', 'Berhasil Update Akun Admin ' . $data['nip']);
  
        $log['log_id']      = "";
        $log['log_time']    = date('Y-m-d H:i:s');
        $log['log_message'] = "Mengubah Data Akun Admin " . $this->input->post('nip');
        $log['user_id']     = $this->session->userdata('user_id');
        $this->m_setting->create_log($log);
  
        $this->m_employee->edit_admin_skpd($data);
      }
    }
    redirect('employee/admin_skpd');

  }

  public function delete_admin_skpd() {
    $this->m_employee->delete_admin_skpd($this->input->post('id'));
    $this->session->set_flashdata('delete', 'Admin ' . $this->input->post('nip') . "-" . $this->input->post('nama') . " telah dihapus !");

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menghapus Data Pegawai " . $this->input->post('nip');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    redirect('employee/admin_skpd');
  }



  /* MUTASI */
  public function list_mutasi() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->fetch_data_mutasi();
    $data['skpd']            = $this->m_skpd->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/mutasi", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function mutasiFromSKPD() {

    $data['id']             = $this->input->post('id');
    $data['kd_skpd']        = 0;
    $this->session->set_flashdata('update', 'Berhasil Mutasi Pegawai ' . $this->input->post('nip'));

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Mutasi Data Pegawai " . $this->input->post('nip');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    $this->m_employee->edit($data);


    $datab['riwayat_mutasi_id']   = "";
    $datab['nip']                 = $this->input->post('nip');
    $datab['kd_skpd']             = $this->input->post('kd_skpd');
    $this->m_employee->input_mutasi($datab);

    redirect('employee/index/'.$datab['kd_skpd']);

  }


  public function mutasi() {

    $data['id']             = $this->input->post('id');
    $data['kd_skpd']        = $this->input->post('kd_skpd');
    $this->session->set_flashdata('update', 'Berhasil Mutasi Pegawai ' . $this->input->post('nip'));

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Mutasi Data Pegawai " . $this->input->post('nip');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);


    $this->m_employee->edit($data);
    redirect('employee/list_mutasi');

  }


  /* Search */
  function getEmployeebyKey() {
    $employee = $this->m_employee->searchEmployeeByKey($this->input->post('key'));
    foreach ($employee as $e) {
      $data .= '
        <b>'.$e->nip.'</b><br>'.$e->nama.'<br>'.$e->nama_skpd.'<hr>
      ';
      
    }
    echo $data;
  }


  // NonActive Employee
  public function nonactive() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']        = $this->m_employee->fetch_data_nonactive();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/employee/nonactive", $data);
    $this->load->view("attribute/footer", $setting);
  }


  public function doNonActive() {
    
    $data['id']             = $this->input->post('id');
    $data['status_pegawai'] = 'NonAktif';
    $data['keterangan']     = $this->input->post('keterangan');

    $this->session->set_flashdata('update', 'Berhasil NonAktifkan Pegawai ' . $this->input->post('nip'));
    
    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "[".$this->session->userdata('user_fullname')."] - MeNonAktifkan Data Akun Pegawai " . $this->input->post('nip');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);

    $this->m_employee->edit($data);

    redirect('employee');

  }

}
?>
