<?php
namespace App\Models\Access\User\Traits\Attribute;

use DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class UserAttribute
 * @package App\Models\Access\User\Traits\Attribute
 */
trait AvatarAttribute {

    public function attachProfileImage($avatarFile)
    {

        if (is_null($avatarFile)) {
            return;
        }
        $filePath = "public/profile/avatar/". $this->id."/";

       $file_name=time();
        Storage::deleteDirectory($filePath);

        if(Storage::put($filePath . $file_name.'.'.$avatarFile->getClientOriginalExtension(), file_get_contents($avatarFile))):
       if(Storage::setVisibility($filePath . $file_name.'.'.$avatarFile->getClientOriginalExtension(), 'public')):





// Resizing the newsfeed images
        $avatar = $avatarFile;


        foreach (config('image.customized.profile_avatar') as $image) {
            $avatar_image= \Image::make($avatar);
            $avatar_image->orientate();
            $avatar_image->fit($image['width'], $image['height'])->save(storage_path('app/' . $filePath . $file_name . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension()));
            Storage::put($filePath . $file_name . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension(), file_get_contents(storage_path('app/' . $filePath . $file_name . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension())));
            Storage::setVisibility($filePath . $file_name. $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension(), 'public');
        }

                $this->avatar_filename= $file_name;
                $this->avatar_extension=$avatarFile->getClientOriginalExtension();
                $this->avatar_path=$filePath;
                $this->save();
                endif;
            endif;


    }


    public function detachProfileImage(){
        if ($this->avatar_filename && $this->avatar_extension && $this->avatar_path) {

            foreach (config('image.customized.profile_avatar') as $image) {
                Storage::delete($this->avatar_path . $this->avatar_filename . $image['width'] . 'x' . $image['height'].'.' . $this->avatar_extension);
            }
            Storage::delete($this->avatar_path.$this->avatar_filename.'.'.$this->avatar_extension);
        }

    }


}
