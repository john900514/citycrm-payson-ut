<?php

namespace AnchorCMS\Http\Requests;

use AnchorCMS\Images;
use Illuminate\Foundation\Http\FormRequest;

class ImagesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'           => 'bail|required',
            'page'           => 'bail|required',
            'url'            => 'bail|required',
            'active'         => 'bail|required',
            //'schedule_end'   => 'sometimes|required',
            'schedule_start' => 'sometimes|required'//_with:schedule_end',
            /*'scheduleStart' => [
                'required_with:scheduleEnd', new ValidScheduleWindow( request()->all() )
            ]*/
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }

    public function checkForActiveunScheduledImage($data, Images $imgs)
    {
        $valid = false;

        if($data['active'] == 1)
        {
            // if an unscheduled Active Image exists, yell at the user.
            $img = $imgs->wherePage($data['page'])->whereName($data['name'])->whereActive(1)->first();
            $valid = is_null($img);
            \Alert::error('Cannot have more than one active unscheduled image. Please deactivate the <a href="'.backpack_url('image-mgnt').'/'.$img->id.'/edit">previous</a> image and try again')->flash();
        }
        else
        {
            $valid = true;
        }

        return $valid;
    }
}
