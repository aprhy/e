<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Setting extends CI_Controller {
  function __construct() {
    parent::__construct();
    error_reporting(0);
    $this->load->model("m_setting");
    $this->load->library('upload');
    if (!($this->session->userdata('user_id'))) {
      redirect('home');
    }
  }
  
  public function index() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['setting'] = $this->m_setting->fetch_setting();
    $this->load->view("attribute/header",$setting);
    $this->load->view("attribute/menus",$setting);
    $this->load->view("admin/master_data/setting", $data);
    $this->load->view("attribute/footer",$setting);
  }

  public function log() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['log'] = $this->m_setting->fetch_data_log();
    $this->load->view("attribute/header",$setting);
    $this->load->view("attribute/menus",$setting);
    $this->load->view("admin/master_data/log", $data);
    $this->load->view("attribute/footer",$setting);
  }


  public function update_setting() {
    if ($_FILES['userfile']['name'] == '') {
      #Get data...
      $data['setting_id']                    = $this->input->post('setting_id');
      $data['setting_appname']               = $this->input->post('setting_appname');
      $data['setting_short_appname']         = $this->input->post('setting_short_appname');
      $data['setting_origin_app']            = $this->input->post('setting_origin_app');
      $data['setting_owner_name']            = $this->input->post('setting_owner_name');
      $data['setting_phone']                 = $this->input->post('setting_phone');
      $data['setting_email']                 = $this->input->post('setting_email');
      $data['setting_address']               = $this->input->post('setting_address');


      $log['log_id']      = "";
      $log['log_time']    = date('Y-m-d H:i:s');
      $log['log_message'] = "Update Informasi Sistem";
      $log['user_id']     = $this->session->userdata('user_id');
      $this->m_setting->create_log($log);
      $this->m_setting->update_setting($data);
      $this->session->set_flashdata('update', 'Berhasil Update Data Informasi Sistem');

    }else{

      $filename                = $this->input->post('setting_id');
      $config['upload_path']   = './upload/setting/';
      $config['allowed_types'] = "gif|jpg|jpeg|png";
      $config['overwrite']     = "true";
      $config['max_size']      = "20000000";
      $config['max_width']     = "10000";
      $config['max_height']    = "10000";
      $config['file_name']     = 'settinglogo' . $filename;
      $this->upload->initialize($config);
      
      if (!$this->upload->do_upload()) {
        echo $this->upload->display_errors();
        
      } else {

        #Get data...

        unlink("./upload/user/".$this->input->post('user_picture'));
        $dat                                   = $this->upload->data();
        $data['setting_id']                    = $this->input->post('setting_id');
        $data['setting_appname']               = $this->input->post('setting_appname');
        $data['setting_short_appname']         = $this->input->post('setting_short_appname');
        $data['setting_origin_app']            = $this->input->post('setting_origin_app');
        $data['setting_owner_name']            = $this->input->post('setting_owner_name');
        $data['setting_phone']                 = $this->input->post('setting_phone');
        $data['setting_email']                 = $this->input->post('setting_email');
        $data['setting_address']               = $this->input->post('setting_address');
        $data['setting_logo']                  = $dat['file_name'];

        
        $log['log_id']      = "";
        $log['log_time']    = date('Y-m-d H:i:s');
        $log['log_message'] = "Update Informasi dan Logo";
        $log['user_id']     = $this->session->userdata('user_id');
        $this->m_setting->create_log($log);
        $this->m_setting->update_setting($data);
        $this->session->set_flashdata('update', 'Berhasil Update Data Informasi Sistem & Logo');
      }
    }
    #redirect to page
    redirect('setting');
  }

  
  
  
  
}
?>
