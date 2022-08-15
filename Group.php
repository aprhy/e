<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Group extends CI_Controller {
  function __construct() {
    parent::__construct();
    error_reporting(0);
    $this->load->model("m_group");
    $this->load->model("m_setting");
    if (!($this->session->userdata('user_id'))) {
      redirect('home');
    }
  }
  
  public function index() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['group'] = $this->m_group->fetch_data();
    $this->load->view("attribute/header",$setting);
    $this->load->view("attribute/menus",$setting);
    $this->load->view("admin/master_data/group", $data);
    $this->load->view("attribute/footer",$setting);
  }

  
  
  public function input() {
    $data['group_id']    = "";
    $data['group_name']  = $this->input->post('group_name');
    $this->session->set_flashdata('add', 'Berhasil Tambah Group ' . $data['group_name']);

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menambah Data Group ".$this->input->post('group_name');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    
    $this->m_group->input($data);
    redirect('group');
  }
  
  public function edit() {
    $data['group_id']    = $this->input->post('group_id');
    $data['group_name']  = $this->input->post('group_name');
    $this->session->set_flashdata('update', 'Berhasil Update Group ' . $data['group_name']);

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Mengubah Data Group ".$this->input->post('group_name');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    
    $this->m_group->edit($data);
    redirect('group');
      
  }
  
  public function delete() {
    $this->m_group->delete($this->input->post('group_id'));
    $this->session->set_flashdata('delete', 'Group ' . $this->input->post('group_name')." telah dihapus !");

    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menghapus Data Group ".$this->input->post('group_name');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    redirect('group');
  }
  
}
?>
