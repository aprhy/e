<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Skpd extends CI_Controller {
  function __construct() {
    parent::__construct();
    error_reporting(0);
    $this->load->model("m_skpd");
    $this->load->model("m_setting");
    if (!($this->session->userdata('user_id'))) {
      redirect('home');
    }
  }

  public function index() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['skpd']            = $this->m_skpd->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/skpd/skpd", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function input() {
    $data['kd_skpd'] = "";
    $data['nama']    = $this->input->post('nama');
    $this->session->set_flashdata('add', 'Berhasil Tambah SKPD ' . $data['nama']);

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menambah Data SKPD " . $this->input->post('nama');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);

    $this->m_skpd->input($data);
    redirect('skpd');
  }

  public function edit() {
    $data['kd_skpd']   = $this->input->post('kd_skpd');
    $data['nama'] = $this->input->post('nama');
    $this->session->set_flashdata('update', 'Berhasil Update SKPD ' . $data['nama']);

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Mengubah Data SKPD " . $this->input->post('nama');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);

    $this->m_skpd->edit($data);
    redirect('skpd');

  }

  public function delete() {
    $this->m_skpd->delete($this->input->post('kd_skpd'));
    $this->session->set_flashdata('delete', 'SKPD ' . $this->input->post('nama') . " telah dihapus !");

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menghapus Data SKPD " . $this->input->post('nama');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    redirect('skpd');
  }

}
?>
