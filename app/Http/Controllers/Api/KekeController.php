<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Model\User;
use GuzzleHttp\Client;

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


    public function md5test()
    {
        $data = "Hai";      
        $key = "yangtao";              
        $signature = 'poiuytrfghjkkmn';
        echo "要发送的数据：". $data;echo '</br>';
        echo "签名：". $signature;echo '</br>';
        $url = "http://passport.1905.com/test/check?data=".$data . '&signature='.$signature;
        echo $url;echo '<hr>';
        $response = file_get_contents($url);
        echo $response;
    }



    public function postqm()
    {
        $key = "yangtao";          

        $order_info = [
            "order_id"          => 'LN_' . mt_rand(111111,999999),
            "order_amount"      => mt_rand(111,999),
            "uid"               => 12345,
            "add_time"          => time(),
        ];

        $data_json = json_encode($order_info); 

        //计算签名
        $sign = md5($data_json.$key);

        // post 表单（form-data）发送数据
        $client = new Client();
        $url = 'http://passport.1905.com/test/postqm2';
        $response = $client->request("POST",$url,[
            "form_params"   => [
                "data"  => $data_json,
                "sign"  => $sign
            ]
        ]);

        //接收服务器端响应的数据
        $response_data = $response->getBody();
        echo $response_data;

    }


    public function sign3()
    {
       $data= "keke"; //要签名的数据
       //计算签名
       $path=storage_path('keys/privkey3'); //私钥的路径
       $pkeyid=openssl_pkey_get_private("file://".$path);
       //得到$signature
       openssl_sign($data,$signature,$pkeyid);
       openssl_free_key($pkeyid);
       //base64编码 方便传输
       $sign_str=base64_encode($signature);
       echo "base64encode 以后的签名".$sign_str;
    }


    public function encrypt1()
    {
        $data=$_GET['data'];
        echo "加密前".$data;echo '</br>';
        $method='AES-256-CBC';
        $key='yangtao';
        $iv='WUSD8796IDjhkchd';
        $enc_data=openssl_encrypt($data, $method, $key,OPENSSL_RAW_DATA,$iv);
        echo "加密后".$enc_data;echo '</br>';
        echo '解密';echo '</br>';

        //解密
        $dec_data=openssl_decrypt($enc_data, $method, $key,OPENSSL_RAW_DATA,$iv);
        echo $dec_data;
    }


    public function decrypt1()
    {
        $data=$_GET['data'];
        $path=storage_path('keys/privkey3');
        $prive_key=openssl_pkey_get_private("file://".$path);
        openssl_private_encrypt($data, $enc_data,$prive_key,OPENSSL_PKCS1_PADDING);

        var_dump($enc_data);
        echo'<hr>';
        //发送密文
        $base64_encode_str=base64_encode($enc_data);
        $url='http://passport.1905.com/decrypt2?data='.urlencode($base64_encode_str);
        echo $url;
        file_get_contents($url); //发送请求

    }
}
