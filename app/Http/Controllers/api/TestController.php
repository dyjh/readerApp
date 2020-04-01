<?php


namespace App\Http\Controllers\api;




use App\User;
use RongCloud\RongCloud;

class TestController
{
    public function index()
    {
        /*$data['name'] = '11123';
        $data['email'] = '123456';
        $data['password'] = '123456';
        $result = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);*/

        /*$RongSDK = new RongCloud(config('latrell-rcloud.app_key'), config('latrell-rcloud.app_secret'));
        $user = [
            'id'=> 'ujadk90had',
            'name'=> 'Maritn',//用户名称
            'portrait'=> 'http://7xogjk.com1.z0.glb.clouddn.com/IuDkFprSQ1493563384017406982' //用户头像
        ];
        $register = $RongSDK->getUser()->register($user);
        dd($register);*/
        $RongSDK = new RongCloud(config('latrell-rcloud.app_key'), config('latrell-rcloud.app_secret'));
        $chatRoom = [
            'id' => '123',
            'name' => 'sss1'
        ];
        $result = $RongSDK->getChatroom()->create([$chatRoom]);
        $RongSDK->getChatroom()->Keepalive()->add(['id' => '123']);
        dd($result);
    }
}