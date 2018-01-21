<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    private static $displayNames = [
        'admin' => '后台',
        'api' => '接口',
        'index' => '列表',
        'create' => '创建',
        'store' => '存储',
        'edit' => '编辑',
        'show' => '详情',
        'destroy' => '删除',
        'update' => '更新',
        'topic' => '帖子',
        'comment' => '回复',
        'attachment' => '附件',
        'forum' => '论坛版块',
        'huisuo' => 'HS',
        'jishi' => 'JS',
        'role' => '角色',
        'permission' => '权限',
        'contact' => '联系方式',
        'password' => '密码',
        'user' => '用户',
        'image' => '图片',
        'upload' => '上传',
        'login' => '登录',
        'logout' => '退出',
        'email' => '邮箱',
        'reset' => '重置',
        'register' => '注册',
        'cnarea' => '行政区域',
        'province' => '省',
        'city' => '市',
        'district' => '区',
        'home' => '首页',
        'other' => '其他',
        'forumtaxonomy' => '论坛版块分类',
    ];

    public function listDisplayNames()
    {
        return self::$displayNames;
    }

}
