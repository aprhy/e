<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rules extends CI_Controller {
  function __construct() {
    parent:: __construct();
    error_reporting(0);
    $this->load->model("m_rules");
    $this->load->model("m_setting");
    if (!($this->session->userdata('user_id'))) {
      redirect('home');
    }
  }

  public function index() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data   ['rules']        = $this->m_rules->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/rules/rules", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function input() {
    $data['rules_id']   = "";
    $data['rules_name'] = $this->input->post('rules_name');
    $this->session->set_flashdata('add', 'Berhasil Tambah Aturan Absen ' . $data['rules_name']);

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menambah Data Aturan Absen " . $this->input->post('rules_name');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);

    $this->m_rules->input($data);
    redirect('rules');
  }

  public function edit() {
    $data['rules_id']   = $this->input->post('rules_id');
    $data['rules_name'] = $this->input->post('rules_name');
    $this->session->set_flashdata('update', 'Berhasil Update Aturan Absen ' . $data['rules_name']);

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Mengubah Data Aturan Absen " . $this->input->post('rules_name');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);

    $this->m_rules->edit($data);
    redirect('rules');

  }

  public function delete() {
    $this->m_rules->delete($this->input->post('rules_id'));
    $this->session->set_flashdata('delete', 'Aturan Absen ' . $this->input->post('rules_name') . " telah dihapus !");

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menghapus Data Aturan Absen " . $this->input->post('rules_name');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    redirect('rules');
  }


  /* RULES */

  public function detail() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data    ['rules_name']  = $this->m_rules->get($this->uri->segment(3));
    $data    ['rules']       = $this->m_rules->fetch_data_detail($this->uri->segment(3));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/rules/rules_detail", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function input_detail() {
    $data['rules_detail_id']     = "";
    $data['rules_detail_start']  = $this->input->post('rules_detail_start');
    $data['rules_detail_end']    = $this->input->post('rules_detail_end');
    $data['rules_detail_status'] = $this->input->post('rules_detail_status');
    $data['rules_id']            = $this->input->post('rules_id');
    

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menambah Data Aturan Absen detail untuk waktu " . $this->input->post('rules_detail_status');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);

    $cek = $this->m_rules->exist_rules($data['rules_id'],$data['rules_detail_status']);

    if($cek){
      $this->session->set_flashdata('add', 'Rules sudah ada');
    }else{
      $this->m_rules->input_detail($data);
      $this->session->set_flashdata('add', 'Berhasil Tambah Aturan Absen untuk waktu ' . $data['rules_detail_status']);
    }
    
    redirect('rules/detail/'.$data['rules_id']);
  }

  public function edit_detail() {
    $data['rules_detail_id']     = $this->input->post('rules_detail_id');
    $data['rules_detail_start']  = $this->input->post('rules_detail_start');
    $data['rules_detail_end']    = $this->input->post('rules_detail_end');
    $data['rules_detail_status'] = $this->input->post('rules_detail_status');
    $data['rules_id']            = $this->input->post('rules_id');
    

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Mengubah Data Aturan Absen detail untuk waktu " . $this->input->post('rules_detail_status');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);

    // $cek = $this->m_rules->exist_rules($data['rules_id'],$data['rules_detail_status']);

    // if($cek){
    //   $this->session->set_flashdata('add', 'Rules Sudah Ada dengan status yang anda pilih');
    // }else{
    //   $this->m_rules->edit_detail($data);
    //   $this->session->set_flashdata('update', 'Berhasil Update Aturan Absen untuk waktu ' . $data['rules_detail_status']);
    // }

    $this->m_rules->edit_detail($data);
    $this->session->set_flashdata('update', 'Berhasil Update Aturan Absen untuk waktu ' . $data['rules_detail_status']);

    
    redirect('rules/detail/'.$data['rules_id']);

  }

  public function delete_detail() {
    $this->m_rules->delete_detail($this->input->post('rules_detail_id'));
    $this->session->set_flashdata('delete', 'Aturan Absen detail untuk waktu ' . $this->input->post('rules_detail_status') . " telah dihapus !");

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menghapus Data Aturan Absen detail untuk waktu" . $this->input->post('rules_detail_status');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    redirect('rules/detail/'.$this->input->post('rules_id'));
  }

}
?>
