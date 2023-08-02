<?php

class Polis_model extends CI_Model {
    
    public function downloadFromSimas($url){
        $this->load->helper('simple_html_dom');
        // die($url);
        $html = file_get_html($url);
        // die($html);
        foreach($html->find('a') as $element) {
            redirect("https://api.simasinsurtech.com/schedule_polis/asuransi_kredit/".$element->href);
        }
    }
}
?>