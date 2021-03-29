<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class User extends \App\Model\Front\User
{

    public static $listField = [
        'studentid' => '学号',
        'statusText' => '状态'
    ];

    public static $searchField = [
        'studentid' => '学号'
    ];

    public function comments()
    {
        return $this->hasMany('App\Model\Admin\Comment', 'user_id');
    }
}
