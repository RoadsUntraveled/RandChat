<?php

namespace frontend\modules\v1\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\models\SignupForm;
use frontend\models\LoginForm;
use frontend\models\ResetPasswordForm;
use frontend\models\PasswordResetRequestForm;
use frontend\controllers\BaseController;

/**
 * Class UserController
 * 暂不考虑跨域问题，这将在v2中实现！POST方法提交访问令牌
 * 用户按需求分为普通用户和管理员
 * 普通用户有以下角色：一般、主播、直播管理员、聊天室室长、聊天室管理员
 * 用户接口，提供：
 * 登录           v1/user/signin
 * 注册           v1/user/signup
 *
 * 密码修改        v1/user/modify-password/
 * 密码重置申请    v1/user/request-password-reset
 * 密码重置        v1/user/reset-password/token
 * 信息维护         v1/user/modify-info
 * 主播申请         v1/user/request-live
 * 聊天室申请        v1/user/request-chatroom
 *
 * 除注册和登录以外，其他接口皆需要提供访问令牌
 * @package frontend\modules\v1\controllers
 */
class SiteController extends BaseController {
    public $modelClass = 'frontend\models\User';

    /**
     * @inheritdoc
     */
//    public function behaviors() {
////        return [];
//        return ArrayHelper::merge (parent::behaviors(), [
//            'authenticator' => [
//                'class' => QueryParamAuth::className(),
//                'optional' => [
//                    'signin',
//                    'signup',
//                    'request-password-reset',
//                    'reset-password',
//                ],
//            ],
//        ] );
//    }

    public function actionIndex() {

//        return Json::encode(['c']);
        Yii::$app->response->data['c'] = 'c';
        //return ['c'];
//        return json_encode(['c']);
        //return $this->render('index');
    }

    public function actionSignin() {
        $response = [];
        $model = new LoginForm();
        if ($response['success'] = $model->load(Yii::$app->request->post())) {//POST登录
            $access_token = $model->login();
            $response['message'] = "登录成功";
            $response['access_token'] = $access_token;
        } elseif ($response['success'] =Yii::$app->request->isGet) {//GET请求
            $response['field'] = $this->getField($model);
        } else {
            $response['message'] = "登录失败";
        }

        //当调试完成后可直接return返回response
        $this->send($response);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionSignout() {
        $this->send(['success' => false]);
    }


    /**
     * 用户注册，自动生成访问令牌，并返回给用户
     * @return array
     */
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    $response['success'] = true;
                }
            }
        } elseif($response['success'] = Yii::$app->request->isGet) {
            $response['field'] = $this->getField($model);
        }else{

        }

        return [];
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $response = [];
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($response['success'] = $model->sendEmail()) {
                $response['message'] = "邮件发送成功，请注意查收！";
            } else {
                //Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
                $response['message'] = "邮件发送失败，请重新尝试或联系管理员！" . Yii::$app->params['adminEmail'];
            }
        } else {
            //此处验证码？？？？还是限流
            //设计一个ip表，然后进行ip限制访问
            $response['code'] = 'image';
        }

        $this->send($response);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        $response = [];
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($response['success'] = ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword())) {
            //Yii::$app->session->setFlash('success', 'New password saved.');
            $response['message'] = "重置成功";
        } else {
            $response['message'] = "重置失败，密码位数低于6位或未知原因";
        }

        $this->send($response);
    }

}
