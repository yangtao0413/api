<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Model\User;

class KekeController extends Controller
{

    /**
     * 注册接口
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function regist(Request $request)
    {
    	echo '<pre>';print_r($request->input());echo '</pre>';

    	
    	$pass1=$request->input('pass1');
    	$pass2=$request->input('pass2');

    	if($pass1 != $pass2){
    		die("两次输入的密码不一致");
    	}

    	$password = password_hash($pass1,PASSWORD_BCRYPT);

    	$data=[
    		'email'=> $request->input('email'),
    		'username'=> $request->input('username'),
    		'password'=> $password,
            'mobile'=>$request->input('mobile'),
    		'last_login'=> time(),
    		'last_ip'=> $_SERVER['REMOTE_ADDR'],    //获取远程ip
    	];

        $uid = User::insertGetId($data);
        var_dump($uid);

    }

/**
 * 登录接口
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function login(Request $request)
    {
        $username=$request->input('username');
        $pass=$request->input('pass');

        //echo "pass: ".$pass;echo '</br>';

        $u=User::where(['username'=>$username])->first();
        if($u){
            //echo '<pre>';print_r($u->toArray());echo '<pre>';

            //验证密码
            if(password_verify($pass,$u->password)){
                //echo '登录成功';
                //生成token
                $token=Str::random(32);
                $response=[
                    'error'=>200,
                    'msg'=>'ok',
                    'data'=>[
                        'token'=>$token
                    ]
                ];
            }else{
                $response=[
                    'error'=>10001,
                    'msg' =>'密码不正确'
                ];
            }
        }else{
            $response=[
                'error'=>10002,
                'msg'=>'无此用户'
            ];
        }

        return $response;
    }

    /**
     * 获取用户列表
     * 2020年1月2日16:32:07
     */
    public function userList()
    {
        $list = UserModel::all();
        echo '<pre>';print_r($list->toArray());echo '</pre>';
    }
}
