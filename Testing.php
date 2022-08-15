<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Testing extends CI_Controller {
  function __construct() {
    parent::__construct();
    error_reporting(0);
    $this->load->model("m_testing");


    //STATUS
    // 0 = HADIR
    // 1 = CUTI SAKIT
    // 2 = CUTI BESAR/ BERSALIN / KARNA HAL PENTING
    // 3 = TANPA KETERANGAN (ALPA)
    // 4 = SAKIT / IZIN DENGAN KETERANGAN
    // 5 = LIBUR
  }

  public function index() {
    echo "OK";
  }

  public function upload_pagi() {
    //echo "OK";
    $tanggal = '2020-08-17';
    $getPagi = $this->m_testing->getPagi($tanggal);

    $hari    = $this->getDay($tanggal);

    if($hari == 'Sabtu' OR $hari == 'Minggu'){
      $status_hari  = 'Extra Day';
    }else{
      $status_hari  = 'Work Day';
    }

    foreach ($getPagi as $g){

      $x = $this->m_testing->cekDouble($g->nip , $tanggal);
      if($x){
        echo $g->nip." - Sudah Ada<br>";
      }else{
        
        $explodeDate  = explode(' ',$g->checkin_time);

        $data['absensi_id']       = "";
        $data['absensi_tanggal']  = $g->today;
        $data['nip']              = $g->nip;
        $data['kd_skpd']          = $g->kd_skpd;
        $data['jam_masuk']        = $explodeDate[1];
        $data['status_berkantor'] = $g->status;
        $data['status_kehadiran'] = 0;
        $data['hari']             = $hari;
        $data['status_hari']      = $status_hari;

        $this->m_testing->input($data);

      }
      
    }
    
    //print("<pre>".print_r($getPagi,true)."</pre>");
    
  }

  public function upload_pagilapangan() {
    //echo "OK";
    $tanggal = '2020-08-17';
    $getPagi = $this->m_testing->getPagiLapangan($tanggal);

    $hari    = $this->getDay($tanggal);

    if($hari == 'Sabtu' OR $hari == 'Minggu'){
      $status_hari  = 'Extra Day';
    }else{
      $status_hari  = 'Work Day';
    }

    foreach ($getPagi as $g){

      $x = $this->m_testing->cekDouble($g->nip , $tanggal);
      if($x){
        echo $g->nip." - Sudah Ada<br>";
      }else{
        //echo $g->nip."<br>";
        $explodeDate  = explode(' ',$g->checkin_time);

        $data['absensi_id']       = "";
        $data['absensi_tanggal']  = $g->today;
        $data['nip']              = $g->nip;
        $data['kd_skpd']          = $g->kd_skpd;
        $data['jam_masuk']        = $explodeDate[1];
        $data['status_berkantor'] = $g->status;
        $data['status_kehadiran'] = 0;
        $data['hari']             = $hari;
        $data['status_hari']      = $status_hari;

        $this->m_testing->input($data);

      }
      
    }
    
    //print("<pre>".print_r($getPagi,true)."</pre>");
        
  }


  public function update_sore() {
    $tanggal = '2020-08-17';
    $getSore = $this->m_testing->getSore($tanggal);

    foreach ($getSore as $g){
      //$explodeDate  = explode(' ',$g->checkin_time);

      $data['absensi_tanggal']  = $g->today;
      $data['nip']              = $g->nip;
      $data['jam_pulang']       = '16:48:00';

      $this->m_testing->edit($data);
    
    }

    //print("<pre>".print_r($getSore,true)."</pre>");

  }

  public function update_sorelapangan() {
    $tanggal = '2020-08-17';
    $getSore = $this->m_testing->getSoreLapangan($tanggal);

    foreach ($getSore as $g){
      //$explodeDate  = explode(' ',$g->checkin_time);

      $data['absensi_tanggal']  = $g->today;
      $data['nip']              = $g->nip;
      $data['jam_pulang']       = '16:48:00';

      $this->m_testing->edit($data);
    
    }

    //print("<pre>".print_r($getSore,true)."</pre>");
  }

  public function push_tidakhadir() {
    $tanggal       = '2020-08-21';
    $getTidakHadir = $this->m_testing->getNotAttendance($tanggal);

    $hari    = $this->getDay($tanggal);

    if($hari == 'Sabtu' OR $hari == 'Minggu'){
      $status_hari      = 'Extra Day';
      $status_kehadiran = 0;
    }else{
      $status_hari      = 'Work Day';
      $status_kehadiran = 3;
    }


    foreach ($getTidakHadir as $g){

      //echo $g->nip."<br>";

      $x = $this->m_testing->cekDouble($g->nip , $tanggal);
      if($x){
        echo $g->nip." - Sudah Ada<br>";
      }else{
        //echo $g->nip."<br>";
        $explodeDate  = explode(' ',$g->checkin_time);

        $data['absensi_id']       = "";
        $data['absensi_tanggal']  = $tanggal;
        $data['nip']              = $g->nip;
        $data['kd_skpd']          = $g->kd_skpd;
        $data['status_berkantor'] = 1;
        $data['status_kehadiran'] = $status_kehadiran;
        $data['hari']             = $hari;
        $data['status_hari']      = $status_hari;

        $this->m_testing->input($data);

      }
      
    }


    print("<pre>".print_r($getTidakHadir,true)."</pre>");

  }



  public function getAbsen() {
    //$tanggal       = date('Y-m-d');
    $tanggal       = '2020-08-26';
    $getAbsen = $this->m_testing->getByDate($tanggal);

    print("<pre>".print_r($getAbsen,true)."</pre>");

  }





  // FUNCTION DAY
  public function getDay($date){
    $daftar_hari = array(
      'Sunday'    => 'Minggu',
      'Monday'    => 'Senin',
      'Tuesday'   => 'Selasa',
      'Wednesday' => 'Rabu',
      'Thursday'  => 'Kamis',
      'Friday'    => 'Jumat',
      'Saturday'  => 'Sabtu'
    );
    
    $namahari = date('l', strtotime($date));
    
    return $daftar_hari[$namahari];
  }



  // TESTING CRONJOB
  public function push_tidakhadir_testing() {
    $tanggal       = date('Y-m-d');
    $getTidakHadir = $this->m_testing->getNotAttendanceX($tanggal);

    $hari    = $this->getDay($tanggal);

    if($hari == 'Sabtu' OR $hari == 'Minggu'){
      $status_hari      = 'Extra Day';
      $status_kehadiran = 0;
    }else{
      $status_hari      = 'Work Day';
      $status_kehadiran = 3;
    }


    foreach ($getTidakHadir as $g){

      //echo $g->nip."<br>";

      $x = $this->m_testing->cekDoubleX($g->nip , $tanggal);
      if($x){
        echo $g->nip." - Sudah Ada<br>";
      }else{
        //echo $g->nip."<br>";
        $explodeDate  = explode(' ',$g->checkin_time);

        $data['absensi_id']       = "";
        $data['absensi_tanggal']  = $tanggal;
        $data['nip']              = $g->nip;
        $data['kd_skpd']          = $g->kd_skpd;
        $data['status_berkantor'] = 1;
        $data['status_kehadiran'] = $status_kehadiran;
        $data['hari']             = $hari;
        $data['status_hari']      = $status_hari;

        $this->m_testing->inputX($data);

      }
      
    }


    //print("<pre>".print_r($getTidakHadir,true)."</pre>");

  }





  public function getAbsenAPI(){

    // GET TOKEN
    $api    = file_get_contents('https://asn.kendarikota.go.id/api/absensi/getAccessToken?client_id=absensikdi&client_secret=98fpj-jpkLs-88G5f-9Wq1r');
    $result = json_decode($api, true);

    // GET MY TOKEN FOR 1 HOUR
    $myToken =  $result['access_token'];

    // YOUR POST
    $nip   = '196205231989032006';
    $bulan = '8';
    $tahun = '2020';

    // SET CURL
    $curl  = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL               => "https://asn.kendarikota.go.id/api/absensi/AttendanceByNip",
      CURLOPT_RETURNTRANSFER    => true,
      CURLOPT_ENCODING          => "",
      CURLOPT_MAXREDIRS         => 10,
      CURLOPT_TIMEOUT           => 30,
      CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST     => "POST",
      CURLOPT_POSTFIELDS        => "nip=".$nip."&bulan=".$bulan."&tahun=".$tahun,
      CURLOPT_HTTPHEADER        => array(
        "Content-Type: application/x-www-form-urlencoded",
        "cache-control: no-cache",
        "Access-Token: ".$myToken.""
      ),
    ));

    $response = curl_exec($curl);
    $err      = curl_error($curl);
    curl_close($curl);

    if ($err) {
      // CURL ERROR
      echo "cURL Error #:" . $err;
    } else {
      
      // CURL SUCCESS : CHECK RESPONSE
      $data = json_decode($response,true);
      echo json_encode($data, JSON_PRETTY_PRINT);
    }


  }


  public function newlog(){
    $time = date('H');
    if($time == 13){

    
      $data['id']   = "";
      $data['nama'] = "";
      $data['date'] = date('Y-m-d H:i:s');

      $this->m_testing->input_newlog($data);
    }
  }
  

}
?>