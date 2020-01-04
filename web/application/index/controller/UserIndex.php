<?php
namespace app\index\controller;
use app\index\model\User;
class UserIndex
{
    public function index()
    {
        $user = User::all();
        var_dump($user);
        return 'ffff';
    }
}
