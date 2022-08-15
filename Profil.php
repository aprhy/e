<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profil extends CI_Controller {
  function __construct() {
    parent::__construct();
    error_reporting(0);
    $this->load->model("m_pages");
    $this->load->model("m_struktur");
    $this->load->model("m_news");
  }

  public function sambutan() {
    $data['sambutan'] = $this->m_pages->tampil_data_pages('sambutan');
    $this->load->view("front_page/header");
    $this->load->view("front_page/module_profil/profil",$data);
    $this->load->view("front_page/footer");
  }

  public function sejarah() {
    $data['berita'] = $this->m_news->tampil_data_news();
    $data['sejarah'] = $this->m_pages->tampil_data_pages('sejarah');
    $data['pejabat'] = $this->m_pages->tampil_data_sejarah_pejabat('sejarah');
    $this->load->view("front_page/header");
    $this->load->view("front_page/module_profil/sejarah",$data);
    $this->load->view("front_page/footer");
  }

  public function visi_misi() {
    $data['visi'] = $this->m_pages->tampil_data_pages('visimisi');
    $this->load->view("front_page/header");
    $this->load->view("front_page/module_profil/visi",$data);
    $this->load->view("front_page/footer");
  }

  public function kebijakan() {
    $data['kebijakan'] = $this->m_pages->tampil_data_pages('kebijakan');
    $data['report'] = $this->m_pages->tampil_data_report('kebijakan');
    $this->load->view("front_page/header");
    $this->load->view("front_page/module_profil/kebijakan",$data);
    $this->load->view("front_page/footer");
  }

  public function struktur_organisasi() {
    $data['struktur'] = $this->m_struktur->tampil_data_struktur();
    $this->load->view("front_page/header");
    $this->load->view("front_page/module_profil/struktur_organisasi", $data);
    $this->load->view("front_page/footer");
  }

  public function fokus_pembangunan() {
    $data['fokus_pembangunan'] = $this->m_pages->tampil_data_pages('fokus_pembangunan');
    $this->load->view("front_page/header");
    $this->load->view("front_page/module_profil/fokus_pembangunan", $data);
    $this->load->view("front_page/footer");
  }
}
?>