<?php
/**
 * Created by PhpStorm.
 * User: huanglele
 * Date: 2016/8/28
 * Time: 下午 12:49
 */

namespace Home\Controller;


class DoubleColorBallController extends CommonController
{

    private $model ;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->model = new \Common\Model\DoubleColorModel();
    }

    /**
     * 显示投注页面
     */
    public function index()
    {
        $current_time = $this->model->getCurrentTimesInfo();
        $this->display('index');
    }

    /**
     * 显示我的竞猜记录页面
     */
    public function myGuess()
    {

    }

    /**
     * 投注
     */
    public function buy()
    {

    }

    /**
     * 显示帮助提示页面
     */
    public function help()
    {

    }

}