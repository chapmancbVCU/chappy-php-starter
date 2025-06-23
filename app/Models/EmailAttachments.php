<?php
namespace App\Models;
use Core\Model;

/**
 * Implements features of the EmailAttachments class.
 */
class EmailAttachments extends Model {

    // Fields you don't want saved on form submit
    // public const blackList = [];

    // Set to name of database table.
    protected static $_table = 'email_attachments';

    // Soft delete
    // protected static $_softDelete = true;
    
    // List your allowed file types.
    protected static $allowedFileTypes = [];
    
    // Set your max file size.
    protected static $maxAllowedFileSize = 17825792;

    // Set your file path.  Include your bucket if necessary.
    protected static $_uploadPath = 'storage'.DS.'app'.DS.'private'.DS .'email_attachments';
    
    // Fields from your database


    public function afterDelete(): void {
        // Implement your function
    }

    public function afterSave(): void {
        // Implement your function
    }

    public function beforeDelete(): void {
        // Implement your function
    }

    public function beforeSave(): void {
        $this->timeStamps();
    }

    /**
     * Getter function for $allowedFileTypes array
     *
     * @return array $allowedFileTypes The array of allowed file types.
     */
    public static function getAllowedFileTypes(): array {
        return self::$allowedFileTypes;
    }

    /**
     * Getter function for $maxAllowedFileSize.
     *
     * @return int $maxAllowedFileSize The max file size for an individual 
     * file.
     */
    public static function getMaxAllowedFileSize(): int {
        return self::$maxAllowedFileSize;
    }

    /**
     * Performs upload
     *
     * @return void
     */
    public static function uploadFile(): void {
        // Implement your function
    }
}
