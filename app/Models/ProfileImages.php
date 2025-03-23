<?php
namespace App\Models;
use Core\{Helper, Model};
use Core\Lib\Utilities\ArraySet;

/**
 * Supports CRUD operations on profile image records.
 */
class ProfileImages extends Model {
    protected static $allowedFileTypes = ['image/gif', 'image/jpeg', 'image/png'];
    public $deleted = 0;
    public $id;
    protected static $maxAllowedFileSize = 5242880;
    public $name;
    protected static $_softDelete = true;
    public $sort;
    protected static $_table = 'profile_images';
    protected static $_uploadPath = 'storage'.DS.'app'.DS.'private'.DS .'profile_images'.DS.'user_';
    public $url;
    public $user_id;

    /**
     * Implements beforeSave function described in Model parent class. 
     * Generates timestamps.
     *
     * @return void
     */
    public function beforeSave(): void {
        $this->timeStamps();
    }
    
    /**
     * Deletes a profile image by id.
     *
     * @param int $id The id of the image we want to delete.
     * @return bool Result of delete operation.  True if success, otherwise 
     * false.
     */
    public static function deleteById($id) {
        $image = self::findById($id);
        $sort = $image->sort;
        $afterImages = self::find([
            'conditions' => 'user_id = ? and sort > ?',
            'bind' => [$image->user_id, $sort]
        ]);
        foreach($afterImages as $af) {
            $af->sort = $af->sort - 1;
            $af->save();
        }
        unlink(ROOT.DS.self::$_uploadPath.$image->user_id.DS.$image->name);
        return $image->delete();
    }
    
    /**
     * Returns currently set profile image for a user.
     *
     * @param int $user_id The id of the user whose profile image we want to 
     * retrieve.
     * @return bool|array The associative array for the profile image's 
     * record.
     */
    public static function findCurrentProfileImage($user_id) {
        return $image = self::findFirst([
            'conditions' => 'user_id = ? AND sort = 0',
            'bind' => ['user_id' => $user_id]
        ]);
    }

    /**
     * Finds all profile images for a user.
     *
     * @param int $user_id The id of the user whose profile images we want to 
     * retrieve.
     * @return bool|array The associative array of profile image records for a 
     * user.
     */
    public static function findByUserId($user_id) {
        return $images = self::find([
            'conditions' => 'user_id = ?',
            'bind' => ['user_id' => $user_id],
            'order' => 'sort'
        ]);
    }

    /**
     * Getter function for $allowedFileTypes array
     *
     * @return array $allowedFileTypes The array of allowed file types.
     */
    public static function getAllowedFileTypes() {
        return self::$allowedFileTypes;
    }

    /**
     * Getter function for $maxAllowedFileSize.
     *
     * @return int $maxAllowedFileSize The max file size for an individual 
     * file.
     */
    public static function getMaxAllowedFileSize() {
        return self::$maxAllowedFileSize;
    }
    
    /**
     * Updates sort order by user id.
     *
     * @param int $user_id The id of the user whose profile images we want 
     * to sort.
     * @param array $sortOrder An array containing sort values for a profile 
     * image.
     * @return void
     */
    public static function updateSortByUserId($user_id, $sortOrder = []) {
        $images = self::findByUserId($user_id);
        $i = 0;
        foreach($images as $image) {
            $val = 'image_'.$image->id;
            $sort = (in_array($val,$sortOrder)) ? array_search($val, $sortOrder) : $i;
            $image->sort = $sort;
            $image->save();
            $i++;
        }
    }

    /**
     * Performs upload operation for a profile image.
     *
     * @param int $user_id The id of the user that the upload operation 
     * is performed upon.
     * @param Uploads $uploads The instance of the Uploads class for this 
     * upload.
     * @return void
     */
    public static function uploadProfileImage($user_id, $uploads) {
        $lastImage = self::findFirst([
            'conditions' => "user_id = ?",
            'bind' => [$user_id],
            'order' => 'sort DESC'
        ]);
        $lastSort = (!$lastImage) ? 0 : $lastImage->sort;
        $path = self::$_uploadPath.$user_id.DS;
        foreach($uploads->getFiles() as $file) {
            $uploadName = $uploads->generateUploadFilename($file['name']);
            $image = new self();
            $image->url = $path . $uploadName;
            $image->name = $uploadName;
            $image->user_id = $user_id;
            $image->sort = $lastSort;
            if($image->save()) {
                $uploads->upload($path, $uploadName, $file['tmp_name']);
                $lastSort++;
            }
        }
    }
}