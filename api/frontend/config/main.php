<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'v1' => [
            'class' => 'frontend\modules\v1\Module',
            // ... 模块其他配置 ...
        ],
    ],
    'components' => [
        //请求
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        //返回定制?错误返回？
//        'response' => [
//            'class' => 'yii\web\Response',
//            'on beforeSend' => function ($event) {
//                $response = $event->sender;
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//                if ($response->data !== null) {
//                    $response->data = [
//                        "charset" => $response->charset,
//                        "statusText" => $response->statusText,
//                        "version" => $response->version,
//                        'success' => $response->isSuccessful,
//                        //状态
//                        'status' => isset($response->data['status'])?$response->data['status']:200,
//                        //数据
//                        'data' => $response->data,
//                    ];
//                    $response->statusCode = 200;
//                }
//            },
//        ],
        //验证
        'user' => [
            'identityClass' => 'frontend\models\Identity',
            'enableAutoLogin' => true,
            'enableSession' => false,
        ],
        //session配置
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        //日志
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        //错误处理
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        //url美化
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
    ],
    'params' => $params,
];
