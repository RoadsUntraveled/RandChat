<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model {
    public $username;
    public $password;
//    public $rememberMe = true;

    private $_user;
    private $_accessToken;


    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
//            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }


    /**
     * 密码验证，$this->validate()下的验证会检查rules下的规则，而rules下password指定了该方法进行验证
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                //考虑是否在这里进行token发送
//                yii::$app->response->data['message'] = "密码错误";
                $this->addError($attribute, 'Incorrect username or password.');
                //$this->
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return $this->getAccessToken();
            //return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * @return null|static
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = Identity::findByUsername($this->username);
        }
//        return ($this->_user === null) ? ($this->_user = Identity::findByUsername($this->username)) : $this->_user;
        return $this->_user;
    }

    protected function getAccessToken() {
        //理论上此时user已被创建
        $user = $this->getUser();
        $user->generateAccessToken();
        if($user->save()){
            return $user->access_token;
        }else{
            Yii::$app->response->data['message_service'] = "access_token保存失败，未知原因";
            return false;
        }
        //return ($this->getUser())->generateAccessToken();
        //return ($this->_accessToken === null)? Identity::gene;
    }
}
