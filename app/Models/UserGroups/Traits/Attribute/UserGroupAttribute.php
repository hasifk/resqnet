<?php

namespace App\Models\UserGroups\Traits\Attribute;

use DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class UserAttribute
 * @package App\Models\Access\User\Traits\Attribute
 */
trait UserGroupAttribute {

    public function attachUserGroupImage($newsfeedFile) {

        if (is_null($newsfeedFile)) {
            return;
        }
        $filePath = "public/UserGroup/image/" . $this->id . "/";
        $file_name = time();



        Storage::deleteDirectory($filePath);

        if (Storage::put($filePath . $file_name . '.' . $newsfeedFile->getClientOriginalExtension(), file_get_contents($newsfeedFile))):
            if (Storage::setVisibility($filePath . $file_name . '.' . $newsfeedFile->getClientOriginalExtension(), 'public')):


                // Resizing the newsfeed images
                $avatar = $newsfeedFile;



                foreach (config('image.customized.gp_image') as $image) {
                    $newsfeed_image = \Image::make($avatar);
                    $newsfeed_image->orientate();
                    $newsfeed_image->resize($image['width'], $image['height'])->save(storage_path('app/' . $filePath . $file_name . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension()));
                    Storage::put($filePath . $file_name . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension(), file_get_contents(storage_path('app/' . $filePath . $file_name . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension())));
                    Storage::setVisibility($filePath . $file_name . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension(), 'public');
                }
                $this->gp_image_filename = $file_name;
                $this->gp_image_extension = $newsfeedFile->getClientOriginalExtension();
                $this->gp_image_path = $filePath;
                $this->save();
            endif;
        endif;
    }

    public function detachUserGroupImage() {
        if ($this->gp_image_filename && $this->gp_image_extension && $this->gp_image_path) {

            foreach (config('image.customized.newsfeed_image') as $image) {
                Storage::delete($this->gp_image_path . $this->gp_image_filename . $image['width'] . 'x' . $image['height'] . '.' . $this->gp_image_extension);
            }
            Storage::delete($this->gp_image_path . $this->gp_image_filename . '.' . $this->gp_image_extension);
        }
    }

    /**
     * @return string
     */
    public function getShowButtonAttribute() {
        if (access()->allow('show-users'))
        return '<a href="' . route('admin.newsfeed.newsfeedshow', $this->id) . '" class="btn btn-xs btn-success"><i class="fa fa-arrow-circle-right" data-toggle="tooltip" data-placement="top" title="View More"></i></a> ';
        return '';
    }

    /**
     * @return string
     */
    
    public function getDeleteButtonAttribute() {
        if (access()->allow('delete-newsfeed'))
            return '<a href="' . route('admin.newsfeed.deletenewsfeed', $this->id) . '" class="newsfeed_delete btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
        return '';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute() {
        return $this->getShowButtonAttribute() .
                $this->getDeleteButtonAttribute();
    }

}
