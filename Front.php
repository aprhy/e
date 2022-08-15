<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Front extends CI_Controller {
  function __construct() {
    parent::__construct();
    //error_reporting(0);
    $this->load->model("m_rules");
    $this->load->model("m_news");
    $this->load->model("m_rules_category");
    $this->load->model("m_rules");
    $this->load->model("m_widget");
    $this->load->model("m_proper");
    $this->load->model("m_spraying");
    $this->load->model("m_spraying_road");
    $this->load->model("m_inspection_vehicle");
    $this->load->model("m_inspection");
    $this->load->model("m_cases");
    $this->load->model("m_facilities");
    $this->load->model("m_user");
    $this->load->model("m_setting");
  }

  public function index() {
    $visit['visit_id']   = "";
    $visit['visit_date'] = date('Y-m-d H:i:s');
    $this->m_setting->create_visit($visit);
    $this->load->view("attribute/front/maps-page/index");
  }

  public function peta_penyemprotan_fasilitas() {
    $data['spraying']     = $this->m_spraying->data_spraying();
    $data['facilities']   = $this->m_facilities->fetch_data();
    $data['penyemprotan'] = $this->m_spraying->fetch_data();
    $this->load->view("attribute/front/maps-page/peta/penyemprotan_fasilitas_kota", $data);
  }

  public function peta_penyemprotan_titik_masuk() {
    $data['pemeriksaan'] = $this->m_inspection_vehicle->fetch_data();
    $this->load->view("attribute/front/maps-page/peta/penyemprotan_titik_masuk", $data);
  }

  public function peta_penyemprotan_jalan() {
    $data['pemeriksaan'] = $this->m_spraying_road->fetch_data();
    $this->load->view("attribute/front/maps-page/peta/penyemprotan_jalan", $data);
  }

  public function peta_penyemprotan_jalan_detail() {
    $data['pemeriksaan']        = $this->m_spraying_road->get($this->uri->segment(3));
    $data['pemeriksaan_detail'] = $this->m_spraying_road->fetch_data_detail($this->uri->segment(3));
    $this->load->view("attribute/front/maps-page/peta/penyemprotan_jalan_detail", $data);
  }

  public function peta_aktivitas_penanggulangan() {
    $data['pemeriksaan'] = $this->m_inspection->fetch_data();
    $this->load->view("attribute/front/maps-page/peta/aktivitas_penanggulangan", $data);
  }

  public function peta_kasus() {
    $data['kasus']  = $this->m_cases->fetch_data();
    $data['widget'] = $this->m_widget->total_cases();
    $this->load->view("attribute/front/maps-page/peta/kasus", $data);
  }

}
?>