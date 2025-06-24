<?php

namespace App\Constants;

class Constant
{
    public const MANUSCRIPT_ID_POINT = 0;
    // 通用每页显示的数量
    public const PAGE_SIZE = 20;

    // 是否选择
    public const IS_NO  = 0;
    public const IS_YES = 1;

    // 邮件模版类型
    public const MAIL_TYPE_MANUSCRIPT = 1;  // 稿件模版
    public const MAIL_TYPE_GENERAL    = 2;  // 普通模版
    public const MAIL_TYPE_LIST       = [
        self::MAIL_TYPE_MANUSCRIPT => 'Manuscript',
        self::MAIL_TYPE_GENERAL    => 'General',
    ];


    // 类型(1=uid，2=user_internal_id)
    const USER_DATA_TYPE_UID = 1;
    const USER_DATA_TYPE_IID = 2;

    // 操作类型
    public const OPT_TYPE_DELETE          = 'delete';
    public const OPT_TYPE_EDIT            = 'edit';
    public const OPT_TYPE_USER_EDIT       = 'user_edit';
    public const OPT_TYPE_CANCEL          = 'cancel';
    public const OPT_TYPE_INVITATION      = 'invitation';
    public const OPT_TYPE_REMIND          = 'remind';
    public const OPT_TYPE_DOWNLOAD        = 'download';
    public const OPT_TYPE_ACCEPT          = 'accept';
    public const OPT_TYPE_REJECT          = 'reject';
    public const OPT_TYPE_APPLICATION     = 'application';
    public const OPT_TYPE_VIEW            = 'view';
    public const OPT_TYPE_REMOVE          = 'remove';
    public const OPT_TYPE_PROXY           = 'proxy';
    public const OPT_TYPE_RE_INVITE       = 're_invite';
    public const OPT_TYPE_REVIEW          = 'review';
    public const OPT_TYPE_PAY_REVIEW      = 'pay_review';
    public const OPT_TYPE_LICENSE_REVIEW  = 'license_review';
    public const OPT_TYPE_REVIEWER_REVIEW = 'reviewer_review';
    public const OPT_TYPE_REJECTED_REVIEW = 'rejected_review';
    public const OPT_TYPE_EVALUATE        = 'evaluate';
    public const OPT_TYPE_ORCID           = 'orcid';
    public const OPT_TYPE_GRANT           = 'grant';
    public const OPT_TYPE_REINSTATE       = 'reinstate';
    public const OPT_TYPE_WITHDRAW        = 'withdraw';

    // 公用路由名称
    const BASE_ROUTER_NAME = 'baseRouterName';

    const YOUTH_EBM_POSITION = 'Youth Editorial Board Member';

    const EDITOR_IN_CHIEF_POSITION = 'Editor-in-Chief';

    const TYPE_MY  = 1;
    const TYPE_ALL = 2;
}
