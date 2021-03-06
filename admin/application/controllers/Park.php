<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Park extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('park_model');
        $this->load->model('facility_model');
        $this->load->model('recommend_model');
        $this->load->model('plot_model');
        $this->load->model('developer_model');
        $this->load->model('admin_model');
    }

    public function index()
    {
            $this->load->view('park_mgr');
    }

    public function park_mgr()
    {
        $draw = $this->input->get('draw');

        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = htmlspecialchars($this->input->get('search[value]'));//搜索内容
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)

        $order_col = array('1' => 'park_id', '3' => 'title', '4' => 'region', '5' => 'price');

        $recordsTotal = $this->park_model->get_total_count();
        $recordsFiltered = $this->park_model->get_filterd_count($search);

        $datas = $this->park_model->get_paginated_parks($length, $start, $search, $order_col[$order_col_no], $order_col_dir);

        foreach ($datas as $data) {
            $data->DT_RowData = array('id' => $data->park_id);
        }

        echo json_encode(array(
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $datas
        ), JSON_UNESCAPED_UNICODE);

    }

    public function park_detail()
    {
        $park_id = $this->input->get('parkId');
        $park = $this->park_model->get_park_by_id($park_id, array('inc_orders'=>FALSE, 'inc_comments'=>FALSE));
        $facilitys = $this->facility_model->get_facility_by_parkid($park_id);
        foreach ($park->plots_data as $plot){
            if($plot->plot_id == $park->plot_id){
                $plot->selected = 'selected';
            }
        }
        foreach ($park->developers_data as $developer){
            if($developer->developer_id == $park->developer_id){
                $developer->selected = 'selected';
            }
        }

        $facArr = [];
        foreach ($facilitys as $fac){
            $facArr[] = $fac->type_id;
        }
        foreach ($park->facilitys_data as $facility){
            if(in_array($facility->type_id,$facArr)){
                $facility->checked = 'checked';
            }else{
                $facility->checked = '';
            }
        }

        if($facilitys){
            $park->facilitys = $facilitys;
        }
        if ($park) {
            echo json_encode($park);
        } else {
            echo json_encode(array('err' => '未找到指定车位信息!'));
        }
    }

    public function park_add(){
        $title = htmlspecialchars($this->input->post('title'));
        $name = htmlspecialchars($this->input->post('name'));
        $hometown = htmlspecialchars($this->input->post('hometown'));
        $city = htmlspecialchars($this->input->post('city'));
        $position = htmlspecialchars($this->input->post('position'));
        $address = htmlspecialchars($this->input->post('address'));
        $bedroom = htmlspecialchars($this->input->post('bedroom'));
        $livingroom = htmlspecialchars($this->input->post('livingroom'));
        $toilet = htmlspecialchars($this->input->post('toilet'));
        $price = htmlspecialchars($this->input->post('price'));
        $park_msg = $this->input->post('park-msg');
        $notice = htmlspecialchars($this->input->post('notice'));
        $traffic = htmlspecialchars($this->input->post('traffic'));
        $isSale = htmlspecialchars($this->input->post('is-sale'));
        $salePrice = htmlspecialchars($this->input->post('sale-price'));
        $floor = htmlspecialchars($this->input->post('floor'));
        $totalFloors = htmlspecialchars($this->input->post('total-floors'));
        $developer_id = htmlspecialchars($this->input->post('developer_id'));
        $video = htmlspecialchars($this->input->post('video'));
        $manager = htmlspecialchars($this->input->post('manager'));
        $area = htmlspecialchars($this->input->post('area'));

        //中间需要处理车库设备的表
        $facilitys = $this->input->post('facility');
        $upload_imgs = $this->input->post('upload_img');

        $id = $this -> park_model -> save_park(array(
            "title" => $title,
            "plot_id" => $name,
            "province" => $hometown,
            "city" => $city,
            "region" => $position,
            "street" => $address,
            "bedroom" => $bedroom,
            "livingroom" => $livingroom,
            "lavatory" => $toilet,
            "price" => $price,
            "description" => $park_msg,
            "notice"=>$notice,
            "traffic"=>$traffic,
            "is_sale"=>$isSale[0],
            "sale_price"=>$salePrice,
            "floor"=>$floor,
            "total_floors"=>$totalFloors,
            "developer_id"=>$developer_id,
            "video"=>$video,
            "manager_id"=>$manager,
            'area'=>$area
        ));

        if($id){
            //中间需要处理车库设备的表
            $facilityArr = [];
            if(isset($facilitys)){
                foreach ($facilitys as $facility){
                    $facilityArr[] = array('type_id'=>$facility,'park_id'=>$id);
                }
                $this -> facility_model -> save_park_facility($facilityArr);
            }

            //添加照片
            $parkArr = [];
            if(isset($upload_imgs)){
                for ($i=0; $i<count($upload_imgs); $i++){
                    $upload_img = $upload_imgs[$i];
                    $upload_img_big = substr_replace($upload_img,"Big",22,5);
                    $parkArr[] = array('is_main'=>$i==0?1:0,'img_thumb_src'=>$upload_img,'img_src'=>$upload_img_big,'park_id'=>$id);
                }
                $this -> park_model -> save_park_img($parkArr);
            }
            redirect('park');
        }else{
            echo "添加车库失败";
        }

    }

    public function park_edit(){
        $park_id = htmlspecialchars($this->input->post('park_id'));
        $title = htmlspecialchars($this->input->post('title'));
        $plot = htmlspecialchars($this->input->post('plot'));
        $hometown = htmlspecialchars($this->input->post('hometown'));
        $city = htmlspecialchars($this->input->post('city'));
        $position = htmlspecialchars($this->input->post('position'));
        $address = htmlspecialchars($this->input->post('address'));
        $price = htmlspecialchars($this->input->post('price'));
        $park_msg = $this->input->post('park-msg');
        $notice = htmlspecialchars($this->input->post('notice'));
        $traffic = htmlspecialchars($this->input->post('traffic'));
        $isSale = $this->input->post('is-sale');
        $salePrice = htmlspecialchars($this->input->post('sale-price'));
        $developer_id = htmlspecialchars($this->input->post('developer_id'));
        $video = htmlspecialchars($this->input->post('video'));

        //新添加图片
        $upload_imgs = $this->input->post('upload_img');
        $del_img_id = $this->input->post('del_img_id');
        $facilitys = $this->input->post('facility');

        $row = $this -> park_model -> edit_park($park_id,array(
            "title" => $title,
            "plot_id" => $plot,
            "province" => $hometown,
            "city" => $city,
            "region" => $position,
            "street" => $address,
            "price" => $price,
            "description" => $park_msg,
            "notice"=>$notice,
            "traffic"=>$traffic,
            "is_sale"=>$isSale[0]==1?1:0,
            "sale_price"=>$salePrice,
            "developer_id"=>$developer_id,
            "video"=>$video
        ));


        //添加照片
        if(isset($upload_imgs)){
            $parkArr = [];
            foreach ($upload_imgs as $key=>$upload_img){
                $upload_img_big = substr_replace($upload_img,"Big",22,5);
                if($key==0){
                    $parkArr[] = array('img_thumb_src'=>$upload_img,'is_main'=>1,'img_src'=>$upload_img_big,'park_id'=>$park_id);
                }else{
                    $parkArr[] = array('img_thumb_src'=>$upload_img,'is_main'=>0,'img_src'=>$upload_img_big,'park_id'=>$park_id);
                }
            }
            $this -> park_model -> save_park_img($parkArr);
        }
        //删除图片
        if(isset($del_img_id)){
            $this -> park_model -> del_park_img($del_img_id);
        }

//        if(count($facilitys)){
//            $facilityArr = [];
//            foreach ($facilitys as $facility){
//                $facilityArr[] = array('type_id'=>$facility,'park_id'=>$park_id);
//            }
//            $this -> facility_model -> update_park_for_facility($park_id,$facilityArr);
//
//        }

        redirect('park');

    }

    public function facility_add(){
        $title = htmlspecialchars($this->input->get('title'));
        $facility_img = $this->input->get('facility_img');
        $facility_free = htmlspecialchars($this->input->get('free'));
        $facility_price = htmlspecialchars($this->input->get('price'));
        $facility_remark = htmlspecialchars($this->input->get('remark'));
        $row = $this -> facility_model -> insert_fac_from_park($title,$facility_img,$facility_free,$facility_price,$facility_remark);
        if($row){
            echo $row;
        }else{
            echo "error";
        }

    }

    public function img_upload_facility(){

        $typeArr = array("jpg", "png", "ico");
        //允许上传文件格式
        $path = "uploads/";
        //上传路径

        if (isset($_POST)) {
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $name_tmp = $_FILES['file']['tmp_name'];
            if (empty($name)) {
                echo json_encode(array("error" => "您还未选择图片"));
                exit;
            }
            $type = strtolower(substr(strrchr($name, '.'), 1));
            //获取文件类型

            if (!in_array($type, $typeArr)) {
                echo json_encode(array("error" => "请上传jpg,png,ico类型的图片！"));
                exit;
            }
            if ($size > (500 * 1024)) {
                echo json_encode(array("error" => "图片大小已超过500KB！"));
                exit;
            }
            $pic_name = time() . rand(10000, 99999) . "." . $type;
            //图片名称
            $pic_url = $path . $pic_name;


            //上传后图片路径+名称
            if (move_uploaded_file($name_tmp, $pic_url)) {//临时文件转移到目标文件夹
                echo json_encode(array("error" => "0", "pic" => $pic_url, "name" => $pic_name));

            } else {
                echo json_encode(array("error" => "上传有误，请稍后重试！"));
            }
        }
    }

    public function img_upload()
    {
        $typeArr = array("jpg", "png", "ico");
        //允许上传文件格式
        $path = "uploads/";//
        $pathSmall = "uploads/parkImg/parkSmall/";
        $pathBig = "uploads/parkImg/parkBig/";
        //上传路径

        //if (!file_exists($path)) {
        //  mkdir($path);
        //}
        if (isset($_POST)) {
            $name = $_FILES['file']['name'];
            $size = $_FILES['file']['size'];
            $name_tmp = $_FILES['file']['tmp_name'];
            if (empty($name)) {
                echo json_encode(array("error" => "您还未选择图片"));
                exit;
            }
            $type = strtolower(substr(strrchr($name, '.'), 1));
            //获取文件类型

            if (!in_array($type, $typeArr)) {
                echo json_encode(array("error" => "请上传jpg,png,ico类型的图片！"));
                exit;
            }
            if ($size > (500 * 1024)) {
                echo json_encode(array("error" => "图片大小已超过500KB！"));
                exit;
            }
            $pic_name = time() . rand(10000, 99999) . "." . $type;
            //图片名称
//            $pic_url = $path . $pic_name;
            $pic_url_small = $pathSmall.$pic_name;
            $pic_url_big = $pathBig. $pic_name;

            //上传后图片路径+名称
            if (move_uploaded_file($name_tmp, $pic_url_small) && copy($pic_url_small, $pic_url_big)) {//临时文件转移到目标文件夹
                echo json_encode(array("error" => "0", "pic" => $pic_url_small, "name" => $pic_name));

            } else {
                echo json_encode(array("error" => "上传有误，请稍后重试！"));
            }
        }
    }


    public function upload_img_del(){
        $id = $this->input->get('id');
        $parkImg = $this -> session -> userdata('parkImg');
        foreach ($parkImg as $key=>$img){
            if($img['id'] == $id){
                unset($parkImg[$key]);
            }
        }
        $this -> session -> set_userdata('parkImg',$parkImg);
        echo "success";
    }



    public function park_orders()
    {
        $park_id = $this->input->get('parkId');

        $draw = $this->input->get('draw');//jquery.datatables用到的数据，类似一个计数器，必须要用到

        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = $this->input->get('search[value]');//搜索内容
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)

        //定义前台datatables中要显示和排序的列出数据库中字段的关系
        $order_col = array('0' => 'order_id', '1' => 'start_time', '2' => 'end_time', '3' => 'price', '4' => 'order_status');

        $recordsTotal = $this->park_model->get_total_park_orders_count($park_id);//获取所有记录数，必须要用到
        $recordsFiltered = $this->park_model->get_filterd_park_orders_count($park_id, $search);//获取搜索过滤后的记录数，必须要用到

        //获取要分页的数据
        $datas = $this->park_model->get_paginated_park_orders($park_id, $length, $start, $search, $order_col[$order_col_no], $order_col_dir);

        foreach ($datas as $data) {
            $data->DT_RowData = array('id' => $data->order_id);//jquery.datatables插件要用DT_RowData属性来为每一个tr绑定自定义data-*属性
        }

        echo json_encode(array(//返回的数据，下面这几个参数draw、recordsTotal、recordsFiltered都是jquery.datatables要求必须要传的
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $datas
        ), JSON_UNESCAPED_UNICODE);

    }

    public function park_comments()
    {
        $park_id = $this->input->get('parkId');
        $comments = $this->park_model->get_comments_by_park_id($park_id);
        if ($comments) {
            echo json_encode($comments);
        } else {
            echo json_encode(array('err' => '未找到该车位的评论信息!'));
        }
    }

    public function park_del()
    {
        $park_id = $this->input->get('parkId');

        $row =  $this->park_model->delete_park($park_id);
        if($row > 0){
            echo 'success';
        }else{
            echo 'fail';
        }
    }

    public function park_list()
    {
        $ordered = $this->park_model->get_ordered_park();
        $unorder = $this->park_model->get_unorder_park();
        $all_plot = $this->park_model->get_plot_by_park();
        $arr['all_plot'] = $all_plot;
        $arr['ordered'] = $ordered;
        $arr['unorder'] = $unorder;

        $this->load->view('park_grids',$arr);
    }
    public function park_list_plot(){
        $plot = $this->input->get('plot');
        $ordered = $this->park_model->get_ordered_park_by_plot($plot);
        $unorder = $this->park_model->get_unorder_park_plot($plot);
        $arr['ordered'] = $ordered;
        $arr['unorder'] = $unorder;
        echo json_encode($arr);
    }
    //订单管理 车库选择
    public function order_search_park()
    {
        $street = $this->input->get("street");
        $result = $this->park_model->order_search_park($street);
        echo json_encode($result);
    }
    public function recommend_add(){
        $rec_id = $this->input->get('rec_id');
        $rec_reason = $this->input->get('rec_reason');
        $row = $this -> recommend_model -> insert_rec_from_park($rec_id,$rec_reason);
        if($row){
            echo $row;
        }else{
            echo "error";
        }

    }
    public function recommend_del(){
        $rec_id = $this->input->get('rec_id');
        $rec_reason = $this->input->get('rec_reason');
        $row = $this -> recommend_model -> del_rec_from_park($rec_id,$rec_reason);
        if($row){
            echo $row;
        }else{
            echo "error";
        }

    }

    public function recommend_add_some()
    {
        $rec_some = $this->input->get('rec_some');
        $rec_some_arr=explode(('.'),$rec_some);
//        echo json_encode($rec_some_arr);
        $rec_reason = $this->input->get('rec_reason');
        for($i=0;$i<count($rec_some_arr);$i++){
            $re=$this->recommend_model->insert_some_rec_from_park($rec_some_arr[$i],$rec_reason);
        }
        if($re){
            echo 1;
        }else{
            echo "error";
        }
    }

    public function get_plot()
    {
        $this->load->model('park_model');
        $rs_plot = $this->park_model->get_plot_by_park();
        $arr['rs_plot']=$rs_plot;
        if($rs_plot){
            echo json_encode($rs_plot);
        }
    }
    public function plot_park()
    {
        $plot_id=$this->input->get('plot_id');

        //分页
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = htmlspecialchars($this->input->get('search[value]'));//搜索内容
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)

        $order_col = array('1' => 'park_id', '3' => 'title', '4' => 'street', '5' => 'price');
