<?php

namespace App\Admin\Controllers;

use App\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
//use Someline\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class UserController
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
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->user_id('ID')->sortable();
            $grid->name('username')->sortable();
            $grid->phone_number('手机号');
            $grid->email('邮箱');
            $grid->plate_number('车牌号');
            $grid->status('状态')->options([1 => '已审核', 0 => '未审核']);

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
        return Admin::form(User::class, function (Form $form) {

            $form->display('user_id', 'ID');
            $form->text('name', 'name');
            $form->text('phone_number', 'phone_number');
            $form->text('email', 'email');
            $form->text('plate_number', 'plate_number');
            $form->select('status', '状态')->options([1 => '已审核', 0 => '未审核']);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
