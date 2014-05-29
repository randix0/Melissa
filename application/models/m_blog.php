<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_blog extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        if (!$data) return false;
        $this->db->insert('ideas', $data);
        $idea_id = $this->db->insert_id();
        return $idea_id;
    }

    public function create_attachment($data)
    {
        if (!$data) return false;
        $this->db->insert('ideas_attachments', $data);
        $idea_attach_id = $this->db->insert_id();
        return $idea_attach_id;
    }


    public function create_comment($data)
    {
        if (!$data || !isset($data['object_type']) || !$data['object_type'] || !isset($data['object_id']) || !$data['object_id']) return false;

        $this->db->insert('comments', $data);
        $comment_id = $this->db->insert_id();
        return $comment_id;
    }

    public function update($id, $data, $what = 'posts')
    {
        if (!$data || !$id) return false;
        $id = (int)$id;
        $this->db->update($what, $data, array('id'=>$id));
        return true;
    }

    public function getItem($getItem = array(), $fetch = false)
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
            $query = $this->db->get('posts', $limit, $offset);
        else
            $query = $this->db->get('posts', $limit);
        $post = $query->row_array();

        if (!$post) return false;

        if ($fetch) {
            $post['comments'] = $this->db->where(array('object_type'=>1,'object_id'=>(int)$post['id']))->order_by('id','ASC')->get('comments')->result_array();

            $tags_ids = array();
            $post_tags_links = $tags_raw = $this->db->get_where('tags_links', array('object_type'=>1, 'object_id'=>$post['id']))->result_array();
            if ($post_tags_links) {
                foreach($post_tags_links as $tl)
                {
                    $tags_ids[] = $tl['tag_id'];
                }
            }

            $tags = array();
            if ($tags_ids) {
                $tags_raw = $this->db->where_in('id',$tags_ids)->get('tags')->result_array();
                foreach($tags_raw as $t)
                {
                    $tags[$t['id']] = $t;
                }
            }

            $post['tags'] = $tags;
        }


        return $post;
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
        $posts = $query->result_array();

        if ($fetch) {
            $tags = array();
            $tags_raw = $this->db->get('tags')->result_array();
            foreach($tags_raw as $t)
            {
                $tags[$t['id']] = $t;
            }

            foreach($posts as &$p)
            {
                $post_tags = array();
                $post_tags_links = $tags_raw = $this->db->get_where('tags_links', array('object_type'=>1, 'object_id'=>$p['id']))->result_array();
                foreach($post_tags_links as $tl)
                {
                    $post_tags[] = $tags[$tl['tag_id']];
                }
                $p['tags'] = $post_tags;
            }
        }

        return $posts;
    }

}