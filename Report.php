<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller {
  function __construct() {
    parent::__construct();
    error_reporting(0);
    $this->load->model("m_report");
    $this->load->model("m_skpd");
    $this->load->model("m_employee");
    $this->load->model("m_setting");
    $this->load->library('excel');
    if (!($this->session->userdata('user_id'))) {
      redirect('home');
    }
  }

  public function employee() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['skpd']            = $this->m_skpd->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/report/report_employee", $data);
    $this->load->view("attribute/footer", $setting);
  }


  public function attendance() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['skpd']            = $this->m_skpd->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/report/report_daily_attendance", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function attendance_sore() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['skpd']            = $this->m_skpd->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/report/report_daily_attendance_sore", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function monthly_attendance() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['employee']       = $this->m_employee->fetch_data($this->session->userdata('skpd_id'));
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/report/monthly_attandance", $data);
    $this->load->view("attribute/footer", $setting);
  }

  public function monthly_skpd() {
    $setting['settings_app'] = $this->m_setting->fetch_setting();
    $data['skpd']            = $this->m_skpd->fetch_data();
    $this->load->view("attribute/header", $setting);
    $this->load->view("attribute/menus", $setting);
    $this->load->view("admin/master_data/report/monthly_skpd", $data);
    $this->load->view("attribute/footer", $setting);
  }


  /* CETAK */

  public function print_employee(){
    $skpd  = $this->input->post('skpd');

    $value    = $this->m_report->fetch_data_employee($skpd);
    $getNama  = $this->m_report->get_nama_skpd($skpd);
    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Laporan Daftar Pegawai');
    
    //STYLING
    $styleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array(
            'argb' => '0000'
          )
        )
      )
    );
    
    //header
    //set report header
    $no = 1;
    $this->excel->getActiveSheet()->getStyle('A:G')->getFont()->setName('Times New Roman');
    $this->excel->getActiveSheet()->mergeCells('A1:G1');
    if($skpd == 0){
      $this->excel->getActiveSheet()->setCellValue('A1', 'REKAP DATA PEGAWAI KOTA KENDARI');
    }else{
      $this->excel->getActiveSheet()->setCellValue('A1', 'REKAP DATA PEGAWAI : '.$getNama[0]->nama);
    }
    
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
    
    
    //set column name
    $this->excel->getActiveSheet()->setCellValue('A3', 'NO');
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('B3', 'NAMA');
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('C3', 'NIP');
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('D3', 'JK');
    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('E3', 'SKPD');
    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('F3', 'UNIT');
    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('G3', 'BAGIAN');
    $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
    
    $this->excel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(4);
    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    $no    = 4;
    $nomor = 1;
    
    foreach ($value as $v) {
      
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('A' . $no, $nomor);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('B' . $no, $v->nama);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('C' . $no, "'".$v->nip);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('D' . $no, $v->kelamin);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('E' . $no, $v->nama_skpd);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('F' . $no, $v->unit_organisasi);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('G' . $no, $v->bagian_bidang);
      
     
      $no++;
      $nomor++;
    }
    
    $this->excel->getActiveSheet()->getStyle('A'.($no))->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->mergeCells('A'.($no).':F'.($no));
    $this->excel->getActiveSheet()->getStyle('A' . ($no))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A' . ($no))->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('A' . ($no), "TOTAL PEGAWAI");
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('G' . ($no), $nomor-1);
    $this->excel->getActiveSheet()->getStyle('A3:G' . (($no+1) - 1))->applyFromArray($styleArray);
    
    ob_end_clean();

    if($skpd==0){
      $filename = 'Rekap Pegawai Kota Kendari - '.date("Y-m-d H:i:s").'.xls'; //save our workbook as this file name
    }else{
      $filename = 'Rekap Pegawai  '.$getNama[0]->nama.'- '.date("Y-m-d H:i:s").'.xls'; //save our workbook as this file name
    }
    
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    
    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    
    $objWriter->save('php://output');
  }


  public function print_admin_skpd(){
    $skpd  = $this->input->post('skpd');

    $value    = $this->m_employee->fetch_data_admin_skpd();
    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Laporan Daftar Admin SKPD');
    
    //STYLING
    $styleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array(
            'argb' => '0000'
          )
        )
      )
    );
    
    //header
    //set report header
    $no = 1;
    $this->excel->getActiveSheet()->getStyle('A:D')->getFont()->setName('Times New Roman');
    $this->excel->getActiveSheet()->mergeCells('A1:D1');
    $this->excel->getActiveSheet()->setCellValue('A1', 'REKAP DATA ADMIN SKPD KOTA KENDARI');
    
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
    
    
    //set column name
    $this->excel->getActiveSheet()->setCellValue('A3', 'NO');
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('B3', 'NAMA');
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('C3', 'NIP');
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('D3', 'SKPD');
    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
    
    $this->excel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $no    = 4;
    $nomor = 1;
    
    foreach ($value as $v) {
      
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('A' . $no, $nomor);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('B' . $no, $v->nama);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('C' . $no, "'".$v->nip);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('D' . $no, $v->nama_skpd);
      
     
      $no++;
      $nomor++;
    }
    
    $this->excel->getActiveSheet()->getStyle('A'.($no))->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->mergeCells('A'.($no).':C'.($no));
    $this->excel->getActiveSheet()->getStyle('A' . ($no))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A' . ($no))->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('A' . ($no), "TOTAL ADMIN");
    $this->excel->getActiveSheet()->getStyle('D' . ($no))->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->getStyle('D' . ($no))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('D' . ($no))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('D' . ($no))->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('D' . ($no), $nomor-1);
    $this->excel->getActiveSheet()->getStyle('A3:D' . (($no+1) - 1))->applyFromArray($styleArray);
    
    ob_end_clean();

    
    $filename = 'Rekap Admin SKPD Kota Kendari - '.date("Y-m-d H:i:s").'.xls'; //save our workbook as this file name
    
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    
    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    
    $objWriter->save('php://output');
  }

  public function print_daily_attendance(){
    $skpd  = $this->input->post('skpd');
    $date  = $this->input->post('tanggal');

    $value   = $this->m_report->daily_attendance($date,$skpd);
    $value2  = $this->m_report->daily_attendance_lapangan($date,$skpd);
    $getNama = $this->m_report->get_nama_skpd($skpd);
    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Laporan Daftar Pegawai');
    
    //STYLING
    $styleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array(
            'argb' => '0000'
          )
        )
      )
    );
    
    //header
    //set report header
    $no = 1;
    $this->excel->getActiveSheet()->getStyle('A:G')->getFont()->setName('Times New Roman');
    $this->excel->getActiveSheet()->mergeCells('A1:G1');
    if($skpd == 0){
      $this->excel->getActiveSheet()->setCellValue('A1', 'REKAP ABSENSI PAGI PEGAWAI KOTA KENDARI');
    }else{
      $this->excel->getActiveSheet()->setCellValue('A1', 'REKAP ABSENSI PAGI PEGAWAI : '.$getNama[0]->nama);
    }
    
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
    
    
    //set column name
    $this->excel->getActiveSheet()->setCellValue('A3', 'NO');
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('B3', 'NAMA');
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('C3', 'NIP');
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('D3', 'JAM ABSEN');
    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('E3', 'STATUS KEHADIRAN');
    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('F3', 'KETERANGAN');
    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('G3', 'STATUS BERKANTOR');
    $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
    
    $this->excel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    $no    = 4;
    $nomor = 1;
    
    foreach ($value as $v) {

      if($v->checkin_status==1){
        $status = 'Hadir';
      }elseif($v->checkin_status==2){
        $status = 'Terlambat';
      }else{
        $status = 'Alpa';
      }


      if($v->sick_status==0){
        $sick = 'Sehat';
      }else{
        $sick = 'Sakit';
      }

      if($v->status==1){
        $k_status = 'Berkantor';
      }elseif($v->status==2){
        $k_status = 'WFH';
      }elseif($v->status==3){
        $k_status = 'Lapangan';
      }else{
        $k_status = 'Upacara';
      }


      
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('A' . $no, $nomor);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('B' . $no, $v->nama);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('C' . $no, "'".$v->nip);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('D' . $no, $v->checkin_time);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('E' . $no, $status);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('F' . $no, $sick);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('G' . $no, $k_status);
      
      $no++;
      $nomor++;
    }


    $nox    = $no;
    $nomorx = $nomor;

    foreach ($value2 as $v2) {

      if($v2->checkin_status==1){
        $status = 'Hadir';
      }elseif($v2->checkin_status==2){
        $status = 'Terlambat';
      }else{
        $status = 'Alpa';
      }


      if($v2->sick_status==0){
        $sick = 'Sehat';
      }else{
        $sick = 'Sakit';
      }

      if($v->status==1){
        $k_status = 'Berkantor';
      }elseif($v->status==2){
        $k_status = 'WFH';
      }elseif($v->status==3){
        $k_status = 'Lapangan';
      }else{
        $k_status = 'Upacara';
      }


      
      $this->excel->getActiveSheet()->getStyle('A' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('A' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('A' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('A' . $nox, $nomorx);
      $this->excel->getActiveSheet()->getStyle('B' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('B' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('B' . $nox, $v->nama);
      $this->excel->getActiveSheet()->getStyle('C' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('C' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('C' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('C' . $nox, "'".$v->nip);
      $this->excel->getActiveSheet()->getStyle('D' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('D' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('D' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('D' . $nox, $v->checkin_time);
      $this->excel->getActiveSheet()->getStyle('E' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('E' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('E' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('E' . $nox, $status);
      $this->excel->getActiveSheet()->getStyle('F' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('F' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('F' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('F' . $nox, $sick);
      $this->excel->getActiveSheet()->getStyle('G' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('G' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('G' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('G' . $no, $k_status);
      
     
      $nox++;
      $nomorx++;
    }



    
    /* $this->excel->getActiveSheet()->getStyle('A'.($no))->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->mergeCells('A'.($no).':F'.($no));
    $this->excel->getActiveSheet()->getStyle('A' . ($no))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A' . ($no))->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('A' . ($no), "TOTAL ABSENSI");
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('G' . ($no), $nomor-1); */
    $this->excel->getActiveSheet()->getStyle('A3:G' . (($nox) - 1))->applyFromArray($styleArray);
    
    ob_end_clean();

    if($skpd==0){
      $filename = 'Rekap Absensi Pagi Pegawai Kota Kendari - '.date("Y-m-d H:i:s").'.xls'; //save our workbook as this file name
    }else{
      $filename = 'Rekap Absensi Pagi Pegawai  '.$getNama[0]->nama.'- '.date("Y-m-d H:i:s").'.xls'; //save our workbook as this file name
    }
    
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    
    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    
    $objWriter->save('php://output');
  }



  public function print_daily_attendance_sore(){
    $skpd  = $this->input->post('skpd');
    $date  = $this->input->post('tanggal');

    $value   = $this->m_report->daily_attendance_sore($date,$skpd);
    $value2  = $this->m_report->daily_attendance_lapangan_sore($date,$skpd);
    $getNama = $this->m_report->get_nama_skpd($skpd);
    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Laporan Absensi Sore');
    
    //STYLING
    $styleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array(
            'argb' => '0000'
          )
        )
      )
    );
    
    //header
    //set report header
    $no = 1;
    $this->excel->getActiveSheet()->getStyle('A:F')->getFont()->setName('Times New Roman');
    $this->excel->getActiveSheet()->mergeCells('A1:F1');
    if($skpd == 0){
      $this->excel->getActiveSheet()->setCellValue('A1', 'REKAP ABSENSI SORE PEGAWAI KOTA KENDARI');
    }else{
      $this->excel->getActiveSheet()->setCellValue('A1', 'REKAP ABSENSI SORE PEGAWAI : '.$getNama[0]->nama);
    }
    
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
    
    
    //set column name
    $this->excel->getActiveSheet()->setCellValue('A3', 'NO');
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('B3', 'NAMA');
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('C3', 'NIP');
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('D3', 'JAM ABSEN');
    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('E3', 'STATUS KEHADIRAN');
    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('F3', 'STATUS BERKANTOR');
    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
    
    $this->excel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $no    = 4;
    $nomor = 1;
    
    foreach ($value as $v) {

      if($v->checkin_status==1){
        $status = 'Hadir';
      }elseif($v->checkin_status==2){
        $status = 'Terlambat';
      }else{
        $status = 'Alpa';
      }


     

      if($v->status==1){
        $k_status = 'Berkantor';
      }elseif($v->status==2){
        $k_status = 'WFH';
      }elseif($v->status==3){
        $k_status = 'Lapangan';
      }else{
        $k_status = 'Upacara';
      }


      
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('A' . $no, $nomor);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('B' . $no, $v->nama);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('C' . $no, "'".$v->nip);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('D' . $no, $v->checkin_time);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('E' . $no, $status);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('F' . $no, $k_status);
      
      $no++;
      $nomor++;
    }


    $nox    = $no;
    $nomorx = $nomor;

    foreach ($value2 as $v2) {

      if($v2->checkin_status==1){
        $status = 'Hadir';
      }elseif($v2->checkin_status==2){
        $status = 'Terlambat';
      }else{
        $status = 'Alpa';
      }

      if($v->status==1){
        $k_status = 'Berkantor';
      }elseif($v->status==2){
        $k_status = 'WFH';
      }elseif($v->status==3){
        $k_status = 'Lapangan';
      }else{
        $k_status = 'Upacara';
      }


      
      $this->excel->getActiveSheet()->getStyle('A' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('A' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('A' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('A' . $nox, $nomorx);
      $this->excel->getActiveSheet()->getStyle('B' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('B' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('B' . $nox, $v->nama);
      $this->excel->getActiveSheet()->getStyle('C' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('C' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('C' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('C' . $nox, "'".$v->nip);
      $this->excel->getActiveSheet()->getStyle('D' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('D' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('D' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('D' . $nox, $v->checkin_time);
      $this->excel->getActiveSheet()->getStyle('E' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('E' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('E' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('E' . $nox, $status);
      $this->excel->getActiveSheet()->getStyle('F' . $nox)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('F' . $nox)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('F' . $nox)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('F' . $nox, $k_status);
      
      $nox++;
      $nomorx++;
    }



    
    /* $this->excel->getActiveSheet()->getStyle('A'.($no))->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->mergeCells('A'.($no).':F'.($no));
    $this->excel->getActiveSheet()->getStyle('A' . ($no))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A' . ($no))->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('A' . ($no), "TOTAL ABSENSI");
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('G' . ($no))->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('G' . ($no), $nomor-1); */
    $this->excel->getActiveSheet()->getStyle('A3:F' . (($nox) - 1))->applyFromArray($styleArray);
    
    ob_end_clean();

    if($skpd==0){
      $filename = 'Rekap Absensi Sore Pegawai Kota Kendari - '.date("Y-m-d H:i:s").'.xls'; //save our workbook as this file name
    }else{
      $filename = 'Rekap Absensi Sore Pegawai  '.$getNama[0]->nama.'- '.date("Y-m-d H:i:s").'.xls'; //save our workbook as this file name
    }
    
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    
    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    
    $objWriter->save('php://output');
  }


  //BULANAN
  public function print_monthly_attendance(){
    $skpd   = $this->input->post('skpd_id');
    $nip    = $this->input->post('nip');
    $date   = $this->input->post('year')."-".$this->input->post('month');

    $value        = $this->m_report->monthly_attendance($date,$nip);
    $getNama      = $this->m_report->get_nama_skpd($skpd);
    $namaPegawai  = $this->m_employee->get($nip);
    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Laporan Daftar Absensi Bulanan');
    
    //STYLING
    $styleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array(
            'argb' => '0000'
          )
        )
      )
    );
    
    //header
    //set report header
    $no = 1;
    $this->excel->getActiveSheet()->getStyle('A:G')->getFont()->setName('Times New Roman');
    $this->excel->getActiveSheet()->mergeCells('A1:G1');
    
    $this->excel->getActiveSheet()->setCellValue('A1', 'REKAP ABSENSI BULANAN PEGAWAI : '.$getNama[0]->nama);
    
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
    
    
    // $this->excel->getActiveSheet()->setCellValue('B3', 'PEGAWAI');
    // $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
    // $this->excel->getActiveSheet()->setCellValue('C3', $nip);
    // $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);

    $this->excel->getActiveSheet()->setCellValue('B4', 'PEGAWAI');
    $this->excel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('C4', "'".$nip." - ".$namaPegawai[0]->nama);
    $this->excel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);

    $this->excel->getActiveSheet()->setCellValue('B5', 'BULAN/TAHUN');
    $this->excel->getActiveSheet()->getStyle('B5')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('C5', $this->input->post('month')."/".$this->input->post('year'));
    $this->excel->getActiveSheet()->getStyle('C5')->getFont()->setBold(true);


    //set column name
    $this->excel->getActiveSheet()->setCellValue('A7', 'NO');
    $this->excel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('B7', 'TANGGAL');
    $this->excel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('C7', 'JAM MASUK');
    $this->excel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('D7', 'JAM PULANG');
    $this->excel->getActiveSheet()->getStyle('D7')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('E7', 'STATUS');
    $this->excel->getActiveSheet()->getStyle('E7')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('F7', 'HARI');
    $this->excel->getActiveSheet()->getStyle('F7')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('G7', 'STATUS HARI');
    $this->excel->getActiveSheet()->getStyle('G7')->getFont()->setBold(true);
    
    $this->excel->getActiveSheet()->getStyle('A7:G7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    $no    = 8;
    $nomor = 1;
    
    foreach ($value as $v) {

      if($v->status_kehadiran==0){
        $status = 'HADIR';
      }elseif($v->checkin_status==1){
        $status = 'CUTI SAKIT';
      }elseif($v->checkin_status==2){
        $status = 'CUTI BESAR/ BERSALIN / KARNA HAL PENTING';
      }elseif($v->checkin_status==3){
        $status = 'ANPA KETERANGAN (ALPA)';
      }elseif($v->checkin_status==4){
        $status = 'SAKIT / IZIN DENGAN KETERANGAN';
      }else{
        $status = 'LIBUR';
      }


      
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('A' . $no, $nomor);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('B' . $no, $v->absensi_tanggal);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('C' . $no, $v->jam_masuk);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('D' . $no, $v->jam_pulang);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('E' . $no, $status);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('F' . $no, strtoupper($v->hari));
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('G' . $no, strtoupper($v->status_hari));
      
      $no++;
      $nomor++;
    }

    $this->excel->getActiveSheet()->getStyle('A7:G' . (($no) - 1))->applyFromArray($styleArray);
    
    ob_end_clean();

    
    $filename = 'Rekap Absensi Bulanan  '.$nip.'- Bulan '.$this->input->post('month').''.$this->input->post('year').'.xls'; //save our workbook as this file name
    
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    
    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    
    $objWriter->save('php://output');
  }


  public function print_absensi_bulanan(){
    require_once './assets/html2pdf/html2pdf.class.php';
    $skpd   = $this->input->post('skpd_id');
    $nip    = $this->input->post('nip');
    $date   = $this->input->post('year')."-".$this->input->post('month');

    $data['value']        = $this->m_report->monthly_attendance($date,$nip);
    $data['getNama']      = $this->m_report->get_nama_skpd($skpd);
    $data['namaPegawai']  = $this->m_employee->get($nip);
    $data['date']         = $this->input->post('month')."-".$this->input->post('year');


    $this->load->view('admin/master_data/report/cetak', $data);
  }


  public function print_monthly_skpd(){
    require_once './assets/html2pdf/html2pdf.class.php';
    $skpd   = $this->input->post('skpd_id');
    $date   = $this->input->post('year')."-".$this->input->post('month');
    
    $data['value']        = $this->m_report->monthly_skpd($date,$skpd);
    $data['getNama']      = $this->m_report->get_nama_skpd($skpd);
    $data['date']         = $this->input->post('month')."-".$this->input->post('year');


    $this->load->view('admin/master_data/report/cetak_bulanan_absensi', $data);
  }


   // PRINT MUTASI
   public function print_mutasi(){

    $value    = $this->m_employee->fetch_data_mutasi();
    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Laporan Daftar Pegawai Mutasi');
    
    //STYLING
    $styleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array(
            'argb' => '0000'
          )
        )
      )
    );
    
    //header
    //set report header
    $no = 1;
    $this->excel->getActiveSheet()->getStyle('A:C')->getFont()->setName('Times New Roman');
    $this->excel->getActiveSheet()->mergeCells('A1:C1');
    $this->excel->getActiveSheet()->setCellValue('A1', 'REKAP DATA PEGAWAI MUTASI KOTA KENDARI');
    
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
    
    
    //set column name
    $this->excel->getActiveSheet()->setCellValue('A3', 'NO');
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('B3', 'NAMA');
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('C3', 'NIP');
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
    
    $this->excel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $no    = 4;
    $nomor = 1;
    
    foreach ($value as $v) {
      
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('A' . $no, $nomor);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('B' . $no, $v->nama);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('C' . $no, "'".$v->nip);
      
     
      $no++;
      $nomor++;
    }
    
    $this->excel->getActiveSheet()->getStyle('A'.($no))->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->mergeCells('A'.($no).':B'.($no));
    $this->excel->getActiveSheet()->getStyle('A' . ($no))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A' . ($no))->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('A' . ($no), "TOTAL PEGAWAI MUTASI");
    $this->excel->getActiveSheet()->getStyle('C' . ($no))->getAlignment()->setWrapText(true);
    $this->excel->getActiveSheet()->getStyle('C' . ($no))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('C' . ($no))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('C' . ($no))->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('C' . ($no), $nomor-1);
    $this->excel->getActiveSheet()->getStyle('A3:C' . (($no+1) - 1))->applyFromArray($styleArray);
    
    ob_end_clean();

    
    $filename = 'Rekap Pegawai Mutasi Kota Kendari - '.date("Y-m-d H:i:s").'.xls'; //save our workbook as this file name
    
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    
    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    
    $objWriter->save('php://output');
  }
  
  
  // Absensi Upacara
  public function print_daily_ceremony(){
    $date  = $this->input->post('tanggal');

    $value   = $this->m_report->daily_attendance_ceremony($date);
    $this->excel->setActiveSheetIndex(0);
    $this->excel->getActiveSheet()->setTitle('Laporan Daftar Pegawai');
    
    //STYLING
    $styleArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN,
          'color' => array(
            'argb' => '0000'
          )
        )
      )
    );
    
    //header
    //set report header
    $no = 1;
    $this->excel->getActiveSheet()->getStyle('A:G')->getFont()->setName('Times New Roman');
    $this->excel->getActiveSheet()->mergeCells('A1:G1');
    
     
     $this->excel->getActiveSheet()->setCellValue('A1', 'REKAP ABSENSI UPACARA PEGAWAI KOTA KENDARI '. $date);
   
    
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
    
    
    //set column name
    $this->excel->getActiveSheet()->setCellValue('A3', 'NO');
    $this->excel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('B3', 'NAMA');
    $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('C3', 'NIP');
    $this->excel->getActiveSheet()->getStyle('C3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('D3', 'JAM ABSEN');
    $this->excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('E3', 'STATUS BERKANTOR');
    $this->excel->getActiveSheet()->getStyle('E3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('F3', 'SKPD');
    $this->excel->getActiveSheet()->getStyle('F3')->getFont()->setBold(true);
    $this->excel->getActiveSheet()->setCellValue('G3', 'HARI');
    $this->excel->getActiveSheet()->getStyle('G3')->getFont()->setBold(true);
    
    $this->excel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
    $no    = 4;
    $nomor = 1;
    
    foreach ($value as $v) {

      
      if($v->status_berkantor==1){
        $k_status = 'Berkantor';
      }elseif($v->status_berkantor==2){
        $k_status = 'WFH';
      }elseif($v->status_berkantor==3){
        $k_status = 'Lapangan';
      }else{
        $k_status = 'Upacara';
      }


      
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('A' . $no, $nomor);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('B' . $no, $v->nama_pegawai);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('C' . $no, "'".$v->nip);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('D' . $no, $v->jam_masuk);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('E' . $no, $k_status);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('F' . $no, $v->nama_skpd);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setWrapText(true);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
      $this->excel->getActiveSheet()->setCellValue('G' . $no, $v->hari);
      
      $no++;
      $nomor++;
    }


    $this->excel->getActiveSheet()->getStyle('A3:G' . (($no) - 1))->applyFromArray($styleArray);
    
    ob_end_clean();

    
     $filename = 'Rekap Absensi Upacara Pegawai Kota Kendari - '.date("Y-m-d H:i:s").'.xls'; //save our workbook as this file name
    
    
    header('Content-Type: application/vnd.ms-excel'); //mime type
    header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
    header('Cache-Control: max-age=0'); //no cache
    
    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format
    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    
    $objWriter->save('php://output');
  }

  

}
?>
