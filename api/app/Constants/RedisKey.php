<?php

namespace App\Constants;

class RedisKey
{
    /**
     * 资源数据缓存
     * 存储结构：Set
     * 获取方式：hGet('user:resourceName', $name);
     * 参数说明：
     *      1.$name: 路由名称
     * 示例：hGet('user:resourceName', 'roles.index');
     */
    const USER_RESOURCE_NAME = 'user:resourceName';


    /**
     * 用户中心Token解析公钥
     *
     * 存储结构：string
     */
    const USER_CENTER_PUBLIC_KEY = 'user_center:public_key';


    /**
     * 邮件发送锁
     *
     * 存储结构：string
     */
    const LOCK_MAIL_SEND_KEY = 'lock:mail_send:%s:%d';

    /**
     * 用户添加锁
     *
     * 存储结构：string
     */
    const LOCK_USER_SAVE_KEY = 'lock:user_save:%s';


    /**
     * 下载中心下载任务
     */
    const LOCK_DOWNLOAD_CENTER = 'lock:download_center:%s';


    /**
     * 用户修改邮箱的验证码
     */
    const USER_MODIFY_MAIL_CODE = 'user:modify_mail_code:%d';

    /**
     * 角色缓存
     */
    const ROLES = 'roles:cache';
}