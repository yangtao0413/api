<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $primaryKey='id';
	protected $table = 'p_user';
	public $timestamps = false;
	protected $guarded = []; //黑名单


	 public static function curlPost($url,$data)
	 {
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);


        $response=curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
