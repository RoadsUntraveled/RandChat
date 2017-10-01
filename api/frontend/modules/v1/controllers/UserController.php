<?php
/**
 * Created by PhpStorm.
 * User: Jassy
 * Date: 2017/10/1
 * Time: 17:01
 */

namespace frontend\modules\v1\controllers;


use frontend\controllers\BaseController;
use yii\filters\auth\QueryParamAuth;


class UserController extends BaseController {
    public $modelClass = 'frontend\models\User';

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
//            'tokenParam' => 'token'  //例如改为‘token’
        ];
        return $behaviors;
    }

    /**
     * 直播申请
     */
    public function actionApplyLive(){
        $response['success'] = true;
        $response['message'] = "Live申请成功";
        $response['user'] = \Yii::$app->getUser();
        $this->send($response);
//        echo "Live申请成功";
    }

    public function actionApplyChatroom(){
        echo "Chatroom申请成功";
    }
}