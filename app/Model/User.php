<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $primaryKey='id';
	protected $table = 'p_user';
	public $timestamps = false;
	protected $guarded = []; //黑名单
}
