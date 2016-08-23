<?php namespace App\Models\Newsfeed\Traits\Attribute;

use DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class UserAttribute
 * @package App\Models\Access\User\Traits\Attribute
 */
trait NewsfeedAttribute {

    public function attachNewsfeedImage($newsfeedFile)
    {

        if (is_null($newsfeedFile)) {
            return;
        }
        $filePath = "public/newsfeed/image/". $this->id."/";
        $this->image_filename= pathinfo($newsfeedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $this->image_extension=$newsfeedFile->getClientOriginalExtension();
        $this->image_path=$filePath;
        $this->save();


        Storage::deleteDirectory($filePath);

        Storage::put($filePath . $newsfeedFile->getClientOriginalName(), file_get_contents($newsfeedFile));
        Storage::setVisibility($filePath . $newsfeedFile->getClientOriginalName(), 'public');


// Resizing the newsfeed images
        $avatar = $newsfeedFile;


        Storage::disk('local')->put($filePath . $avatar->getClientOriginalName(), file_get_contents($avatar));

        foreach (config('image.customized.newsfeed_image') as $image) {
            $newsfeed_image= \Image::make($avatar);
            $newsfeed_image->resize($image['width'], $image['height'])->save(storage_path('app/' . $filePath . pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension()));
            Storage::put($filePath . pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension(), file_get_contents(storage_path('app/' . $filePath . pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension())));
            Storage::setVisibility($filePath . pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME) . $image['width'] . 'x' . $image['height'] . '.' . $avatar->getClientOriginalExtension(), 'public');
        }
    }


    public function detachNewsfeedImage(){
        if ($this->image_filename && $this->image_extension && $this->image_path) {

            foreach (config('image.customized.newsfeed_image') as $image) {
                Storage::delete($this->image_path . $this->image_filename . $image['width'] . 'x' . $image['height'].'.' . $this->image_extension);
            }
            Storage::delete($this->image_path.$this->image_filename.'.'.$this->image_extension);
        }

    }
    
     /**
     * @return string
     */
    
    public function getShowButtonAttribute() {
       // if (access()->can('show-users'))
            return '<a href="'.route('admin.newsfeed.newsfeedshow', $this->id).'" class="btn btn-xs btn-success"><i class="fa fa-arrow-circle-right" data-toggle="tooltip" data-placement="top" title="View More"></i></a> ';
        return '';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute() {
        if (access()->allow('delete-newsfeed'))
            return '<a href="'.route('admin.newsfeed.newsfeedshow', $this->id).'" class="newsfeed_delete btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
        return '';
    }

    /**
     * @return string
     */
    
    public function getActionButtonsAttribute() {
        return $this->getShowButtonAttribute().
        $this->getDeleteButtonAttribute();
    }

}
