<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('m_user');
    $this->load->model('m_group');
    $this->load->model("m_setting");
    $this->load->library('upload');
    #Check user login or not
    if (!($this->session->userdata('user_id'))) {
      $this->session->set_flashdata('login', 'Maaf Anda Tidak Mempunyai Hak Akses Untuk Menu User!');
      redirect('home');
    }
  }

  public function index() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data["user"]            = $this->m_user->fetch_data();
    $data["group"]           = $this->m_group->fetch_data();
    #Generate Template...
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/user", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function input() {
    $data['user_id']         = "US" . date('YmdHis');
    $data['user_name']       = $this->input->post('user_name');
    $data['user_fullname']   = $this->input->post('user_fullname');
    $data['user_phone']      = $this->input->post('user_phone');
    $data['user_password']   = md5($this->input->post('user_password'));
    $data['group_id']        = $this->input->post('group_id');

    $this->session->set_flashdata('add', 'Berhasil Tambah User <b>' . $data['user_name'] . '</b>');
    $this->m_user->create_user($data);
    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menambah Data User " . $data['user_name'];
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    redirect('user');
  }

  public function edit() {
    if ($this->input->post('user_password') != "") {
      $data['user_id']         = $this->input->post('user_id');
      $data['user_name']       = $this->input->post('user_name');
      $data['user_fullname']   = $this->input->post('user_fullname');
      $data['user_phone']      = $this->input->post('user_phone');
      $data['user_password']   = md5($this->input->post('user_password'));
      $data['group_id']        = $this->input->post('group_id');
    } else {
      $data['user_id']         = $this->input->post('user_id');
      $data['user_name']       = $this->input->post('user_name');
      $data['user_fullname']   = $this->input->post('user_fullname');
      $data['user_phone']      = $this->input->post('user_phone');
      $data['group_id']        = $this->input->post('group_id');
    }
    $this->session->set_flashdata('update', 'Berhasil Update User <b>' . $data['user_name'] . '</b>');
    $this->m_user->update_user($data);
    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Mengedit Data User " . $data['user_name'];
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    redirect('user');
  }

  public function delete() {
    $this->session->set_flashdata('delete', 'User <b>' . $this->input->post('user_name') . '</b> telah dihapus !');
    $this->m_user->delete_user($this->input->post('user_id'));
    $log['log_id']      = "";
    $log['log_time']    = date('Y-m-d H:i:s');
    $log['log_message'] = "Menghapus Data User " . $this->input->post('user_name');
    $log['user_id']     = $this->session->userdata('user_id');
    $this->m_setting->create_log($log);
    redirect('user');
  }
  /*Profile*/
  public function profile() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['profile']         = $this->m_user->profile($this->session->userdata('user_id'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/profile", $data);
    $this->load->view("attribute/footer", $setting);
  }
  #Update user profile...
  public function update_profile() {
    if ($_FILES['userfile']['name'] == '') {
      #Get data...
      $data['user_id']       = $this->input->post('user_id');
      $data['user_name']     = $this->input->post('user_name');
      $data['user_fullname'] = $this->input->post('user_fullname');

      #set up password field
      $oldpassword      = $this->input->post('oldpassword');
      $checkoldpassword = md5($this->input->post('checkoldpassword'));
      $password         = md5($this->input->post('password'));
      $matchpassword    = md5($this->input->post('matchpassword'));

      if ($this->input->post('checkoldpassword') == "") {
        $this->session->set_flashdata('update', 'Berhasil Update User <b>' . $data['user_name'] . '</b>');
        #call function
        $this->m_user->update_user($data);
        #Set Session
        $sesi = array(
          'user_name'     => $data['user_name'],
          'user_fullname' => $data['user_fullname'],
        );
        $this->session->set_userdata($sesi);

      } else {
        #validasi password
        if ($oldpassword != $checkoldpassword) {
          $this->session->set_flashdata('gagal', 'Password Lama Anda Salah');
        } else {
          if ($password != $matchpassword) {
            $this->session->set_flashdata('gagal', 'Validasi Password Tidak Sama');
          } else {
            $this->session->set_flashdata('update', 'Berhasil Mengganti Password');
            $data['user_password'] = $password;
            #call function
            $this->m_user->update_user($data);
            $sesi = array(
              'user_name'     => $data['user_name'],
              'user_fullname' => $data['user_fullname'],
            );
            $this->session->set_userdata($sesi);
          }
        }
      }
      $log['log_id']      = "";
      $log['log_time']    = date('Y-m-d H:i:s');
      $log['log_message'] = $data['user_fullname'] . " Update Profile";
      $log['user_id']     = $this->session->userdata('user_id');
      $this->m_setting->create_log($log);
    } else {
      $filename                = $this->input->post('user_id');
      $config['upload_path']   = './upload/user/';
      $config['allowed_types'] = "gif|jpg|jpeg|png";
      $config['overwrite']     = "true";
      $config['max_size']      = "20000000";
      $config['max_width']     = "10000";
      $config['max_height']    = "10000";
      $config['file_name']     = '' . $filename;
      $this->upload->initialize($config);

      if (!$this->upload->do_upload()) {
        echo $this->upload->display_errors();

      } else {
        #Get data...
        unlink("./upload/user/" . $this->input->post('user_picture'));
        $dat                   = $this->upload->data();
        $data['user_id']       = $this->input->post('user_id');
        $data['user_name']     = $this->input->post('user_name');
        $data['user_fullname'] = $this->input->post('user_fullname');
        $data['user_photo']    = $dat['file_name'];

        #set up password field
        $oldpassword      = $this->input->post('oldpassword');
        $checkoldpassword = md5($this->input->post('checkoldpassword'));
        $password         = md5($this->input->post('password'));
        $matchpassword    = md5($this->input->post('matchpassword'));

        if ($this->input->post('checkoldpassword') == "") {
          $this->session->set_flashdata('update', 'Berhasil Update User <b>' . $data['user_name'] . '</b>');
          #call function
          $this->m_user->update_user($data);
          $sesi = array(
            'user_name'     => $data['user_name'],
            'user_fullname' => $data['user_fullname'],
            'user_photo'    => $data['user_photo'],
          );
          $this->session->set_userdata($sesi);
        } else {
          #validasi password
          if ($oldpassword != $checkoldpassword) {
            $this->session->set_flashdata('gagal', 'Password Lama Anda Salah');
          } else {
            if ($password != $matchpassword) {
              $this->session->set_flashdata('gagal', 'Validasi Password Tidak Sama');
            } else {
              $this->session->set_flashdata('update', 'Berhasil Mengganti Password');
              $data['user_password'] = $password;
              #call function
              $this->m_user->update_user($data);
              $sesi = array(
                'user_name'     => $data['user_name'],
                'user_fullname' => $data['user_fullname'],
                'user_photo'    => $data['user_photo'],
              );
              $this->session->set_userdata($sesi);
            }
          }
        }
        $log['log_id']      = "";
        $log['log_time']    = date('Y-m-d H:i:s');
        $log['log_message'] = $data['user_fullname'] . " Update Profile & Foto";
        $log['user_id']     = $this->session->userdata('user_id');
        $this->m_setting->create_log($log);
      }
    }
    #redirect to page
    redirect('user/profile');

  }
}
?>