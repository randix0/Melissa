<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($data, $table = 'posts')
    {
        if (!$data) return false;
        $this->db->insert($table, $data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function batch_create($data, $table = 'posts')
    {
        if (!$data) return false;
        $result = $this->db->insert_batch($table, $data);
        return $result;
    }

    public function update($id, $data, $table = 'postss')
    {
        if (!$data || !$id) return false;
        $id = (int)$id;
        $this->db->update($table, $data, array('id'=>$id));
        return true;
    }

    public function getItem($getItem = array(), $table = 'posts', $fetch = false)
    {
        $where = $order = array();
        $limit = 1;
        $offset = 0;

        if ($getItem) {
            if (is_array($getItem)) {
                if (isset($getItem['where']) && $getItem['where']) $where = $getItem['where'];
                if (isset($getItem['order']) && $getItem['order']) $order = $getItem['order'];
                //if (isset($getItem['limit']) && $getItem['limit']) $limit = $getItem['limit'];
                if (isset($getItem['offset']) && $getItem['offset']) $offset = $getItem['offset'];
            } elseif (is_int($getItem)){
                $where = array('id'=>(int)$getItem);
            } else {
                $where = array('uri'=>$getItem);
            }
        }

        $this->db->where($where);
        if ($order){
            foreach($order as $o_key=>$o_val)
                $this->db->order_by($o_key, $o_val);
        }

        if ($offset)
            $query = $this->db->get($table, $limit, $offset);
        else
            $query = $this->db->get($table, $limit);
        $posts = $query->row_array();

        if (!$posts) return false;

        if ($fetch) {

        }


        return $posts;
    }

    public function getItems($getItems = array(), $table = 'posts', $fetch = false)
    {
        $where = $order = array();
        $limit = 100;
        $offset = 0;

        if ($getItems) {
            if (isset($getItems['where']) && $getItems['where']) $where = $getItems['where'];
            if (isset($getItems['order']) && $getItems['order']) $order = $getItems['order'];
            if (isset($getItems['limit']) && $getItems['limit']) $limit = $getItems['limit'];
            if (isset($getItems['offset']) && $getItems['offset']) $offset = $getItems['offset'];
        }

        if (!$where || (!isset($where['is_deleted']) && !isset($where['is_deleted >'])))
            $where['is_deleted'] = 0;

        $this->db->where($where);
        if ($order){
            foreach($order as $o_key=>$o_val)
                $this->db->order_by($o_key, $o_val);
        }

        if ($offset)
            $query = $this->db->get($table, $limit, $offset);
        else
            $query = $this->db->get($table, $limit);
        $postss = $query->result_array();

        if ($fetch) {

        }

        return $postss;
    }

    public function save($table = 'posts', $id = 0, $data = array())
    {
        $id = (int)$id;
        if (!$data) return false;
        if ($id) {
            $this->db->update($table, $data, array('id'=>$id));
        } else {
            $this->db->insert($table, $data);
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function increase($table = 'posts', $id, $colomn = '', $inc = 1)
    {
        if (!$colomn || !$id || !$inc) return false;
        $id = (int)$id;

        $this->db->where('id', $id);
        if ($inc > 0)
            $this->db->set($colomn, $colomn.'+'.abs($inc), FALSE);
        else
            $this->db->set($colomn, $colomn.'-'.abs($inc), FALSE);
        $this->db->update($table);

        return true;
    }

}