//
        $recordsTotal = $this->park_model->get_total_plot_count($plot_id);
        $recordsFiltered = $this->park_model->get_plot_filterd_count($search,$plot_id);

        $datas = $this->park_model->get_paginated_plot_parks($length, $start, $search, $order_col[$order_col_no], $order_col_dir,$plot_id);

        foreach ($datas as $data) {
            $data->DT_RowData = array('id' => $data->park_id);
        }
        echo json_encode(array(
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $datas
        ), JSON_UNESCAPED_UNICODE);

    }

    public function get_del_park()
    {
        $start = $this->input->get('start');//从多少开始
        $length = $this->input->get('length');//数据长度
        $search = htmlspecialchars($this->input->get('search[value]'));//搜索内容
        $order_col_no = $this->input->get('order[0][column]');//排序的列
        $order_col_dir = $this->input->get('order[0][dir]');//排序的方向(asc|desc)

        $order_col = array('1' => 'park_id', '3' => 'title', '4' => 'street', '5' => 'price');

        $recordsFiltered = $this->park_model->get_del_filterd_count($search);
        $recordsTotal = $this->park_model->get_total_del_plot_count();
        $datas=$this->park_model->get_del_park($length, $start, $search, $order_col[$order_col_no], $order_col_dir);
        foreach ($datas as $data) {
            $data->DT_RowData = array('id' => $data->park_id);
        }
        echo json_encode(array(
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $datas
        ), JSON_UNESCAPED_UNICODE);
    }
    public function get_facility_plot(){
        $datas = $this->facility_model->get_facility();
        $plots = $this->park_model->get_plot_by_park();
        $developer = $this->developer_model->get_developer();
        $manager = $this-> admin_model->query_admin_manager();
        echo json_encode(array(
            "datas" => $datas,
            "plots" =>$plots,
            "developers" =>$developer,
            'managers'=>$manager
        ), JSON_UNESCAPED_UNICODE);
    }
    public function plot_add(){
        $title = $this->input->get('plot_name');
        $deve_id = $this->input->get('plot_deve');
        $plot_description = $this->input->get('description');
        $plot_video = $this->input->get('video');
        $row = $this -> plot_model -> insert_plot_from_park($title,$deve_id,$plot_description,$plot_video);
        if($row){
            echo $row;
        }else{
            echo "error";
        }
    }


    public function del_all(){
        $namearr=$this->input->get('name');
        admin_log('管理员删除车库');
        $result=$this->park_model->del_all_name($namearr);
        if($result){
            echo "success";
        }else{
            echo "error";
        }
    }
}
