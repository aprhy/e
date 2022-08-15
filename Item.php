<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Item extends CI_Controller {
  function __construct() {
    parent::__construct();
    error_reporting(0);
    $this->load->model("m_item");
    $this->load->model("m_setting");
    if (!($this->session->userdata('user_id'))) {
      redirect('home');
    }
  }

  public function index() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['item']      = $this->m_item->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/item/item", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function input() {
    $data['item_id']   = "";
    $data['item_name'] = $this->input->post('item_name');
    $data['item_unit'] = $this->input->post('item_unit');
    $this->session->set_flashdata('add', 'Berhasil Tambah Barang ' . $data['item_name']);

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menambah Data Barang " . $this->input->post('item_name');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);

    $this->m_item->input($data);
    redirect('item');
  }

  public function edit() {
    $data['item_id']   = $this->input->post('item_id');
    $data['item_name'] = $this->input->post('item_name');
    $data['item_unit'] = $this->input->post('item_unit');
    $this->session->set_flashdata('update', 'Berhasil Update Barang ' . $data['item_name']);

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Mengubah Data Barang " . $this->input->post('item_name');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);

    $this->m_item->edit($data);
    redirect('item');

  }

  public function delete() {
    $this->m_item->delete($this->input->post('item_id'));
    $this->session->set_flashdata('delete', 'Barang ' . $this->input->post('item_name') . " telah dihapus !");

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menghapus Data Barang " . $this->input->post('item_name');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    redirect('item');
  }

}
?>
