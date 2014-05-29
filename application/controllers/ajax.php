<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */

    protected $isAjax = false;

    function __construct()
    {
        parent::__construct();
        $this->load->library('json');
        $this->load->helper('url');
        $this->load->helper('slugify');
        $this->load->model('m_model');
        $this->isAjax = $this->input->is_ajax_request();
    }

    protected function returner($result = null, $goto = '')
    {
        if ($this->isAjax)
            return $this->json->parse($result);
        else
            return redirect($goto);
    }

    public function index()
    {
        //$this->load->view('welcome_message');
        $ps = array(
            '__PAGE' => 'main'
        );
        $this->json->parse($ps);
    }

    public function getMorePhotos()
    {
        $instagram_64 = $this->input->post('pagination');
        $instagram_url = base64_decode($instagram_64);
        $instagram_json = file_get_contents($instagram_url);
        $instagram = json_decode($instagram_json, true);

        $ps = array(
            'instagram' => $instagram
        );
        $this->mysmarty->view('std/instagram/index.tpl', $ps);
    }

    public function saveCategory()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        //if (!$this->user->logged()) return $this->json->parse($result);
        $RAW = $this->input->post('item');
        $category_id = (int)$this->input->post('id');
        if (!$RAW || !isset($RAW['iname']) || !$RAW['iname']) return $this->json->parse($result);

        $this->load->model('m_products');

        $data = array(
            'uri' => trim($RAW['uri']),
            'iname' => trim($RAW['iname']),
            'idesc' => trim($RAW['idesc']),
        );

        $category_id = $this->m_products->save('categories',$category_id, $data);

        if ($category_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function saveCollection()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        //if (!$this->user->logged()) return $this->json->parse($result);
        $RAW = $this->input->post('item');
        $collection_id = (int)$this->input->post('id');
        if (!$RAW || !isset($RAW['iname']) || !$RAW['iname']) return $this->json->parse($result);

        $this->load->model('m_products');

        $data = array(
            'uri' => trim($RAW['uri']),
            'iname' => trim($RAW['iname']),
            'idesc' => trim($RAW['idesc']),
        );

        $collection_id = $this->m_products->save('collections',$collection_id, $data);

        if ($collection_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function savePost()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        //if (!$this->user->logged()) return $this->json->parse($result);
        $RAW = $this->input->post('item');
        $file = $this->input->post('file');
        $post_id = (int)$this->input->post('id');
        if (!$RAW || !isset($RAW['iname']) || !$RAW['iname']) return $this->json->parse($result);

        $this->load->model('m_model');

        $iname = trim($RAW['iname']);
        $idesc = trim($RAW['idesc']);
        $is_deleted = (int)$RAW['is_deleted'];
        $data = array(
            'iname' => $iname,
            'uri' => slugify($iname),
            'idesc' => $idesc,
            'is_deleted' => $is_deleted
        );

        $photos = array();
        if ($file && isset($file['store_name']) && $file['store_name'] && isset($file['upload_path']) && $file['upload_path']) {
            $this->load->library('imagine');

            $photo_cover = array();

            if (is_array($file['store_name'])) {
                foreach($file['store_name'] as $i=>$file_name)
                {
                    $temp_photo = $this->imagine->proccessPhoto(array('store_name'=>$file_name, 'upload_path'=>$file['upload_path']));
                    if ($file['cover'] > -1 && $file['cover'] == $i) $photo_cover = $temp_photo;
                    $photos[] = array(
                        'photo_s' => $temp_photo['s'],
                        'photo_m' => $temp_photo['m'],
                        'photo_b' => $temp_photo['b'],
                        'iname' => (isset($file['iname'][$i]) && $file['iname'][$i])?$file['iname'][$i]:'',
                        'idesc' => (isset($file['idesc'][$i]) && $file['idesc'][$i])?$file['idesc'][$i]:'',
                    );
                }

            } else {
                $photos[] = $photo_cover = $this->imagine->proccessPhoto($file);
            }

            if ($photos && isset($photo_cover) && $photo_cover) {
                $data['photo_s'] = $photo_cover['s'];
                $data['photo_m'] = $photo_cover['m'];
                $data['photo_b'] = $photo_cover['b'];
            }
        }

        if ($RAW['add_date'] && is_array($RAW['add_date'])) {
            list($ad_y,$ad_m,$ad_d) = explode('-',$RAW['add_date']['date']);
            list($ad_h,$ad_i) = explode(':',$RAW['add_date']['time']);
            $data['add_date'] = mktime($ad_h,$ad_i,date('s'),$ad_m,$ad_d,$ad_y);
        }

        $goto = false;
        if (!$post_id) $goto = true;
        $post_id = $this->m_model->save('posts',$post_id, $data);
        if ($goto) $result['goto'] = '/admin/posts/'.$post_id;

        $tags_ids = array();
        if (isset($RAW['tags']) && $RAW['tags'] && is_array($RAW['tags'])) {
            //$tags = array();
            $tags_ids = $RAW['tags'];
        }

        if (isset($RAW['new_tags']) && $RAW['new_tags']) {
            $tags_raw = explode(',',$RAW['new_tags']);
            foreach($tags_raw as &$t)
            {
                $t = trim($t);
                $tag_exists = $this->m_model->getItem(array(
                    'where' => array('iname'=>$t)
                ));
                if (!$tag_exists)
                    $tags_ids[] = $this->m_model->create(array(
                        'iname' => $t,
                        'uri' => slugify($t)
                    ),'tags');
            }
        }

        $result['tags'] = $RAW['new_tags'];

        if ($tags_ids) {
            $tags_links = array();
            foreach($tags_ids as $t)
            {
                $tags_links[] = array(
                    'object_type' => 1,
                    'object_id' => $post_id,
                    'tag_id' => (int)$t
                );
            }
            $this->m_model->batch_create($tags_links, 'tags_links');
        }

        if ($photos) {
            $p_add_date = time();
            foreach($photos as $p)
            {
                $p['posts_id'] = $post_id;
                $p['add_date'] = $p_add_date;
                $this->m_model->save('posts_photos', 0, $p);
            }
        }

        if ($post_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function saveProduct()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        //if (!$this->user->logged()) return $this->json->parse($result);
        $RAW = $this->input->post('item');
        $file = $this->input->post('file');
        $product_id = (int)$this->input->post('id');
        $product = array();
        if (!$RAW || !isset($RAW['iname']) || !$RAW['iname']) return $this->json->parse($result);

        $this->load->model('m_products');
        if ($product_id) $product = $this->m_products->getItem($product_id, true);

        $data = array(
            'uri' => trim($RAW['uri']),
            'iname' => trim($RAW['iname']),
            'idesc' => trim($RAW['idesc']),
            'materials' => trim($RAW['materials']),
            'dimensions' => trim($RAW['dimensions']),
            'iname_ru' => trim($RAW['iname_ru']),
            'idesc_ru' => trim($RAW['idesc_ru']),
            'materials_ru' => trim($RAW['materials_ru']),
            'dimensions_ru' => trim($RAW['dimensions_ru']),
            'price' => (int)$RAW['price'],
            'category_id' => (int)$RAW['category_id'],
            'collection_id' => (int)$RAW['collection_id'],
            'is_featured' => (int)$RAW['is_featured'],
            'is_deleted' => (int)$RAW['is_deleted'],
        );

        $photos = array();
        if ($file && isset($file['store_name']) && $file['store_name'] && isset($file['upload_path']) && $file['upload_path']) {
            $this->load->library('imagine');

            $photo_cover = array();

            if (is_array($file['store_name'])) {
                foreach($file['store_name'] as $i=>$file_name)
                {
                    $temp_photo = $this->imagine->proccessPhoto(array('store_name'=>$file_name, 'upload_path'=>$file['upload_path']));
                    if ($file['cover'] > -1 && $file['cover'] == $i) $photo_cover = $temp_photo;
                    $photos[] = array(
                        'photo_s' => $temp_photo['s'],
                        'photo_m' => $temp_photo['m'],
                        'photo_b' => $temp_photo['b'],
                        'iname' => (isset($file['iname'][$i]) && $file['iname'][$i])?$file['iname'][$i]:'',
                        'idesc' => (isset($file['idesc'][$i]) && $file['idesc'][$i])?$file['idesc'][$i]:'',
                    );
                }

            } else {
                $photos[] = $photo_cover = $this->imagine->proccessPhoto($file);
            }

            if ($photos && isset($photo_cover) && $photo_cover) {
                $data['photo_s'] = $photo_cover['s'];
                $data['photo_m'] = $photo_cover['m'];
                $data['photo_b'] = $photo_cover['b'];
            }
        }

        $product_id = $this->m_products->save('products', $product_id, $data);


        if (isset($RAW['details']) && $RAW['details'] && $details = $RAW['details']) {
            foreach($details as $d)
                $this->m_products->save('products_details', 0, array('product_id'=>$product_id, 'detail_id'=>$d));
        }

        if (isset($RAW['colors']) && $RAW['colors'] && $colors = $RAW['colors']) {
            foreach($colors as $d)
                $this->m_products->save('products_colors', 0, array('product_id'=>$product_id, 'color_id'=>$d));
        }

        if ($photos) {
            $p_add_date = time();
            foreach($photos as $p)
            {
                $p['product_id'] = $product_id;
                $p['add_date'] = $p_add_date;
                $this->m_products->save('products_photos', 0, $p);
            }
        }

        if ($product_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function del($table)
    {
        $result = array('status' => 'error');
        $RAW = $this->input->post('item');
        if (!$RAW || !$table || !preg_match('/^(posts|comments|tags_links|users|music|video)$/',$table,$match) || !isset($RAW['id']) || !$RAW['id']) return $this->json->parse($result);

        $where = array(
            'id' => (int)$RAW['id']
        );

        if ($table && $where) {
            $object = $this->db->get_where($table, $where, 1)->row_array();

            if ($object){
                $del = $this->db->delete($table, $where);
                if ($del){
                    if ($object['object_type'] == 1) {
                        $this->m_model->increase('posts', (int)$object['object_id'], 'comments_count', -1);
                    }
                    $result['status'] = 'success';
                }
            }
        }

        return $this->json->parse($result);
    }

    public function saveProductCover()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        $RAW = $this->input->post('item');
        if (!$RAW) return $this->json->parse($result);

        $this->load->model('m_products');

        $where = array(
            'id' => (int)$RAW['item_id'],
            'product_id' => (int)$RAW['product_id']
        );

        $photo = $this->db->where('id', (int)$RAW['item_id'])->get('products_photos', 1)->row_array();

        $product_update = array(
            'photo_s' => $photo['photo_s'],
            'photo_m' => $photo['photo_m'],
            'photo_b' => $photo['photo_b'],
        );

        $product_id = $this->m_products->save('products', (int)$RAW['product_id'], $product_update);

        if ($product_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function saveNewsCover()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        $RAW = $this->input->post('item');
        if (!$RAW) return $this->json->parse($result);

        $this->load->model('m_products');

        $where = array(
            'id' => (int)$RAW['item_id'],
            'news_id' => (int)$RAW['news_id']
        );

        $photo = $this->db->where('id', (int)$RAW['item_id'])->get('news_photos', 1)->row_array();

        $news_update = array(
            'photo_s' => $photo['photo_s'],
            'photo_m' => $photo['photo_m'],
            'photo_b' => $photo['photo_b'],
        );

        $news_id = $this->m_products->save('news', (int)$RAW['news_id'], $news_update);

        if ($news_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function saveDetail()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        //if (!$this->user->logged()) return $this->json->parse($result);
        $RAW = $this->input->post('item');
        $file = $this->input->post('file');
        $detail_id = (int)$this->input->post('id');
        if (!$RAW || !isset($RAW['iname']) || !$RAW['iname']) return $this->json->parse($result);

        $this->load->model('m_products');

        $data = array(
            'uri' => trim($RAW['uri']),
            'iname' => trim($RAW['iname']),
            'idesc' => trim($RAW['idesc']),
            'iname_ru' => trim($RAW['iname_ru']),
            'idesc_ru' => trim($RAW['idesc_ru']),
            'collection_id' => (int)$RAW['collection_id'],
        );

        $photo = array();
        if ($file && isset($file['store_name']) && $file['store_name'] && isset($file['upload_path']) && $file['upload_path']) {
            $this->load->library('imagine');
            $photo = $this->imagine->proccessPhoto($file);

            if ($photo) {
                $data['photo_s'] = $photo['s'];
                $data['photo_m'] = $photo['m'];
                $data['photo_b'] = $photo['b'];
            }
        }

        $detail_id = $this->m_products->save('details', $detail_id, $data);

        if ($detail_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function saveColor()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        //if (!$this->user->logged()) return $this->json->parse($result);
        $RAW = $this->input->post('item');
        $file = $this->input->post('file');
        $color_id = (int)$this->input->post('id');
        if (!$RAW || !isset($RAW['iname']) || !$RAW['iname']) return $this->json->parse($result);

        $this->load->model('m_products');

        $data = array(
            'uri' => trim($RAW['uri']),
            'iname' => trim($RAW['iname']),
            'idesc' => trim($RAW['idesc']),
            'iname_ru' => trim($RAW['iname_ru']),
            'idesc_ru' => trim($RAW['idesc_ru']),
        );

        $photo = array();
        if ($file && isset($file['store_name']) && $file['store_name'] && isset($file['upload_path']) && $file['upload_path']) {
            $this->load->library('imagine');
            $photo = $this->imagine->proccessPhoto($file);

            if ($photo) {
                $data['photo_s'] = $photo['s'];
                $data['photo_m'] = $photo['m'];
                $data['photo_b'] = $photo['b'];
            }
        }

        $color_id = $this->m_products->save('colors', $color_id, $data);

        if ($color_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function saveIdea()
    {
        $result = array('status' => 'error', 'errors' => array());

        $item = array();
        $required = array(
            'link','iname','idesc','contact_first_name','contact_last_name','contact_email','contact_phone','contact_role'
        );

        $RAW = $this->input->post('item');
        $idea_id = $this->input->post('id');

        foreach($required as $r)
        {
            if (!isset($RAW[$r]) || !$RAW[$r]) $result['errors'][$r] = 1;
        }

        if (!$idea_id) {
            $item['user_id'] = $this->user->uid();
            $item['add_date'] = time();

        }

        if ($result['errors'])
            return $this->json->parse($result);

        $item['iname'] = strip_tags($RAW['iname']);
        $item['idesc'] = $RAW['idesc'];
        $item['contact_first_name'] = strip_tags($RAW['contact_first_name']);
        $item['contact_last_name'] = strip_tags($RAW['contact_last_name']);
        $item['contact_email'] = strip_tags($RAW['contact_email']);
        $item['contact_phone'] = strip_tags($RAW['contact_phone']);
        $item['contact_role'] = strip_tags($RAW['contact_role']);

        if ($this->user->access_level > 50 && isset($RAW['is_sample'])){
            if ($RAW['is_sample'])
                $item['is_sample'] = 1;
            else
                $item['is_sample'] = 0;
        }

        $link = $RAW['link'];
        $link = str_replace('http://', '', $link);
        $link = str_replace('www.', '', $link);
        preg_match('/([^\/]+)/', $link, $matches);
        $hoster = $matches[1];
        $result['$hoster']=$hoster;
        $matches = array();
        if($hoster == 'youtube.com' && preg_match('/(?:\?|\&)v\=([A-z0-9\-\_]+)/', $link, $matches)) {
            $item['youtube_img'] = 'http://i1.ytimg.com/vi/'.$matches[1].'/0.jpg';
            $item['youtube_code'] = $matches[1];
        } else {
            $result['errors']['link'] = 2;
        }


        if (!$result['errors']) {
            $this->load->model('m_ideas');
            if (!$idea_id)
                $idea_id = $this->m_ideas->create($item);
            else
                $this->m_ideas->update($idea_id,$item);
            $item['id'] = $idea_id;
            if ($idea_id > 0){
                $result = array(
                    'status' => 'success',
                    'item' => $item,
                    'goto' => base_url('/idea/'.$idea_id)
                );
                $qr_name = $this->m_ideas->generateQR($idea_id);
                $this->m_ideas->update($idea_id,array('qr_code'=>$qr_name));

            }
        }

        $this->json->parse($result);
    }

    public function shareIdea($handler, $idea_id = 0)
    {
        if (!$this->user->logged()) return false;
        $config	 = &get_config();
        $link = $config['base_url'].'idea/'.$idea_id;
        if ($handler == 'facebook') {
            $facebook_id = $this->user->facebook_id;
            $access_token = $this->user->facebook_oa_access_token;

            $token_url = 'https://graph.facebook.com/'.$facebook_id.'/feed';
            $data = 'access_token='.$access_token.'&link='.urlencode($link);
        }

        if ($token_url && $data) {
            $contents = file_get_contents($token_url, false, stream_context_create(array('http'=>array(
                'method'	=>'POST',
                'header'	=> "Content-type: application/x-www-form-urlencoded\r\n".
                    "Content-Length: " . strlen($data) . "\r\n",
                'content'	=>$data
            ))));

            $response = json_decode($contents, true);
            var_dump($response);
        }
    }

    public function voteIdea()
    {
        $this->load->helper('cookie');
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);

        $RAW = $this->input->post('item');

        if (!$this->user->logged() || !isset($RAW['handler_type']) || !$RAW['handler_type'] || !isset($RAW['idea_id']) || !$RAW['idea_id']) return $this->json->parse($result);

        $user_id = $this->user->uid();
        $is_judge = $this->user->is_judge;

        $idea_id = (int)$RAW['idea_id'];
        $handler_type = $RAW['handler_type'];
        $is_deleted = 0;

        if (!$is_judge && !preg_match('/^(fb|vk|gp|tw|facebook|vkontakte|google|twitter)$/',$handler_type)) return $this->json->parse($result);
        if (preg_match('/^(fb|vk|gp|tw)$/',$handler_type)) {
            if ($handler_type == 'fb') $handler_type = 'facebook';
            elseif ($handler_type == 'vk') $handler_type = 'vkontakte';
            elseif ($handler_type == 'gp') $handler_type = 'google';
            elseif ($handler_type == 'tw') $handler_type = 'twitter';
            elseif ($handler_type == 'login' && $is_judge) $handler_type == 'login';
        }

        if (($handler_type == 'facebook' && !$this->user->facebook_id) || ($handler_type == 'vkontakte' && !$this->user->vkontakte_id) || ($handler_type == 'google' && !$this->user->google_id) || ($handler_type == 'twitter' && !$this->user->twitter_id)) $is_deleted = 1;

        $this->load->model('m_ideas');

        $isVoted = $this->m_ideas->isVoted($idea_id, $user_id);
        $idea = $this->m_ideas->getItem($idea_id);

        if ($isVoted || $idea['isVoted']) {
            $result['code'] = 'isVoted';
            return $this->json->parse($result);
        }

        if ($idea && isset($idea['is_deleted']) && !$idea['is_deleted']) {
            $vote_id = $this->m_ideas->vote($idea_id, $handler_type, $this->user->uid(), $idea, $is_judge, $is_deleted);
            if ($vote_id) $result['status'] = 'success';
        } elseif ($idea['user_id'] == $user_id) {
            $result['code'] = 'isVoted';
            return $this->json->parse($result);
        } else {
            $result['errors'][] = 'idea404';
        }

        return $this->json->parse($result);
    }

    public function getIdeas()
    {
        $this->load->model('m_ideas');
        $ideas = $this->m_ideas->getItems(array(),array(), true);
        $result = array('status'=>'success');
        $result['html'] = $this->mysmarty->view('global/idea/items/index.tpl', array('ideas'=>$ideas),false,true);
        return $this->json->parse($result);
    }

    public function getIdea($idea_id = 0, $order_by = '', $direction = '')
    {
        $this->load->model('m_ideas');
        $where = array();
        if ($direction == 'prev') $where['id <'] = $idea_id;
        elseif ($direction == 'next') $where['id >'] = $idea_id;
        $idea = $this->m_ideas->getItem($where, true);

        $result = array('status'=>'success');
        $result['html'] = $this->mysmarty->view('global/idea/ajaxItem/item.tpl', array('idea'=>$idea, 'class'=>$direction),false,true);
        return $this->json->parse($result);
    }

    public function isAuthNeeded($handler = '')
    {
        $result = array('status'=>'error','needed' => 1);
        if (!$handler) return $this->json->parse($result);

        $user_id = $this->user->uid();

        if (preg_match('/^(facebook|vkontakte|google|twitter)$/',$handler)) $result['status'] = 'success';

        if ($user_id) {
            $handler_id = 0;
            if ($handler == 'facebook')
                $handler_id = $this->user->facebook_id;
            elseif ($handler == 'vkontakte')
                $handler_id = $this->user->vkontakte_id;
            elseif ($handler == 'google')
                $handler_id = $this->user->google_id;
            elseif ($handler == 'twitter')
                $handler_id = $this->user->twitter_id;

            if ($handler_id){
                $result['needed'] = 0;
                $result[$handler.'_id'] = $handler_id;
            } else {
                $this->session->set_userdata('aC_stop',1);
            }
        }

        return $this->json->parse($result);
    }

    public function uploadFile($upload_type = '', $item_id = 0)
    {
        $this->load->helper('file');
        $result = array('status'=>'error');

        if (!$upload_type || !$item_id || !preg_match('/^(video)$/',$upload_type)) return $this->json->parse($result);

        if (!$_FILES || !isset($_FILES['userfile']) || !$_FILES['userfile'] || !isset($_FILES['userfile']['name'])) return $this->json->parse($result);

        $upload_path = 'upload/'.$upload_type.'/';
        $upload_dir = FCPATH.$upload_path;

        $allowed_files = array(
            //'attachments' => 'pdf|doc|docx|png|jpg',
            'video' => 'png|jpg',
        );

        $limit = array(
            'video' => 1,
        );

        $files = array();
        $files_i = 0;

        foreach($_FILES['userfile']['name'] as $i=>$name)
        {
            if ($files_i >= $limit[$upload_type]) {
                unlink($_FILES['userfile']['tmp_name'][$i]);
                continue;
            }
            $extension = '';
            switch($_FILES['userfile']['type'][$i]) {
                case 'image/png': $extension = 'png'; break;
                case 'image/jpeg': $extension = 'jpg'; break;
                case 'image/gif': $extension = 'gif'; break;
                case 'application/pdf': $extension = 'pdf'; break;
                case 'application/msword': $extension = 'doc'; break;
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document': $extension = 'docx'; break;
                default: $extension = 'delete'; break;
            }

            if (!preg_match('/^'.$allowed_files[$upload_type].'$/',$extension)) continue;

            if ($extension == 'delete') {
                unlink($_FILES['userfile']['tmp_name'][$i]);
                continue;
            }

            $store_name = sha1($name.$_FILES['userfile']['tmp_name'][$i].time()).'.'.$extension;

            $file = array(
                'name' => $name,
                'store_name' => $store_name,
                'type' => $_FILES['userfile']['type'][$i],
                'ext' => $extension,
                'path' => $upload_path.$store_name,
                'size' => $_FILES['userfile']['size'][$i],
                'idea_id' => $item_id
            );

            if ($copy_status = copy($_FILES['userfile']['tmp_name'][$i], $upload_dir.$store_name))
                unlink($_FILES['userfile']['tmp_name'][$i]);

            $files[] = $file;
            $files_i++;
        }

        //var_dump($_FILES['userfile']);
        if ($files) {
            $result['status'] = 'success';
            $result['files'] = $files;
            $result['upload_path'] = $upload_path;
            $result['html'] = $this->mysmarty->view('modals/upload/'.$upload_type.'.tpl', array('files' => &$files), false, true);
        }
        return $this->json->parse($result);
    }

    public function saveAttachments()
    {
       //if (!$this->user->logged()) return false;
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        $this->load->model('m_ideas');
        $RAW = $this->input->post('item');

        if (!$RAW || !is_array($RAW))
            $this->json->parse($result);

        $attachments = array();

        $i = 0;
        foreach($RAW as $file)
        {
            if ($i > 5) continue;
            $data = array(
                'idea_id' => (int)$file['idea_id'],
                'type' => $file['type'],
                'ext' => $file['ext'],
                'user_id' => $this->user->uid(),
                'iname' => $file['iname'],
                'path' => $file['path'],
                'add_date' => time()
            );
            $idea_attach_id = $this->m_ideas->create_attachment($data);
            if ($idea_attach_id)
                $attachments[] = $data;

        }
        if ($attachments) {
            $result['status'] = 'success';
            $result['html'] = $this->mysmarty->view('global/idea/attachments/index.tpl', array('attachments' => &$attachments), false, true);
        }
        return $this->json->parse($result);
    }

    public function saveTeam()
    {
        //if (!$this->user->logged()) return false;
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        $this->load->model('m_ideas');
        $RAW = $this->input->post('item');

        if (!$RAW || !is_array($RAW))
            $this->json->parse($result);

        $team = array();

        $i = 0;
        foreach($RAW as $file)
        {
            if ($i > 5) continue;
            /*
            $data = array(
                'idea_id' => (int)$file['idea_id'],
                'type' => $file['type'],
                'ext' => $file['ext'],
                'user_id' => $this->user->uid(),
                'iname' => $file['iname'],
                'path' => 'upload/'.$file['store_name'],
                'add_date' => time()
            );
            */

            if (!preg_match('/^(png|jpg)$/',$file['ext'])) continue;

            $avatar = array(
                's' => 'upload/team/s_'.$file['store_name'],
                'm' => 'upload/team/m_'.$file['store_name'],
                'b' => 'upload/team/'.$file['store_name']
            );

            $image = new Imagick(FCPATH.$avatar['b']);

            $height=$image->getImageHeight();
            $width=$image->getImageWidth();

            if ($width > 800 || $height > 800){
                if ($height < $width)
                    $image->scaleImage(800,0);
                else
                    $image->scaleImage(0,600);
            }

            $image->writeImage(FCPATH.$avatar['m']);
            $image->cropThumbnailImage(107, 107);
            $image->writeImage(FCPATH.$avatar['s']);

            unset($image);

            $data = array(
                'idea_id' => (int)$file['idea_id'],
                'user_id'=> $this->user->uid(),
                'first_name' => $file['first_name'],
                'last_name' => $file['last_name'],
                'role' => $file['role'],
                'avatar_s' => $avatar['s'],
                'avatar_m' => $avatar['m'],
                'avatar_b' => $avatar['b'],
                'add_date' => time()
            );

            $result['files'][] = $avatar['b'];

            $idea_team_id = $this->m_ideas->create_team($data);
            if ($idea_team_id)
                $team[] = $data;

        }
        if ($team) {
            $result['status'] = 'success';
            $result['html'] = $this->mysmarty->view('global/idea/team/index.tpl', array('team' => &$team), false, true);
        } else {
            $result['errors'][] = 'array team is empty';
        }
        return $this->json->parse($result);
    }

    public function saveComment()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        if (!$this->user->logged()) return $this->json->parse($result);
        $RAW = $this->input->post('item');
        if (!$RAW || !isset($RAW['object_type']) || !$RAW['object_type'] || !isset($RAW['object_id']) || !$RAW['object_id'] || !isset($RAW['idesc']) || !$RAW['idesc']) return $this->returner($result,'/blog/');

        $this->load->model('m_model');
        $object_type = (int)$RAW['object_type'];
        $object_id = (int)$RAW['object_id'];

        $objects_id2table = array(
            1 => 'posts'
        );

        if (!isset($objects_id2table[$object_type]) || !$objects_id2table[$object_type]) return $this->returner($result,'/blog/');

        $object = $this->m_model->getItem($object_id, $objects_id2table[$object_type]);
        if (!$object) return $this->returner($result,'/blog/');

        $data = array(
            'object_type' => $object_type,
            'object_id' => $object_id,
            'user_id' => $this->user->uid(),
            'user_full_name' => $this->user->first_name.' '.$this->user->last_name,
            'user_avatar' => ($this->user->avatar_m)?$this->user->avatar_m:'',
            'idesc' => strip_tags($RAW['idesc']),
            'add_date' => time()
        );
        $comment_id = $this->m_model->create($data, 'comments');
        $this->m_model->increase($objects_id2table[$object_type], $object_id, 'comments_count');

        if ($comment_id) {
            $result['status'] = 'success';
            $result['html'] = $this->mysmarty->view('std/comments/item_processor.tpl', array('comments' => array($data)), false, true);
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function saveJudge()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        $RAW = $this->input->post('item');
        $judge_id = (int)$this->input->post('id');
        $file = $this->input->post('file');
        if (!$RAW || !isset($RAW['first_name']) || !$RAW['first_name'] || !isset($RAW['last_name']) || !$RAW['last_name']) return $this->json->parse($result);

        $item = array(
            'first_name' => strip_tags($RAW['first_name']),
            'last_name' => strip_tags($RAW['last_name']),
            'company_url' => strip_tags($RAW['company_url']),
            'company_iname' => strip_tags($RAW['company_iname']),
            'role' => strip_tags($RAW['role']),
            'iname' => strip_tags($RAW['iname']),
            'idesc' => strip_tags($RAW['idesc'])
        );
        if (!$judge_id) $item['user_id'] = $this->user->uid();

        if ($RAW['link']) {
            $link = $RAW['link'];
            $link = str_replace('http://', '', $link);
            $link = str_replace('www.', '', $link);
            preg_match('/([^\/]+)/', $link, $matches);
            $hoster = $matches[1];
            $result['hoster']=$hoster;
            $matches = array();
            if($hoster == 'youtube.com' && preg_match('/(?:\?|\&)v\=([A-z0-9\-\_]+)/', $link, $matches)) {
                $item['youtube_img'] = 'http://i1.ytimg.com/vi/'.$matches[1].'/0.jpg';
                $item['youtube_code'] = $matches[1];
            } else {
                $result['errors']['link'] = 2;
            }
        }


        if ($file && isset($file['store_name']) && $file['store_name'] && isset($file['upload_path']) && $file['upload_path']) {
            $this->load->library('imagine');
            $avatar = $this->imagine->proccessPhoto($file);
            if ($avatar) {
                $item['avatar_b'] = $avatar['b'];
                $item['avatar_m'] = $avatar['m'];
                $item['avatar_s'] = $avatar['s'];
            }
        }

        $this->load->model('m_judges');
        if ($judge_id)
            $this->m_judges->update($judge_id, $item);
        else {
            $item['add_date'] = time();
            $judge_id = $this->m_judges->create($item);
        }
        if ($judge_id) {
            $result['status'] = 'success';
            $result['goto'] = base_url('/judges');
        }

        return $this->json->parse($result);
    }

    public function savePartner()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        $RAW = $this->input->post('item');
        $partner_id = (int)$this->input->post('id');
        $file = $this->input->post('file');
        if (!$RAW || !isset($RAW['iname']) || !$RAW['iname'] || !isset($RAW['idesc']) || !$RAW['idesc'])
            return $this->json->parse($result);


        $item = array(
            'url' => strip_tags($RAW['url']),
            'iname' => strip_tags($RAW['iname']),
            'idesc' => strip_tags($RAW['idesc'])
        );
        if (!$partner_id) $item['user_id'] = $this->user->uid();

        if ($RAW['link']) {
            $link = $RAW['link'];
            $link = str_replace('http://', '', $link);
            $link = str_replace('www.', '', $link);
            preg_match('/([^\/]+)/', $link, $matches);
            $hoster = $matches[1];
            $result['hoster']=$hoster;
            $matches = array();
            if($hoster == 'youtube.com' && preg_match('/(?:\?|\&)v\=([A-z0-9\-\_]+)/', $link, $matches)) {
                $item['youtube_img'] = 'http://i1.ytimg.com/vi/'.$matches[1].'/0.jpg';
                $item['youtube_code'] = $matches[1];
            } else {
                $result['errors']['link'] = 2;
            }
        }

        if ($file && isset($file['store_name']) && $file['store_name'] && isset($file['upload_path']) && $file['upload_path']) {
            $this->load->library('imagine');
            $avatar = $this->imagine->proccessPhoto($file);
            if ($avatar) {
                $item['avatar_b'] = $avatar['b'];
                $item['avatar_m'] = $avatar['m'];
                $item['avatar_s'] = $avatar['s'];
            }
        }

        $this->load->model('m_partners');
        if ($partner_id)
            $this->m_partners->update($partner_id, $item);
        else {
            $item['add_date'] = time();
            $partner_id = $this->m_partners->create($item);
        }
        if ($partner_id) {
            $result['status'] = 'success';
            $result['goto'] = base_url('/partners');
        }

        return $this->json->parse($result);
    }

    public function saveVideo()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        $RAW = $this->input->post('item');
        $video_id = (int)$this->input->post('id');
        if (!$RAW || !isset($RAW['iname']) || !$RAW['iname'] || !isset($RAW['idesc']) || !$RAW['idesc'])
            return $this->json->parse($result);

        $item = array(
            'iname' => strip_tags($RAW['iname']),
            'idesc' => strip_tags($RAW['idesc'])
        );
        if (!$video_id) $item['user_id'] = $this->user->uid();

        if ($RAW['link']) {
            $link = $RAW['link'];
            $link = str_replace('http://', '', $link);
            $link = str_replace('www.', '', $link);
            preg_match('/([^\/]+)/', $link, $matches);
            $hoster = $matches[1];
            $result['hoster']=$hoster;
            $matches = array();
            if($hoster == 'youtube.com' && preg_match('/(?:\?|\&)v\=([A-z0-9\-\_]+)/', $link, $matches)) {
                $item['youtube_img'] = 'http://i1.ytimg.com/vi/'.$matches[1].'/0.jpg';
                $item['youtube_code'] = $matches[1];
            } else {
                $result['errors']['link'] = 2;
            }
        }

        $this->load->model('m_videos');
        if ($video_id)
            $this->m_videos->update($video_id, $item);
        else {
            $item['add_date'] = time();
            $video_id = $this->m_videos->create($item);
        }
        if ($video_id) {
            $result['status'] = 'success';
            $result['goto'] = base_url('/videos');
        }

        return $this->json->parse($result);
    }

    public function saveProductPhoto()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        //if (!$this->user->logged()) return $this->json->parse($result);
        $RAW = $this->input->post('item');
        $photo_id = (int)$this->input->post('id');
        if (!$photo_id || !$RAW || !isset($RAW['iname']) || !$RAW['iname']) return $this->json->parse($result);

        $this->load->model('m_products');

        $data = array(
            'iname' => trim($RAW['iname']),
            'idesc' => trim($RAW['idesc']),
        );

        $photo_id = $this->m_products->save('products_photos', $photo_id, $data);

        if ($photo_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function saveNewsPhoto()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        //if (!$this->user->logged()) return $this->json->parse($result);
        $RAW = $this->input->post('item');
        $photo_id = (int)$this->input->post('id');
        if (!$photo_id || !$RAW || !isset($RAW['iname']) || !$RAW['iname']) return $this->json->parse($result);

        $this->load->model('m_products');

        $data = array(
            'iname' => trim($RAW['iname']),
            'idesc' => trim($RAW['idesc']),
        );

        $photo_id = $this->m_products->save('news_photos', $photo_id, $data);

        if ($photo_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }

    public function saveMedia()
    {
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);

        $data = $this->input->post('item');
        $file = $this->input->post('file');

        if (!$data || !isset($data['iname']) || !$data['iname']) return $this->returner($result,'/admin/media/');

        $photo = array();
        if ($file && isset($file['store_name']) && $file['store_name'] && isset($file['upload_path']) && $file['upload_path']) {
            $this->load->library('imagine');
            $photo = $this->imagine->proccessPhoto($file);
            if ($photo) {
                $nMedia['poster_s'] = $photo['s'];
                $nMedia['poster_m'] = $photo['m'];
                $nMedia['poster_b'] = $photo['b'];
            }
        }

        $mediaId = 0;
        if($data['type'] == 'video' && preg_match('/(?:\?|\&)v\=([A-z0-9\-\_]+)/', $data['link'], $matches)) {
            if (!$photo) {
                $nMedia['poster_b'] = 'http://i1.ytimg.com/vi/'.$matches[1].'/0.jpg';
                $nMedia['poster_m'] = 'http://i1.ytimg.com/vi/'.$matches[1].'/1.jpg';
            }
            $nMedia['code'] = $matches[1];
            $nMedia['iname'] = $data['iname'];
            $nMedia['idesc'] = $data['idesc'];
            $mediaId = $this->m_model->create($nMedia, 'video');
        } elseif($data['type'] == 'music' && $data['link']) {
            $nMedia['iname'] = $data['iname'];
            $nMedia['idesc'] = $data['idesc'];
            $nMedia['link'] = $data['link'];
            $mediaId = $this->m_model->create($nMedia, 'music');
        }

        if ($mediaId) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'media add error';
        }
        return $this->returner($result,'/admin/media/');
    }

    public function savePage(){
        $result = array('status' => 'error', 'errors' => array(), 'code' => 0);
        $RAW = $this->input->post('item');
        $page_id = (int)$this->input->post('id');
        if (!$RAW || !isset($RAW['idesc']) || !$RAW['idesc']) return $this->json->parse($result);

        $this->load->model('m_model');

        $idesc = trim($RAW['idesc']);
        $is_deleted = (int)$RAW['is_deleted'];
        $data = array(
            'idesc' => $idesc,
            'is_deleted' => $is_deleted
        );

        $goto = false;
        if (!$page_id) $goto = true;
        $page_id = $this->m_model->save('pages',$page_id, $data);
        if ($goto) $result['goto'] = '/admin/pages/';

        if ($page_id) {
            $result['status'] = 'success';
        } else {
            $result['errors'][] = 'comment add error';
        }
        return $this->json->parse($result);
    }
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */