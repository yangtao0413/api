<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

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

    	// echo '<pre>';print_r($request->input());echo '</pre>';

    	
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
    public function loginn(Request $request)
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
     * 获取用户列
     * 2020年1月2日16:32:07
     */
    public function userList()
    {
        $list = UserModel::all();
        echo '<pre>';print_r($list->toArray());echo '</pre>';
    }

    public function reg()
    {
         //请求passport
         $url='http://passport.1905.com/reg';
         $response=User::curlPost($url,$_POST);
         echo '<pre>';print_r($response);echo'</pre>';die;
         return $response;
    }

    public function login()
    {
        //请求passport
         $url='http://passport.1905.com/login';
         $response=User::curlPost($url,$_POST);
         echo '<pre>';print_r($response);echo'</pre>';die;
         return $response;
    }

    public function showData()
    {
        // 收到 token
        $uid = $_SERVER['HTTP_UID'];
        $token = $_SERVER['HTTP_TOKEN'];


        // 请求passport鉴权
        $url = 'http://passport.1905.com/auth';         //鉴权接口
        $response = User::curlPost($url,['uid'=>$uid,'token'=>$token]);


        $status = json_decode($response,true);


        //处理鉴权结果
        if($status['errno']==0)     //鉴权通过
        {
            $data = "sdlfkjsldfkjsdlf";
            $response = [
                'errno' => 0,
                'msg'   => 'ok',
                'data'  => $data
            ];
        }else{          //鉴权失败
            $response = [
                'errno' => 40003,
                'msg'   => '授权失败'
            ];
        }


        return $response;

    }


    public function postman1()
    {

        echo json_encode($data);

        
        // //获取用户标识
        // $token =$_SERVER['HTTP_TOKEN'];
        // //当前url
        // $request_uri=$_SERVER['REQUEST_URI'];
        // $url_hash=md5($token.$request_uri);

        // //echo'url_hash:'.$url_hash;echo '</br>';
        // $key='count:url'.$url_hash;
        // //echo 'Key:'.$key;echo '</br>';

        // //检查  次数是否已经超过限制
        // $count=Redis::get($key);
        // echo "当前接口访问次数为:".$count;echo '</br>';

        // if($count >= 3){
        //     $time=5;   //时间秒
        //     echo "请勿频繁请求接口,$time 秒后重试";
        //     Redis::expire($key,$time);
        //     die;
        // }
        // //访问数+1
        // $count=Redis::incr($key);
        // echo 'count:'.$count;
    }
}
