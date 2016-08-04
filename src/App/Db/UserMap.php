<?php
namespace App\Db;

use Tk\Db\Tool;
use Tk\Db\Map\ArrayObject;
use Tk\DataMap\Db;
use Tk\DataMap\Form;

/**
 * Class UserMapper
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class UserMap extends Mapper
{
    /**
     *
     * @return \Tk\DataMap\DataMap
     */
    public function getDbMap()
    {
        if (!$this->dbMap) {
            $this->dbMap = new \Tk\DataMap\DataMap();
            $this->dbMap->addProperty(new Db\Number('id'), 'key');
            $this->dbMap->addProperty(new Db\Text('name'));
            $this->dbMap->addProperty(new Db\Text('email'));
            $this->dbMap->addProperty(new Db\Text('username'));
            $this->dbMap->addProperty(new Db\Text('password'));
            $this->dbMap->addProperty(new Db\Text('role'));
            $this->dbMap->addProperty(new Db\Boolean('active'));
            $this->dbMap->addProperty(new Db\Text('hash'));
            //$this->dbMap->addProperty(new Db\Date('lastLogin', 'last_login'));
            $this->dbMap->addProperty(new Db\Date('modified'));
            $this->dbMap->addProperty(new Db\Date('created'));

            $this->setPrimaryKey($this->dbMap->currentProperty('key')->getColumnName());
        }
        return $this->dbMap;
    }

    /**
     *
     * @return \Tk\DataMap\DataMap
     */
    public function getFormMap()
    {
        if (!$this->formMap) {
            $this->formMap = new \Tk\DataMap\DataMap();
            $this->formMap->addProperty(new Form\Number('id'), 'key');
            $this->formMap->addProperty(new Form\Text('name'));
            $this->formMap->addProperty(new Form\Text('email'));
            $this->formMap->addProperty(new Form\Text('username'));
            $this->formMap->addProperty(new Form\Text('password'));
            $this->formMap->addProperty(new Form\Text('role'));
            $this->formMap->addProperty(new Form\Boolean('active'));

            $this->setPrimaryKey($this->formMap->currentProperty('key')->getColumnName());
        }
        return $this->formMap;
    }
    
//    public function unmap($obj)
//    {
//        $arr = array(
//            'id' => $obj->id,
//            'name' => $obj->name,
//            'email' => $obj->email,
//            'username' => $obj->username,
//            'password' => $obj->password,
//            'role' => $obj->role,
//            'active' => (int)$obj->active,
//            'hash' => $obj->hash,
//            'modified' => $obj->modified->format('Y-m-d H:i:s'),
//            'created' => $obj->created->format('Y-m-d H:i:s')
//        );
//        return $arr;
//    }
//
//    public function map($row)
//    {
//        $obj = new User();
//        $obj->id = $row['id'];
//        $obj->name = $row['name'];
//        $obj->email = $row['email'];
//        $obj->username = $row['username'];
//        $obj->password = $row['password'];
//        $obj->role = $row['role'];
//        $obj->active = ($row['active'] == 1);
//        $obj->hash = $row['hash'];
//        if ($row['modified'])
//            $obj->modified = new \DateTime($row['modified']);
//        if ($row['created'])
//            $obj->created = new \DateTime($row['created']);
//        return $obj;
//    }
//
//    static function mapForm($row, $obj = null)
//    {
//        if (!$obj) {
//            $obj = new User();
//        }
//        //$obj->id = $row['id'];
//        if (isset($row['name']))
//            $obj->name = $row['name'];
//        if (isset($row['email']))
//            $obj->email = $row['email'];
//        if (isset($row['username']))
//            $obj->username = $row['username'];
//        if (isset($row['password']))
//            $obj->password = $row['password'];
//        if (isset($row['role']))
//            $obj->role = $row['role'];
//        if (isset($row['active']))
//            $obj->active = ($row['active'] == 'active');
//
//        // TODO: This has to be tested, should parse date string using config['system.date.format.php']
//        if (isset($row['modified']))
//            $obj->modified = new \DateTime($row['modified']);
//        if (isset($row['created']))
//            $obj->created = new \DateTime($row['created']);
//
//        return $obj;
//    }
//
//    static function unmapForm($obj)
//    {
//        $arr = array(
//            'id' => $obj->id,
//            'name' => $obj->name,
//            'email' => $obj->email,
//            'username' => $obj->username,
//            'password' => $obj->password,
//            'role' => $obj->role,
//            'active' => (int)$obj->active,
//            'modified' => $obj->modified->format('Y-m-d H:i:s'),
//            'created' => $obj->created->format('Y-m-d H:i:s')
//        );
//        return $arr;
//    }
    
    /**
     * 
     * 
     * @param $username
     * @return mixed
     */
    public function findByUsername($username)
    {
        $result = $this->select('username = ' . $this->getDb()->quote($username) );
        return $result->current();
    }


    public function findByEmail($email)
    {
        return $this->select('email = ' . $this->getDb()->quote($email))->current();
    }

    public function findByHash($hash)
    {
        return $this->select('hash = ' . $this->getDb()->quote($hash))->current();
    }



    /**
     * Find filtered records
     *
     * @param array $filter
     * @param Tool $tool
     * @return ArrayObject
     */
    public function findFiltered($filter = array(), $tool = null)
    {
        $from = sprintf('%s a ', $this->getDb()->quoteParameter($this->getTable()));
        $where = '';

        if (!empty($filter['keywords'])) {
            $kw = '%' . $this->getDb()->escapeString($filter['keywords']) . '%';
            $w = '';
            $w .= sprintf('a.name LIKE %s OR ', $this->getDb()->quote($kw));
            $w .= sprintf('a.username LIKE %s OR ', $this->getDb()->quote($kw));
            $w .= sprintf('a.email LIKE %s OR ', $this->getDb()->quote($kw));
            $w .= sprintf('a.role LIKE %s OR ', $this->getDb()->quote($kw));
            if (is_numeric($filter['keywords'])) {
                $id = (int)$filter['keywords'];
                $w .= sprintf('a.id = %d OR ', $id);
            }
            if ($w) {
                $where .= '(' . substr($w, 0, -3) . ') AND ';
            }
        }
        
//        if (!empty($filter['lti_context_id'])) {
//            $where .= sprintf('a.lti_context_id = %s AND ', $this->getDb()->quote($filter['lti_context_id']));
//        }


        if ($where) {
            $where = substr($where, 0, -4);
        }

        $res = $this->selectFrom($from, $where, $tool);
        return $res;
    }
}