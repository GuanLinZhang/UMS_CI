<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

/***
 * Class User_controller 用户控制器 负责用户界面UI跳转,用户数据处理
 * author zgl
 */
class User_controller extends MY_controller {
    public function __construct() {
        //根据表名,初始化user_model
        parent::__construct("user");
        //加载helper
        $this->loadHelper(array("url_helper","date"));
        //加载依赖库
        $this->loadLibrary(array("form_validation",
                                 "JSONResult",
                                 "upload",));
    }

    /***
     * 获取全部用户
     */
    public function getAllUsers() {
        $userList = $this->getObjects();
        $this->sendJSONResponse(new JSONResult($userList));
    }

    /***
     * 模糊查找用户
     */
    public function getUsersByFuzzyName() {
        $userList = $this->getObjects(false,$this->getGETParam("username"));
        $this->sendJSONResponse(new JSONResult($userList));
    }

    /***
     * 根据id查询指定用户
     * @param $id int 唯一用户标识
     */
    public function getUserById($id) {
        $user = $this->getObjects($id);
        $this->sendJSONResponse(new JSONResult($user));
    }

    /***
     * 注册用户
     */
    public function register() {
        //获取请求参数,组成用户注册数据
        $userData = array(
            'username' => $this->getPOSTParam('username'),
            'password' => $this->getPOSTParam('password'),
            'birth' => $this->getPOSTParam('birth') ?? mdate('%Y-%m-%d %h:%m:%s',time()),
            'createdUser' => $this->getPOSTParam('createdUser') ?? 'Administrator',
            "intro" => $this->getPOSTParam('intro'),
            "telephone" => $this->getPOSTParam('telephone'),
        );
        $upload_config = array(
            'upload_path'      => 'upload/',
            'allowed_types'    => 'gif|jpg|png',
            'max_size'         => 100,
            'max_width'        => 1024,
            'max_height'       => 768,
            'encrypt_name'     => true,
        );
        $uploadStatus = $this->uploadFile("uploaderFile",$upload_config);
        $filePath =  $uploadStatus['db_path'];
        $userData['headimg'] = $filePath;
        $DMLResult = $this->manipulateObject($userData,"insert");

        $jsonResult = new JSONResult();
        if ($DMLResult !== true) {
            $jsonResult->setState(0);
            $jsonResult->setMessage($DMLResult);
        } else {
            $jsonResult->setMessage("file_path".$filePath);
        }
        $this->sendJSONResponse($jsonResult);
    }

    /***
     * 更新用户
     * @param $id int 用户id
     */
    public function update($id) {
        //获取请求参数,组成用户注册数据
        $userData = array(
            'id' => $id,
            'username' => $this->getPOSTParam('username'),
            'password' => $this->getPOSTParam('password'),
            'birth' => $this->getPOSTParam('birth') ?? mdate('%Y-%m-%d %h:%m:%s',time()),
            'createdUser' => $this->getPOSTParam('createdUser') ?? 'Administrator',
            "intro" => $this->getPOSTParam('intro'),
            "telephone" => $this->getPOSTParam('telephone'),
        );
        $upload_config = array(
            'upload_path'      => 'upload/',
            'allowed_types'    => 'gif|jpg|png',
            'max_size'         => 100,
            'max_width'        => 1024,
            'max_height'       => 768,
            'encrypt_name'     => true,
        );
        $uploadStatus = $this->uploadFile("uploaderFile",$upload_config);
        $filePath =  $uploadStatus['db_path'];
        if ($uploadStatus['result'] !== 0) {
            $userData['headimg'] = $filePath;
        }
        $DMLResult = $this->manipulateObject($userData,"alter");

        $jsonResult = new JSONResult();
        if ($DMLResult !== true) {
            $jsonResult->setState(0);
            $jsonResult->setMessage($DMLResult);
        } else {
            $jsonResult->setMessage("file_path".$filePath);
        }

        $this->sendJSONResponse($jsonResult);
    }

    /***
     * 逻辑删除用户(status = 0)
     */
    public function delete() {
        $userData = array(
            'id' => $this->getGETParam("id"),
            'status' => 0,
        );
        $db_msg = $this->manipulateObject($userData,"alter");
        $jsonResult = new JSONResult();
        if ($db_msg !== true) {
            $jsonResult->setState(0);
            $jsonResult->setMessage($db_msg);
        }
        $this->sendJSONResponse($jsonResult);
    }

    /***
     * 用户登录
     */
    public function login() {
        $userData = array("username" => $this->getRequestParam('username','post'),
                          "password" => $this->getRequestParam('password','post'));

        $rowCount = $this->user_model->get_user_by_username_password($userData)->num_rows();
        $state = 0;
        if ($rowCount) {
            $state = 1;
            $message = "success";
        } else {
            $message = "fail";
        }

        $this->sendJSONResponse(new JSONResult(null,$state,$message));
    }

    /***
     * 跳转到登录页面
     */
    public function doLoginUI() {
        $data["title"] = "登录";
        $this->load->view("templates/header",$data);
        $this->load->view("users/login",$data);
        $this->load->view("templates/footer",$data);
    }

    /***
     * 跳转到注册页面
     */
    public function doRegisterUI() {
        $data["title"] = "注册";
        $this->load->view("users/register",$data);
    }

    /***
     * 跳转到注册成功页面
     */
    public function doSuccessUI() {
        $this->load->view("users/success");
    }

    /***
     * 跳转到用户列表页
     */
    public function doListUI() {
        $data['title'] = "用户列表";
        $this->load->view("users/list",$data);
    }

    /***
     * 跳转到用户详情页,利用url传递id
     * @param $id int 用户id
     */
    public function doDetailUI($id) {
        $data['id'] = $id;
        $this->load->view("users/detail",$data);
    }

    /***
     * 跳转到用户更新页,利用url传递id
     * @param $id int 用户id
     */
    public function doUpdateUI($id) {
        $data['id'] = $id;
        $this->load->view("users/update",$data);
    }

}