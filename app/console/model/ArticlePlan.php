<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2021 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: https://gitee.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/ThinkLibrary
// | github 代码仓库：https://github.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------

declare (strict_types=1);

namespace app\console\model;

use think\admin\Model;

/**
 * 翻译计划
 * Class SystemBase
 * @package think\admin\model
 */
class ArticlePlan extends Model
{
    /**
     * 日志名称
     * @var string
     */
    protected $oplogName = '翻译计划';

    /**
     * 日志类型
     * @var string
     */
    protected $oplogType = '翻译计划管理';

    protected $table = 'article_plan';
}