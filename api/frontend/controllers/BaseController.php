<?php
/**
 * Created by PhpStorm.
 * User: Jassy
 * Date: 2017/10/1
 * Time: 13:49
 */

namespace frontend\controllers;


use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;

class BaseController extends \yii\rest\Controller {

//    public function behaviors() {
//        return ArrayHelper::merge (parent::behaviors(), [
//            'authenticator' => [
//                'class' => QueryParamAuth::className()
//            ],
//        ] );
//    }

    public function send($response){
        //莫名其妙，之前未设定的时候就可以根据请求自动返回相应数据，现在却提醒必须不能为数组？？？
        //出现这种情况是因为在重写behaviors的时候没有写上父行为，
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        foreach ($response as $key=>$value){
            Yii::$app->response->data[$key] = $value;
        }
    }

    public function getField(Model $model) {
        $field = [];
        $className = $model::className();
        $className = substr($className,strripos($className,'\\')+1);
        foreach ($model->attributes() as $attribute){
            $field[] = $className."[$attribute]";
        }
        return $field;
    }
}