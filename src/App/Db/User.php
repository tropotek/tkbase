<?php
namespace App\Db;

use App\Auth\Acl;
use Tk\Db\Map\Model;

/**
 * Class User
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class User extends Model implements \Tk\ValidInterface
{
    
    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var string
     */
    public $email = '';

    /**
     * @var string
     */
    public $username = '';

    /**
     * @var string
     */
    public $password = '';

    /**
     * @var string
     */
    public $role = '';

    /**
     * @var string
     */
    public $notes = '';

    /**
     * @var \DateTime
     */
    public $lastLogin = null;

    /**
     * @var bool
     */
    public $active = true;

    /**
     * @var string
     */
    public $hash = '';

    /**
     * @var \DateTime
     */
    public $modified = null;

    /**
     * @var \DateTime
     */
    public $created = null;


    /**
     * User constructor.
     * 
     */
    public function __construct()
    {
        $this->modified = new \DateTime();
        $this->created = new \DateTime();
    }

    /**
     *
     */
    public function save()
    {
        if (!$this->hash) {
            $this->hash = $this->generateHash();
        }
        parent::save();
    }

    /**
     * Return the users home|dashboard relative url
     *
     * @return string
     * @throws \Exception
     */
    public function getHomeUrl()
    {
        $access = Acl::create($this);
        if ($access->hasRole(Acl::ROLE_ADMIN))
            return '/admin/index.html';
        if ($access->hasRole(Acl::ROLE_USER))
            return '/user/index.html';
        return '/index.html';
    }
    
    /**
     * Create a random password
     *
     * @param int $length
     * @return string
     */
    public static function createPassword($length = 8)
    {
        $chars = '234567890abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
        $i = 0;
        $password = '';
        while ($i <= $length) {
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
            $i++;
        }
        return $password;
    }

    /**
     * Helper method to generate user hash
     * 
     * @param bool $isTemp Set this to true, when generate a temporary hash used for registration
     * @return string
     */
    public function generateHash($isTemp = false) 
    {
        $key = sprintf('%s:%s:%s', $this->getVolatileId(), $this->username, $this->email); 
        if ($isTemp) {
            $key .= date('YmdHis');
        }
        return hash('md5', $key);
    }


    /**
     * Validate this object's current state and return an array
     * with error messages. This will be useful for validating
     * objects for use within forms.
     *
     * @return array
     */
    public function validate()
    {
        $errors = array();

        if (!$this->name) {
            $errors['name'] = 'Invalid field name value';
        }
        if (!$this->role) {
            $errors['role'] = 'Invalid field role value';
        }
        if (!$this->username) {
            $errors['username'] = 'Invalid field username value';
        } else {
            $dup = UserMap::create()->findByUsername($this->username);
            if ($dup && $dup->getId() != $this->getId()) {
                $errors['username'] = 'This username is already in use';
            }
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        } else {
            $dup = UserMap::create()->findByEmail($this->email);
            if ($dup && $dup->getId() != $this->getId()) {
                $errors['email'] = 'This email is already in use';
            }
        }
        return $errors;
    }
}

//class UserValidator extends \App\Helper\Validator
//{
//
//    /**
//     * Implement the validating rules to apply.
//     *
//     */
//    protected function validate()
//    {
//        /** @var User $obj */
//        $obj = $this->getObject();
//
//        if (!$obj->name) {
//            $this->addError('name', 'Invalid field value.');
//        }
//        if (!$obj->username) {
//            $this->addError('username', 'Invalid field value.');
//        } else {
//            $dup = User::getMapper()->findByUsername($obj->username);
//            if ($dup && $dup->getId() != $obj->getId()) {
//                $this->addError('username', 'This username is already in use.');
//            }
//        }
//        if (!$obj->role) {
//            $this->addError('role', 'The user must have a role assigned for the permission system');
//        }
//
//        if (!filter_var($obj->email, FILTER_VALIDATE_EMAIL)) {
//            $this->addError('email', 'Please enter a valid email address');
//        } else {
//            $dup = User::getMapper()->findByEmail($obj->email);
//            if ($dup && $dup->getId() != $obj->getId()) {
//                $this->addError('email', 'This email is already in use.');
//            }
//        }
//
//    }
//}