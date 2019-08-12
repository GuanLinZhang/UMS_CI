<?php


class User_model extends CI_Model {
    private $tableName = "user";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * @return string
     */
    public function getTableName(): string {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName): void {
        $this->tableName = $tableName;
    }

    /***
     * @param bool $id
     * @param bool $fuzzyName
     * @return mixed 返回指定id的用户或用户集
     */
    public function get_user($id = false,$fuzzyName = false) {
        $resultArr = null;
        if ($id === false) {
            if ($fuzzyName === false) {
                $resultArr = $this->db->get_where(
                                        $this->getTableName(),
                                        array('status' => 1))
                                        ->result_array();
            } else {
                $resultArr = $this->db->like('username',$fuzzyName)
                                        ->get_where(
                                        $this->getTableName(),
                                        array('status' => 1))
                                        ->result_array();
            }
        } else {
            $resultArr = $this->db->get_where(
                                    $this->getTableName(),
                                    array('id' => $id,'status' => 1))
                                    ->result_array();
        }
        return $resultArr;
    }

    /***
     * @param $userData mixed 封装的用户数据
     * @return mixed 返回是否存在此用户记录
     */
    public function get_user_by_username_password($userData) {
        return $this->db->get_where($this->getTableName(),
                             array('username' => $userData["username"],
                                   'password' => $userData["password"]));
    }

    /***
     * 保存用户
     * @param $user object 待序列化实体
     * @return bool / string 如插入失败,返回错误信息,反之 返回true
     */
    public function insert_user($user) {
        try {
            $this->db->insert($this->getTableName(), $user);

            $db_error = $this->db->error();
            if ($db_error['code'] !== 0) {
                throw new Exception('Database error! 
                                              Error Code [' . $db_error['code'] . '] 
                                              Error: ' . $db_error['message']);
            }
            return true;
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $db_error['message'];
        }
    }

    /***
     * 更新用户信息
     * @param $user object 待更新用户数据集
     * @return bool / string 如删除失败,返回错误信息,反之 返回true
     */
    public function alter_user($user) {
        try {
            $userId = $user['id'];
            if (!$userId) return false;
            $this->db->update($this->getTableName(),$user,array('id' => $userId));

            $db_error = $this->db->error();
            if ($db_error['code'] !== 0) {
                throw new Exception('Database error! 
                                              Error Code [' . $db_error['code'] . '] 
                                              Error: ' . $db_error['message']);
            }
            log_message('info', $this->db->last_query());
            return true;
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return $db_error['message'];
        }
    }


}