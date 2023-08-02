<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hari extends CI_Controller {

	function __construct() {
        parent::__construct();
        $this->load->model('middleware','middle');

        date_default_timezone_set("Asia/Jakarta");
    }
    public function hitungHari(){
        $tanggalMulai = $_GET['tanggal'];
        $bulanAngsuran = $_GET['angs'];
        $arrayDay = array();
        $dateStartDays = date("d",strtotime($tanggalMulai));
        $currentMonth = date("m",strtotime($tanggalMulai));
        $currentYears = date("Y",strtotime($tanggalMulai));
        for ($i=0; $i <= $bulanAngsuran  ; $i++) { 

            if ($i == 0) {
                $arrayDay['totalhari'][] = cal_days_in_month(CAL_GREGORIAN,$currentMonth,$currentYears)-$dateStartDays+1;
                $arrayDay['bulan'][] = $currentYears.'-'.$currentMonth;
                // $arrayDay['tanggalAngsuran'][] = $currentYears.'-'.$currentMonth.'-'.cal_days_in_month(CAL_GREGORIAN,$currentMonth,$currentYears);
            } else if ($i == $bulanAngsuran){
                if ($dateStartDays+1 > cal_days_in_month(CAL_GREGORIAN,$currentMonth,$currentYears)) {
                    $arrayDay['totalhari'][] = cal_days_in_month(CAL_GREGORIAN,$currentMonth,$currentYears)+1;
                } else {
                    $arrayDay['totalhari'][] = $dateStartDays+1;
                }
                $totalHari = array_sum($arrayDay['totalhari']);
                $totalHaribuatEndDate = $totalHari-1;
                $arrayDay['bulan'][] = $currentYears.'-'.$currentMonth;
                $arrayDay['tanggalAngsuran'][] = date('Y-m-d', strtotime($tanggalMulai. ' + '.$totalHaribuatEndDate.' days'));
            } else {

                $arrayDay['totalhari'][] = cal_days_in_month(CAL_GREGORIAN,$currentMonth,$currentYears);
                $arrayDay['bulan'][] = $currentYears.'-'.$currentMonth;
                $arrayDay['tanggalAngsuran'][] = date('Y-m-d', strtotime($currentYears.'-'.$currentMonth.'-'.cal_days_in_month(CAL_GREGORIAN,$currentMonth,$currentYears)));
            }

            if ($currentMonth == 12) {
                $currentMonth = 1;
                $currentYears = $currentYears+1;
            } else {
                $currentMonth = $currentMonth+1;
            }
        }
        $totalHari = array_sum($arrayDay['totalhari']);
        $totalHaribuatEndDate = $totalHari-1;
        echo print_r($arrayDay).'<br>';
        echo "---------------".'<br>';
        echo "total hari : ".$totalHari.'<br>';
        echo "---------------".'<br>';
        echo "tanggal akhir : ".date('Y-m-d', strtotime($tanggalMulai. ' + '.$totalHaribuatEndDate.' days')).'<br>';
    }
}