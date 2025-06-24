<?php

namespace App\Constants;


class ErrorCode
{
    /**
     * @Message("Server Error！")
     */
    public const SERVER_ERROR = 500;

    /**
     * @Message("params is error")
     */
    public const PARAMS_ERROR = 400;

    /**
     * @Message("No permission operation")
     */
    public const NOT_PERMISSION = 403;


    /**
     * 自定义错误码
     *
     * 规则：模块代码（2位） 功能代码（2位）具体错误码（4位）
     * 总长度8位：例如10010001，10 01 0001（10:模块代码（10-99），01:功能代码(01-99)，0001:具体错误码（0001-9999））
     */

    /**
     * @Message("request data does not exist")
     */
    public const CUSTOM_ERROR_DATA_EXISTS         = 10100000;
    public const CUSTOM_ERROR_DATA_NOT_EXISTS     = 10100001;
    public const CUSTOM_ERROR_DATA_NOT_DELETE     = 10100002;
    public const CUSTOM_ERROR_NAME_ALREADY_EXISTS = 10010003;
    public const CUSTOM_ERROR_MAIL_SEND_FAIL      = 10010004;
    public const CUSTOM_ERROR_USER_LOGIN_FAIL     = 10010005;
    public const CUSTOM_ERROR_DATA_LOCKING        = 10010006;
    public const CUSTOM_ERROR_FILE_UPLOAD         = 10010007;

    // 用户信息
    public const CUSTOM_ERROR_EMAIL_NOT_REGISTER     = 20010001;        // 邮箱未注册
    public const CUSTOM_ERROR_EMAIL_ALREADY_REGISTER = 20010002;        // 邮箱已经注册
    public const CUSTOM_ERROR_USER_PASSWORD_ERROR    = 20010003;        // 用户密码错误
    public const CUSTOM_ERROR_USER_NOT_EXISTS        = 20010004;        // 用户不存在
    public const CUSTOM_ERROR_USER_INCOMPLETE        = 20010005;        // 用户信息不完整

    // 邀请信息
    public const CUSTOM_ERROR_INVITATION_CANNOT_OPERATED = 21010001;        // 邀请不能操作

    //审稿
    public const CUSTOM_ERROR_INVITATION_IS_EXISTS                 = 21010002;           //被邀请人已存在邀请表中
    public const CUSTOM_ERROR_CONDITION_NOT_SATISFIED              = 21010003;           //前置条件不满足
    public const CUSTOM_ERROR_NOT_CHECK_ALL_ITEMS                  = 21010004;           //未核对完检查清单
    public const CUSTOM_ERROR_EDITORS_COMMENTS_VERIFY              = 20110005;           //编辑评论验证
    public const CUSTOM_ERROR_UPLOAD_FINAL_VERSION_FILES_VERIFY    = 21010006;           //最终版本文件验证
    public const CUSTOM_ERROR_UPLOAD_LANGUAGE_EDITING_FILES_VERIFY = 21010007;           //润色文件验证
    public const CUSTOM_ERROR_UPLOAD_CONTENT_CHECKING_FILES_VERIFY = 21010008;           //内容检查文件验证
    public const CUSTOM_ERROR_UPLOAD_TYPESETTING_FILES_VERIFY      = 21010009;           //排版文件验证
    public const CUSTOM_ERROR_ACCEPT_REVIEWER_SHOULD_CANCELED      = 21010010;           //同意的审稿人没有发布报告的要取消

    // Word文档转化
    public const CUSTOM_ERROR_WORD_CONVERSION_FAILED = 22010001;        // Word转化失败
    //特刊
    public const CUSTOM_ERROR_DATA_STATUS_ERROR   = 30010001;             // 请求数据状态错误
    public const CUSTOM_ERROR_DATA_REPEAT         = 30020001;             // 请求数据重复
    public const CUSTOM_ERROR_SI_DEADLINE_ARRIVED = 30030001;          // 特刊截止时间到了
    public const CUSTOM_ERROR_SI_WITHDRAWN        = 30030002;          // 特刊下架了

