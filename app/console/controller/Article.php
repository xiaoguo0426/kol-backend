<?php


namespace app\console\controller;

use app\console\model\ArticlePlan;
use Stichoza\GoogleTranslate\GoogleTranslate;
use think\admin\Controller;
use think\admin\service\AdminService;
use think\facade\Db;

/**
 * 文章管理
 * Class Article
 * @package app\console\controller
 */
class Article extends Controller
{

    /**
     * 翻译计划
     * @auth true
     * @menu true
     */
    public function index()
    {
        ArticlePlan::mQuery()->layTable(function () {
            $this->title = '翻译计划';
        });

    }

    /**
     * 添加计划
     * @auth true
     * @menu true
     */
    public function add()
    {
        if ($this->request->isGet()) {
            ArticlePlan::mForm('form');
        } else {
            $update = [
                'create_by' => AdminService::instance()->getUserId(),
                'create_date' => date('Y-m-d H:i:s'),
                'title' => $this->request->post('title', ''),
                'country' => $this->request->post('country', ''),
                'table_link' => $this->request->post('table_link', ''),
            ];
            if (ArticlePlan::mk()->insert($update) !== false) {
                //TODO 拷贝表
                $this->success('添加成功！');
            } else {
                $this->error('添加失败，请稍候再试！');
            }
        }
    }

    public function edit()
    {
        if ($this->request->isGet()) {
            ArticlePlan::mForm('form');
        } else {
            $update = [
                'title' => $this->request->post('title', ''),
            ];
            $id = $this->request->post('id', '');

            if (ArticlePlan::mk()->where('id', $id)->update($update) !== false) {
                $this->success('编辑成功！');
            } else {
                $this->error('编辑失败，请稍候再试！');
            }
        }
    }

    /**
     * 文章列表
     */
    public function list()
    {
        $output = $this->request->get('output', '');
        $country = $this->request->get('country');

        if ($output === 'layui.table') {
            $page = $this->request->get('page', 1);
            $limit = $this->request->get('limit', 20);

            //判断表是否存在

            $table = 'article-' . $country;
            $count = Db::table($table)->count('*');

            $list = [];
            if ($count) {
                $list = Db::table($table)->field('id,title,subtitle')->page($page, $limit)->select();
            }

            return json([
                'code' => 0,
                'count' => $count,
                'data' => $list->toArray(),
                'msg' => ''
            ]);
        } else {
            $this->title = '文章列表';
            $this->country = $country;
            $this->fetch();
        }

    }

    /**
     * 翻译
     * @auth true
     * @menu true
     */
    public function translate()
    {
        if ($this->request->isGet()) {
            $id = $this->request->get('id', '');
            $country = $this->request->get('country');

            $table = 'article-' . $country;
            $article = Db::table($table)->where('id', '=', $id)->find();

            $this->country = $country;
            $this->article = $article;
            $this->fetch();
        } else if ($this->request->isPost()) {

            $id = $this->request->post('id', '');
            $country = $this->request->post('country', '');

            $title = $this->request->post('title', '');
            $subtitle = $this->request->post('subtitle', '');
            $article = $this->request->post('article', '');

            $table = 'article-' . $country;

            $update = Db::table($table)->where('id', '=', $id)->update([
                'title' => $title,
                'subtitle' => $subtitle,
                'content' => $article,
            ]);

            if ($update) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }

    }

    /**
     * 翻译
     */
    public function t()
    {
        $from = $this->request->get('from', 'en');//文章原语言
        $to = $this->request->get('to', 'zh');//文章翻译语言

        $text = $this->request->get('text', '');

        $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
        $tr->setSource($from); // Translate from English
        $tr->setTarget($to); // Translate to Georgian

        //        $translation = '模拟翻译后的文本';
        $translation = $tr->translate($text);
        $this->success('', [
            'translation' => $translation
        ]);
    }

}