<?php
namespace App\Models;
use Core\Model;
use Core\Lib\Mail\Attachments;

/**
 * Implements features of the EmailAttachments class.
 */
class EmailAttachments extends Model {

    // Fields you don't want saved on form submit
    // public const blackList = [];

    // Set to name of database table.
    protected static $_table = 'email_attachments';

    // Soft delete
    protected static $_softDelete = true;
    
    // List your allowed file types.
    protected static $allowedFileTypes = [
        Attachments::MIME_7ZIP,
        Attachments::MIME_DOC,
        Attachments::MIME_DOCX,
        Attachments::MIME_GIF,
        Attachments::MIME_JPG,
        Attachments::MIME_PDF,
        Attachments::MIME_PNG,
        Attachments::MIME_PPTX,
        Attachments::MIME_SVG,
        Attachments::MIME_TAR,
        Attachments::MIME_TEXT,
        Attachments::MIME_XLS,
        Attachments::MIME_XLSX
    ];
    
    // Set your max file size.
    protected static $maxAllowedFileSize = 17825792;

    // Set your file path.  Include your bucket if necessary.
    protected static $_uploadPath = 'storage'.DS.'app'.DS.'private'.DS .'email_attachments';
    
    // Fields from your database
    public $created_at;
    public $deleted = 0;
    public $description;
    public $id;
    public $mime_type;
    public $name;
    public $path;
    public $size;
    public $updated_at;

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
