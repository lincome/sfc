<?php

namespace App\Admin\Controllers;

use App\Order;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
//use Someline\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class OrderController
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Order::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->city_start('出发地')->sortable();
            $grid->city_end('目的地');
            $grid->start_date('出发时间');
            $grid->price('价格');
            $grid->mobile('手机号');
            $grid->status('状态')->sortable();

            $grid->created_at()->sortable();
            $grid->updated_at();

            //$grid->disableBatchDeletion();

            //$grid->disableExport();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Order::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->display('city_start', '出发地');
            $form->display('city_end', '目的地');
            $form->text('start_date', '出发时间');
            $form->text('price', '价格');
            $form->text('mobile', '手机号');
            $form->select('status', '状态')->options([1 => '已审核', 0 => '未审核']);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
