<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

    }

    public function index($id = 0)
    {
        $this->load->model('m_blog');

        $posts = $this->m_blog->getItems(array(
            'order' => array('id'=>'desc')
        ), 'posts', true);

        $ps = array(
            '__PAGE' => 'blog',
            'posts' => $posts,
            'BLOG_FILTER' => 'posts'
        );
        $this->mysmarty->view('global/blog/posts/index.tpl', $ps);
    }

    public function archive($id = 0)
    {
        $this->load->model('m_blog');

        $posts = $this->m_blog->getItems(array(
            'order' => array('id'=>'desc')
        ), 'posts', true);

        $ps = array(
            '__PAGE' => 'blog',
            'posts' => $posts
        );
        $this->mysmarty->view('global/blog/posts/index.tpl', $ps);
    }

    public function tags($uri = '')
    {
        $this->load->model('m_blog');
        $this->load->model('m_model');

        $tags = $this->m_blog->getItems(array(
            'where' => array('is_deleted' => 0),
            'order' => array('id'=>'desc')
        ), 'tags', true);

        $posts = array();
        $tag = array();
        if ($uri) {
            $uri = trim($uri);
            $tag = $this->m_model->getItem((string)$uri, 'tags');
            if ($tag) {
                $tags_links_raw = $this->m_model->getItems(array('object_type'=>1, 'tag_id'=>$tag['id']), 'tags_links');
                if ($tags_links_raw) {
                    $tags_links = array();
                    foreach($tags_links_raw as $tl) {
                        $tags_links[] = (int)$tl['object_id'];
                    }
                    if ($tags_links) {
                        $posts = $this->db->where_in('id', $tags_links)->order_by('id', 'desc')->get('posts')->result_array();
                    }
                }
            }
        }

        if (!$posts) {
            $posts = $this->m_blog->getItems(array(
                'order' => array('id'=>'desc')
            ), 'posts', true);
        }

        $ps = array(
            '__PAGE' => 'blog',
            'tags' => $tags,
            'current_tag' => $tag,
            'posts' => $posts,
            'BLOG_FILTER' => 'tags'
        );
        $this->mysmarty->view('global/blog/tags/index.tpl', $ps);
    }

    public function post($id = 0)
    {
        $id = (int)$id;
        $this->load->model('m_blog');

        $post = $this->m_blog->getItem($id, 'posts', true);

        $ps = array(
            '__PAGE' => 'blog',
            'post' => $post,
            'BLOG_FILTER' => 'post'
        );
        $this->mysmarty->view('global/blog/post/index.tpl', $ps);
    }

}