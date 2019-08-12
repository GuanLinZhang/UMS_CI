<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

/***
 * Class MY_controller
 * author zgl
 */
class MY_controller extends CI_Controller {
    //初始化表名
    private $tableName;

    public function __construct($tableName) {
        parent::__construct();
        $this->tableName = $tableName;
        $this->loadModel($tableName . "_model");
    }

    /**
     * @return mixed
     */
    public function getTableName() {
        return $this->tableName;
    }

    /**
     * @param mixed $tableName
     */
    public function setTableName($tableName): void {
        $this->tableName = $tableName;
    }

    /***
     * 封装加载模型方法
     * @param $model mixed 加载model字符串
     */
    public function loadModel($model) {
        $this->load->model($model);
    }

    /***
     * 封装加载库方法
     * @param $lib mixed 加载library字符串
     */
    public function loadLibrary($lib) {
        $this->load->library($lib);
    }

    /***
     * 封装加载帮助类方法
     * @param $helper mixed 加载helper字符串
     */
    public function loadHelper($helper) {
        $this->load->helper($helper);
    }

    /***
     * @param bool $id
     * @param bool $name
     * @return mixed 查询结果集
     */
    public function getObjects($id = false,$name = false) {
        $model = $this->getModel();
        $model_method = $this->getModelMethod("get");
        if ($id === false) {
            if ($name === false) {
                return $this->{$model}->{$model_method}(false,false);
            } else {
                return $this->{$model}->{$model_method}(false,$name);
            }
        } else {
            return $this->{$model}->{$model_method}($id,false);
        }
    }

    /***
     * @param $object
     * @return bool / int 如保存成功,则返回object id,否则返回false
     */
    public function createObject($object) {
        $model = $this->getModel();
        $model_method = $this->getModelMethod("insert");
        return $this->{$model}->{$model_method}($object);
    }

    /***
     *  向客户端发送JSON数据响应
     * @param JSONResult $obj 待发送到客户端的JSON数据
     */
    public function sendJSONResponse(JSONResult $obj) {
        $this->output->set_content_type('application/json')
                     ->set_output($obj);
    }

    /***
     * @param $object
     * @return bool / string 如删除失败,返回错误信息,反之 返回true
     */
    public function deleteObject($object) {
        $model = $this->getTableName() . "_model";
        $model_method = "delete_".$this->getTableName();
        return $this->{$model}->{$model_method}($object);
    }

    /***
     * 封装常用DML方法
     * @param $object mixed 待操作对象
     * @param $method string insert,alter,delete
     * @return bool / int 如保存成功,则返回object id,否则返回false
     */
    public function manipulateObject($object,$method) {
        $model = $this->getModel();
        $model_method = $this->getModelMethod($method);
        return $this->{$model}->{$model_method}($object);
    }

    /***
     * 根据传入方法拼接model_method字符串
     * @param $method string DML方法 insert alter delete
     * @return string 返回拼接字符串
     */
    public function getModelMethod($method) {
        return "{$method}_" .$this->getTableName();
    }

    /***
     * @return string 根据表名返回关于此表的模型字符串
     */
    public function getModel() {
        return $this->getTableName()."_model";
    }

    /***
     * 获取请求参数值
     * @param $param string 请求参数键名
     * @param $method string HTTP方法 e.g: GET POST PUT DELETE
     * @return mixed 返回请求参数值
     */
    public function getRequestParam($param, $method) {
        return $this->input->{$method}($param);
    }

    /***
     * 获取POST请求参数值
     * @param $param string 请求参数键名
     * @return mixed 返回请求参数值
     */
    public function getPOSTParam($param) {
        return $this->input->post($param);
    }

    /***
     * 获取GET请求参数值
     * @param $paramKey string 请求参数键名
     * @return mixed 返回请求参数值
     */
    public function getGETParam($paramKey) {
        return $this->input->get($paramKey);
    }

    /***
     * 上传文件
     * 将文件根据config配置上传到服务器指定路径中,并将成功保存后的路径作为数据库字段
     * 建议将文件名随机生成后,再进行保存操作
     * @param $originFileName string input标签的name值 或者 formData的键 作为文件名
     * @param $config mixed 文件上传配置,具体参考CI 文件上传类
     * @return array 上传文件信息
     *                      err_msg(string): 上传结果信息 如失败,返回具体报错信息, 反之 返回success
     *                      db_path(string): 文件保存路径 如成功,返回文件保存相对路径,反之 返回null
     *                      result(bool):  文件是否上传成功
     */
    public function uploadFile(string $originFileName, $config) {
        //加载ci文件上传库
        $this->load->library('upload', $config);
        //初始化返回状态结果
        $err_msg = null;
        //需保存到数据库中的文件路径
        $db_path = null;
        //根据config初始化文件上传类
        $this->upload->initialize($config);
        //上传文件结果
        $is_upload_success = $this->upload->do_upload($originFileName);
        //若上传失败获取错误信息
        if (!$is_upload_success) {
            $err_msg = $this->upload->display_errors();
            $is_upload_success = 0;
        } else {
            //获得已上传文件完整属性
            $fileInfo = $this->upload->data();
            $file_name = $fileInfo['file_name'];
            //获得已上传文件根路径
            $upload_path = $config['upload_path'];
            //拼接新生成加密文件名和根路径 生成保存到数据库中文件路径
            $db_path = $upload_path.$file_name;
            $err_msg = "success";
        }
        return array(
            'err_msg' => $err_msg,
            'db_path' => $db_path,
            'result'  => $is_upload_success
        );
    }

}