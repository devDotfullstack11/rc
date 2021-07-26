<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_m extends MY_Model {

    public function getStoreBanners($cond){
        $this->db->select('sb.store_banner_id, sb.media_id, up.thumb_path,sb.description');

        $this->db->join('uploads AS up', 'up.id=sb.media_id', 'left');
        return $this->get('store_banners AS sb', $cond, -1);
    }

    public function setCustomerDevice($cusArr){
        //$chkDevice = $this->get('customer_devices', ['customer_id' => $cusArr['customer_id'], 'device_id' => $cusArr['device_id']], 0, true);
        $chkDevice = $this->get('rc_users_devices', ['device_id' => $cusArr['device_id']], 0, true);
        
        $cond = [];
        if(!empty($chkDevice)){
            $cond = ['device_id' => $chkDevice['device_id']];
        }
       
        $this->save('rc_users_devices', $cusArr, $cond);
    }

}