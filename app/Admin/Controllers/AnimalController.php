<?php

namespace App\Admin\Controllers;

use App\Models\Animal;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Carbon\Carbon;


class AnimalController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Animal';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Animal());

        $grid->column('id', __('Id'));
        $grid->farm()->name('Farm');
        $grid->breed()->name('Breed');
        $grid->column('parents', __('Parents'));
        $grid->column('dob', __('Dob'));
        $grid->column('date_of_weaning', __('Date of weaning'));
        $grid->column('created_at', __('Created at'))->display(function ($x) {
            $c = Carbon::parse($x);
        if ($x == null) {
            return $x;
        }
        return $c->format('d M, Y');
        });
        $grid->column('updated_at', __('Updated at'))->display(function ($x) {
            $c = Carbon::parse($x);
        if ($x == null) {
            return $x;
        }
        return $c->format('d M, Y');
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Animal::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('farm_id', __('Farm id'));
        $show->field('breed_id', __('Breed id'));
        $show->field('parents', __('Parents'));
        $show->field('dob', __('Dob'));
        $show->field('date_of_weaning', __('Date of weaning'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()   
    {
        $form = new Form(new Animal());

        $form->select('farm_id', __('Select Farm'))->options(\App\Models\Farm::pluck('name', 'id'))->rules('required');
        $form->select('breed_id', __('Select Breed'))->options(\App\Models\Breed::pluck('name', 'id'))->rules('required');
        $form->text('parents', __('Parents')); //TODO: Add a select2 dropdown for this
        $form->datetime('dob', __('Date Of Birth'));
        $form->date('date_of_weaning', __('Date of weaning'));

        return $form;
    }
}
