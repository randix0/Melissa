<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings{
    private $settings = array();
    private $settingsData = array();
    private $cronData = array();
    private	$CI;

    function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('m_settings');
        $raw_data = $this->CI->m_settings->getItems();
        foreach($raw_data as $r){
            $this->settings[$r['code']] = $r;
            $this->settingsData[$r['code']] = $r['value'];

            if ($r['type'] == 'date' && $r['status'] == 0 && $r['value'] > 0 && $r['value'] < time()){
                $this->cronData[] = $r['code'];
            }
        }
        if ($this->cronData) {
            $this->cron();
        }
    }

    function __get($code)
    {
        if(isset($this->settingsData[$code]))
            return $this->settingsData[$code];
        else
            return NULL;
    }

    function set($params = array())
    {
        if (!$params) return false;
        $i = 0;
        foreach($params as $p_key=>$p_val)
        {
            if (isset($this->settings[$p_key]) && $this->settings[$p_key] && isset($this->settings[$p_key]['id'])) {
                $this->CI->m_settings->update($this->settings[$p_key]['id'], array('value'=>$p_val));
                $this->settingsData[$p_key] = $p_val;
                $i++;
            } else {
                $newSetting = array('code'=>$p_key, 'value'=>$p_val);
                $id = $this->CI->m_settings->create($newSetting);
                $newSetting['id'] = $id;
                $this->settings[$p_key] = $newSetting;
                $this->settingsData[$p_key] = $p_val;
                $i++;
            }
        }
        return ($i)?true:false;
    }

    function &getAllData()
    {
        return $this->settingsData;
    }

    function cron()
    {
        if (!$this->cronData) return;
        $code = $this->cronData[0];
        ignore_user_abort(true);
        set_time_limit(0);
        $setting = $this->settings[$code];
        $id = $setting['id'];
        $this->CI->m_settings->update($id,array('status'=>1));
        $result = false;
        if($setting['code'] == 'stage1_start') {}
        elseif($setting['code'] == 'stage2_start') {
            $result = $this->cron_proccess_top10(1);
            if ($result) $this->CI->m_settings->update($this->settings['current_stage']['id'], array('value'=>2));
        }
        elseif($setting['code'] == 'stage3_start') {
            $result = $this->cron_proccess_top10(2);
            if ($result) $this->CI->m_settings->update($this->settings['current_stage']['id'], array('value'=>3));
        }
        elseif($setting['code'] == 'stage4_start') {
            $result = $this->cron_proccess_top10(3);
            if ($result) $this->CI->m_settings->update($this->settings['current_stage']['id'], array('value'=>4));
        }

        if($result)
            $this->CI->m_settings->update($id,array('status'=>10));


    }

    function cron_proccess_top10($stage = 0)
    {
        if (!$stage) $stage = $this->current_stage;
        $this->CI->load->model('m_photos');
        $getItems = array(
            'where' => array(
                'is_deleted' => 0,
                'is_stage_winner' => 0
            ),
            'order' => array('rating' => 'desc'),
            'limit' => 10,
            'offset' => 0,

        );
        $top10 = $this->CI->m_photos->getItems($getItems);
        $ids = array();
        foreach($top10 as $photo)
        {
            $ids[] = $photo['id'];
        }

        $this->CI->m_photos->update($ids, array('is_stage_winner'=>1,'top_ten_stage'=>$stage));
        return true;
    }
}