    // 文章发布
    public const CUSTOM_ERROR_PUBLISHED_FAILED = 30040001;          // 文章发布失败

    //支付
    public const CUSTOM_ERROR_PAID_THE_FEE = 4001001;


    // 错误码对应的信息
    public const CODE_MESSAGE = [
        self::SERVER_ERROR                                      => 'The service is busy, please try again later',
        self::PARAMS_ERROR                                      => 'The data is incorrect, please check the data',
        self::NOT_PERMISSION                                    => 'no permission operation',
        self::CUSTOM_ERROR_DATA_NOT_EXISTS                      => 'request data does not exist',
        self::CUSTOM_ERROR_DATA_EXISTS                          => 'request data already exist',
        self::CUSTOM_ERROR_DATA_NOT_DELETE                      => 'request data not delete',
        self::CUSTOM_ERROR_EMAIL_NOT_REGISTER                   => 'email not register',
        self::CUSTOM_ERROR_EMAIL_ALREADY_REGISTER               => 'email already register',
        self::CUSTOM_ERROR_USER_PASSWORD_ERROR                  => 'username or password error',
        self::CUSTOM_ERROR_USER_NOT_EXISTS                      => 'user not exists',
        self::CUSTOM_ERROR_NAME_ALREADY_EXISTS                  => 'name already exists',
        self::CUSTOM_ERROR_MAIL_SEND_FAIL                       => 'email send fail',
        self::CUSTOM_ERROR_INVITATION_CANNOT_OPERATED           => 'This invitation cannot be operated',
        self::CUSTOM_ERROR_WORD_CONVERSION_FAILED               => 'Word conversion failed',
        self::CUSTOM_ERROR_DATA_STATUS_ERROR                    => 'This data status is error',
        self::CUSTOM_ERROR_USER_LOGIN_FAIL                      => 'User login failed',
        self::CUSTOM_ERROR_DATA_REPEAT                          => 'This data is repeat',
        self::CUSTOM_ERROR_SI_DEADLINE_ARRIVED                  => 'The deadline for this special issue has expired',
        self::CUSTOM_ERROR_SI_WITHDRAWN                         => 'The special issue has been offline',
        self::CUSTOM_ERROR_DATA_LOCKING                         => 'The data has been submitted, please try again later',
        self::CUSTOM_ERROR_FILE_UPLOAD                          => 'File upload failed',
        self::CUSTOM_ERROR_INVITATION_IS_EXISTS                 => 'Please note that this user already exists on the invitation list of this manuscript.',
        self::CUSTOM_ERROR_CONDITION_NOT_SATISFIED              => 'The pre conditions for the operation are not met',
        self::CUSTOM_ERROR_PUBLISHED_FAILED                     => 'The article failed to be published. Please try again later',
        self::CUSTOM_ERROR_NOT_CHECK_ALL_ITEMS                  => 'Please check all the items in the form.',
        self::CUSTOM_ERROR_EDITORS_COMMENTS_VERIFY              => 'Please Submit the Editor Comments First.',
        self::CUSTOM_ERROR_UPLOAD_FINAL_VERSION_FILES_VERIFY    => 'Please upload the final version for language editing.',
        self::CUSTOM_ERROR_UPLOAD_LANGUAGE_EDITING_FILES_VERIFY => 'Please upload the language editing file.',
        self::CUSTOM_ERROR_UPLOAD_CONTENT_CHECKING_FILES_VERIFY => 'Please upload the content checking file.',
        self::CUSTOM_ERROR_UPLOAD_TYPESETTING_FILES_VERIFY      => 'Please upload the typesetting file.',
        self::CUSTOM_ERROR_ACCEPT_REVIEWER_SHOULD_CANCELED      => 'The accepted reviewer should be canceled manually.',
        self::CUSTOM_ERROR_PAID_THE_FEE                         => 'You have paid the publication fee.',
        self::CUSTOM_ERROR_USER_INCOMPLETE                      => 'Your personal information is incomplete, please complete the information',
    ];
}
