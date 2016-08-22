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
        $this->avatar_filename= pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
        $this->avatar_extension=$avatarFile->getClientOriginalExtension();
        $this->avatar_path=$filePath;
        $this->save();


        Storage::deleteDirectory($filePath);

        Storage::put($filePath . $avatarFile->getClientOriginalName(), file_get_contents($avatarFile));
        Storage::setVisibility($filePath . $avatarFile->getClientOriginalName(), 'public');


// Resizing the newsfeed images
        $avatar = $avatarFile;


        Storage::disk('local')->put($filePath . $avatar->getClientOriginalName(), file_get_contents($avatar));

        foreach (config('image.customized.profile_avatar') as $image) {
            $avatar_image= \Image::make($avatar);
            $avatar_image->resize($image['width'], $image['height'])->save(storage_path('app/' . $filePath . pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension()));
            Storage::put($filePath . pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension(), file_get_contents(storage_path('app/' . $filePath . pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension())));
            Storage::setVisibility($filePath . pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension(), 'public');
        }


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
