<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

class ProfilePicModel extends DAO {
    private static $instance;

    public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    function __construct() {
        parent::__construct();
        $this->setTableName('t_user_profilepic');
        $this->setPrimaryKey('fk_i_user_id');
        $this->setFields(array('fk_i_user_id', 's_name', 'dt_date'));
    }

    public function getSQL($file) {
        $path = PROFILEPIC_PATH.'assets/model/'.$file;
        $sql = file_get_contents($path);

        return $sql;
    }

    public function install() {
        $sql = $this->getSQL('install.sql');
        if(!$this->dao->importSQL($sql)) {
            throw new Exception('Installation error: ProfilePicModel:'.$file);
        }
    }

    public function uninstall() {
        $sql = $this->getSQL('uninstall.sql');
        if(!$this->dao->importSQL($sql)) {
            throw new Exception('Uninstallation error: ProfilePicModel:'.$file);
        }
    }

    public function listWhere($where = null) {
        $this->dao->select('p.*, u.s_name as user_name');
        $this->dao->from($this->getTableName().' p');
        $this->dao->join(DB_TABLE_PREFIX.'t_user u', 'u.pk_i_id = p.fk_i_user_id', 'LEFT');
        if(!is_null($where)) {
            $this->dao->where($where);
        }
        $this->dao->orderBy('p.dt_date', 'DESC');

        $result = $this->dao->get();
        if($result === false) {
            return array();
        }

        $pics = $result->result();
        if(!is_null($pics)) {
            return $pics;
        } else {
            return array();
        }
    }

    public function countWhere($where = null) {
        $this->dao->select();
        $this->dao->from($this->getTableName());

        if(!is_null($where)) {
            $this->dao->where($where);
        }

        $result = $this->dao->get();
        if($result === false) {
            return array();
        }

        $result = $result->result();
        return $result;
    }
}
?>
