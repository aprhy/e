<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {
  function __construct() {
    parent::__construct();
    error_reporting(0);
    $this->load->model("m_login");
    $this->load->model("m_widget");
    $this->load->model("m_setting");
  }

  public function index() {
    if (!($this->session->userdata('user_id'))) {
      $visit['visit_id']   = "";
      $visit['visit_date'] = date('Y-m-d H:i:s');
      $this->m_setting->create_visit($visit);
      $data['settings_app'] = $this->m_setting->fetch_setting();
      $this->load->view("admin/login/login_page", $data);
    } else {
      $setting['settings_app'] = $this->m_setting->fetch_setting();

      $data['group']                    = $this->m_widget->total_group();
      $data['user']                     = $this->m_widget->total_user();
      $data['asn']                      = $this->m_widget->total_asn($this->session->userdata('skpd_id'));
      $data['absen']                    = $this->m_widget->total_absen_today(date('Y-m-d'), $this->session->userdata('skpd_id'));
      $data['absen_lapangan']           = $this->m_widget->total_absen_lapangan_today(date('Y-m-d'), $this->session->userdata('skpd_id'));
      $data['violator']                 = $this->m_widget->total_pelanggar(date('Y-m-d'));
      $data['upacara']                 = $this->m_widget->total_upacara(date('Y-m-d'));
    //   $data['total_ketidakhadiranSKPD'] = $this->m_widget->total_ketidakhadiranSKPD(date('Y-m-'));
    //   $data['total_ketidakhadiran']     = $this->m_widget->total_ketidakhadiran(date('Y-m-'));
    //   $data['total_kehadiran']          = $this->m_widget->total_kehadiran(date('Y-m-'));
    //   $data['total_seluruhkehadiran']   = $this->m_widget->total_seluruhkehadiran(date('Y-m-'));

      //$data['proper']         = $this->m_widget->total_proper();
      //===================================================
    //   $data['log'] = $this->m_setting->get_log();
    //   $data['jam'] = array();
    //   for ($i = 0; $i <= 23; $i++) {
    //     if (strlen($i) == 1) {
    //       $date = date('Y-m-d') . " 0" . $i;
    //     } else {
    //       $date = date('Y-m-d') . " " . $i;
    //     }
    //     $x = $this->m_setting->visit_by_hour($date);
    //     array_push($data['jam'], $x);

    //   }

      $this->load->view("attribute/header", $setting);
      $this->load->view("attribute/menus", $setting);
      $this->load->view("attribute/content", $data);
      $this->load->view("attribute/footer", $setting);
    }
  }

  public function login() {
    if ($_POST) {
      $data['username'] = $this->input->post('username');
      $data['password'] = md5($this->input->post('password'));
      $result           = $this->m_login->login($data);
      if (!!($result)) {
        $data = array(
          'user_id'       => $result->user_id,
          'user_name'     => $result->user_name,
          'user_fullname' => $result->user_fullname,
          'user_photo'    => $result->user_photo,
          'user_address'  => $result->user_address,
          'group_id'      => $result->group_id,
          'skpd_id'       => 0,
          'sess_skpd'     => 1,
          'IsAuthorized'  => true,
        );
        $log['log_id']      = "";
        $log['log_time']    = date('Y-m-d H:i:s');
        $log['log_message'] = $data['user_fullname'] . " melakukan login ke sistem";
        $log['user_id']     = $data['user_id'];
        $this->m_setting->create_log($log);

        $this->session->set_userdata($data);
        redirect('home');
      } else {
        /* $this->session->set_flashdata('login', 'Username atau Kata Sandi salah!');
        redirect('home'); */

        $login_skpd           = $this->m_login->login_skpd($data);
        if (!!($login_skpd)) {
          $data = array(
            'user_id'       => $login_skpd[0]->id,
            'user_name'     => $login_skpd[0]->username,
            'user_fullname' => $login_skpd[0]->nama,
            'user_photo'    => $login_skpd[0]->picture,
            'user_address'  => $login_skpd[0]->alamat,
            'user_skpd'     => $login_skpd[0]->nama_skpd,
            'skpd_id'       => $login_skpd[0]->kd_skpd,
            'sess_skpd'     => $login_skpd[0]->kd_skpd,
            'group_id'      => 99,
            'IsAuthorized'  => true,
          );
          $log['log_id']      = "";
          $log['log_time']    = date('Y-m-d H:i:s');
          $log['log_message'] = $data['user_fullname'] . " melakukan login ke sistem";
          $log['user_id']     = $data['user_id'];
          $this->m_setting->create_log($log);

          $this->session->set_userdata($data);
          redirect('home');
        } else {
          $this->session->set_flashdata('login', 'Username atau Kata Sandi salah!');
          redirect('home');
        }
      }
    }
  }
  
  public function grafik() {
    if (!($this->session->userdata('user_id'))) {
      $this->session->set_flashdata('login', 'Maaf Anda Tidak Mempunyai Hak Akses Untuk Menu User!');
      redirect('home');
    } else {
      $setting['settings_app'] = $this->m_setting->fetch_setting();

      $data['total_ketidakhadiranSKPD'] = $this->m_widget->total_ketidakhadiranSKPD(date('Y-m-'));
      $data['total_ketidakhadiran']     = $this->m_widget->total_ketidakhadiran(date('Y-m-'));
      $data['total_kehadiran']          = $this->m_widget->total_kehadiran(date('Y-m-'));
      $data['total_seluruhkehadiran']   = $this->m_widget->total_seluruhkehadiran(date('Y-m-'));

      
      //===================================================
      $data['log'] = $this->m_setting->get_log();
      $data['jam'] = array();
      for ($i = 0; $i <= 23; $i++) {
        if (strlen($i) == 1) {
          $date = date('Y-m-d') . " 0" . $i;
        } else {
          $date = date('Y-m-d') . " " . $i;
        }
        $x = $this->m_setting->visit_by_hour($date);
        array_push($data['jam'], $x);

      }

      $this->load->view("attribute/header", $setting);
      $this->load->view("attribute/menus", $setting);
      $this->load->view("admin/master_data/grafik", $data);
      $this->load->view("attribute/footer", $setting);
    }
  }

  public function logout() {
    $this->session->sess_destroy();
    redirect('home');
  }

}
?>