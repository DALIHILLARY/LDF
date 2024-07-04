<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Farmer\ApproveOrDeny;
use App\Admin\Actions\Farmer\Inspect;
use App\Models\Farmer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Utils;
use Encore\Admin\Facades\Admin;


class FarmerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Farmer';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Farmer());
     
        $grid->filter(function ($f) {
            $f->disableIdFilter();
            $f->between('created_at', 'Filter by date')->date();
            $f->where(function ($query) {
                $query->where('surname', 'like', "%{$this->input}%")
                    ->orWhere('given_name', 'like', "%{$this->input}%")
                    ->orWhere('nin', 'like', "%{$this->input}%")
                    ->orWhere('primary_phone_number', 'like', "%{$this->input}%")
                    ->orWhere('secondary_phone_number', 'like', "%{$this->input}%");
            }, 'Search by name, nin, phone number');
            

        });

         //show a user only their records if they are not an admin
         if (!Admin::user()->inRoles(['administrator','ldf_admin'])) {
            $grid->model()->where('user_id', Admin::user()->id);
        }
        //disable batch actions
        $grid->disableBatchActions();

         //order of table
         $grid->model()->orderBy('id', 'desc');

         //disable action buttons appropriately
         Utils::disable_buttons('Vet', $grid);

         $grid->column('profile_picture', __('Profile picture'))->display(function ($profile_picture) {
            if ($profile_picture) {
                return $profile_picture;
            } else {
                return "/images/default_image.png";
            }
        })->image('', 50, 50);
        
        $grid->column('surname', __('Surname'));
        $grid->column('given_name', __('Given name'));
        $grid->column('location', __('Location'));
        $grid->column('marital_status', __('Marital status'))->display(function ($marital_status) {
            switch ($marital_status) {
                case 'S':
                    return 'Single';
                    break;
                case 'M':
                    return 'Married';
                    break;
                case 'D':
                    return 'Divorced';
                    break;
                case 'W':
                    return 'Widowed';
                    break;
                default:
                    return 'Unknown';
                    break;
            }
        });
        $grid->column('status', __('Status'));
       

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
        $show = new Show(Farmer::findOrFail($id));
         //delete notification after viewing the form
         Utils::delete_notification('Farmer', $id);

         $farmer = Farmer::findorFail($id);
         return view('farmers_profile', compact('farmer'));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Farmer());
        if($form->isCreating()){
            $form->hidden('status')->default('Pending');
           
        }
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
           
        });

        $form->text('surname', __('Surname'))->rules('required');
        $form->text('given_name', __('Given name'))->rules('required');
        $form->date('date_of_birth', __('Date of birth'))->rules('required|before:today');
        $form->text('nin', __('Nin'))->rules('required');
        $form->text('location', __('SubCounty'));
        $form->text('village', __('Village'))->rules('required');
        $form->text('parish', __('Parish'))->rules('required');
        $form->text('zone', __('Zone'))->rules('required');
        $form->radio('gender', __('Gender'))->options(['M'=> 'Male', 'F' => 'Female'])->rules('required');
        $form->radio('marital_status', __('Marital status'))->options(['S'=> 'Single', 'M' => 'Married', 'D' => 'Divorced', 'W' => 'Widowed'])->rules('required');
        $form->text('number_of_dependants', __('Number of dependants'))->rules('required|numeric');
        $form->text('farmer_group', __('Farmer group'))->rules('required');
        $form->text('primary_phone_number', __('Phone number'))->rules('required');
        $form->text('secondary_phone_number', __('Other phone number'));
        $form->radio('is_land_owner', __('Do you own land ?'))->options([1=> 'Yes', 0 => 'No'])
            ->when(1, function (Form $form) {
                $form->select('land_ownership', __('Land ownership'))->options([
                    'Lease' => 'Lease',
                    'Rent' => 'Rent',
                    'Own' => 'Own',
                    'Other' => 'Other'
                ]);
            })
            ->rules('required');
        $form->select('production_scale', __('Production Type'))->options([
            'Small scale' => 'Small scale',
            'Medium scale' => 'Medium scale',
            'Large scale' => 'Large scale',
            'Commercial scale' => 'Commercial scale',
            'Other' => 'Other'
        ]);
        $form->radio('access_to_credit', __('Have you ever gotten credit?'))->options(['1'=> 'Yes', '0' => 'No'])
            ->when(1, function (Form $form) {
                $form->text('credit_institution', __('Credit institution'))->rules('required');
            })
        ->rules('required');
        $form->date('date_started_farming', __('Which year did you start farming?'))->default(date('Y'))->format('YYYY')->rules('required');
        $form->select('highest_level_of_education', __('Highest level of education'))
            ->options([
                'None' => 'None',
                'Primary' => 'Primary',
                'Secondary' => 'Secondary',
                'Tertiary' => 'Tertiary',
                'Bachelor' => 'Bachelor',
                'Masters' => 'Masters',
                'PhD' => 'PhD',
            ])->rules('required');
            
        $form->image('profile_picture', __('Profile picture'));
        $form->hidden('added_by')->default(Admin::user()->id);
        $form->hidden('user_id');
      
        return $form;
    }
}
