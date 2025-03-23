<h1 style="font-size: 50px; text-align: center;">Uploads</h1>

## Table of contents
1. [Overview](#overview)
2. [Setup](#setup)
    * A. [Migration File](#migration-file)
    * B. [Setting up the Model](#model-setup)
3. [Single File Upload](#single-file)
4. [Multiple File Upload](#multiple-file)
5. [Configuring File Types](#file-types)
<br>
<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents]
This framework supports single and multiple file uploads.  Switching between both modes is relatively easy and is achieved by changing one line of code in your view file and the action function that renders the view.  In this guide we will use the ProfileController and it's associated view located at "resources/views/profile/edit.php" as examples.

To enable uploads in your Controllers add the following import:
```php
use Core\Lib\FileSystem\Uploads;
```
<br>

## 2. Setup <a id="setup"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents]
The upload feature is supported by the Uploads class.  To use the Uploads class you will need to perform the following steps:
<br>

#### A. Migration File <a id="migration-file">
We need to create a table in the database to store information about the profile pictures we want to upload.  Run the following command to create a migration.
```sh
php console make:migration profile_images
```

Edit the up function to contain the fields you need to use for your new table.  Here is an example of of our up function:

```php
public function up() {
    $table = 'profile_images';
    $this->createTable($table);
    $this->addColumn($table, 'url', 'varchar', ['size' => 255]);
    $this->addColumn($table, 'sort', 'int');
    $this->addColumn($table, 'user_id', 'int');
    $this->addColumn($table, 'name', 'varchar', ['size' => 255]);
    $this->addSoftDelete($table);
}
```

The name of the table will already be set along with a call to create the table.  Add any extra fields that you need.  Once you are finished you can perform the migration command shown below:

```sh
php console migrate
```

Once the migration has been complete the new table will now be accessible in your database.
<br>

#### B. Setting up the Model <a id="model-setup">
First we create a new model file.

```sh
php console make:model ProfileImages
```

The new model file will be created at `app/models/`.  You will need to add instance variables for any database fields and set them to public.  We also need to add protected static instance variables for allowed file types, maximum file size, and upload path.  Finally, set the $_table variable to match the name of the table you just created.  Since profile images are associated with a user we also added a $user_id instance variable to this class to match what we have in the migration file.  The final list of instance variables is shown below:

```php
protected static $allowedFileTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];
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
```

Finally, create a function to perform the upload.  An example is shown below:

```php
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
```

The key parts common to most uploads are the `$path` and generating a unique hashed filename with the `generateUploadFilename` function.  It required to used the original file name since this function needs it to determine the correct file extension.

The last parts that are common is the setting of values for the database fields using the model's instance variables, the test to determine if saving the record is successful, and the setup of the foreach loop.  If and only if the record save is successful then we proceed to upload the file.

The upload function accepts 3 parameters:
- string $path - Directory where file will exist when uploaded.
- string $uploadName - The actual name for the file when uploaded.
- string $fileName - The temporary file name.
<br>

## 3. Single File Upload <a id="single-file"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents]
Setting up single file uploads requires the correct configuration of your action inside of the appropriate controller and the associated view file.  Let's look at the editAction function for the ProfileController.

```php
public function editAction(): void {
    $user = Users::currentUser();
    if(!$user) {
        Session::addMessage('danger', 'You do not have permission to edit this user.');
        Router::redirect('');
    }

    $profileImages = ProfileImages::findByUserId($user->id);
    if($this->request->isPost()) {
        $this->request->csrfCheck();

        // Handle file upload using the static method in Uploads
        $uploads = Uploads::handleUpload(
            $_FILES['profileImage'],
            ProfileImages::getAllowedFileTypes(),
            ProfileImages::getMaxAllowedFileSize(),
            ROOT . DS,
            "5mb"
        );

        $user->assign($this->request->get(), Users::blackListedFormKeys);
        $user->save();
        if($user->validationPassed()){
            if($uploads) {
                // Upload Image
                ProfileImages::uploadProfileImage($user->id, $uploads);
            }
            ProfileImages::updateSortByUserId($user->id, json_decode($_POST['images_sorted']));

            // Redirect
            Router::redirect('profile/index');
        }
    }

    $this->view->profileImages = $profileImages;
    $this->view->displayErrors = $user->getErrorMessages();
    $this->view->user = $user;
    $this->view->render('profile/edit');
}
```

Let's zero in on the block of code below the comment for `Handle file uploads`.  Between single and multiple file uploads most of the code is copy in paste.  We shall focus on the following line below and explain what happens.

```php
$uploads = Uploads::handleUpload(
    $_FILES['profileImage'],
    ProfileImages::class,
    ROOT . DS,
    "5mb",
    $user,
    'profileImage'
);
```

The boolean value `false` is for the instance variable of the Upload class called $uploads.  When this value is set to false multiple file uploads is disabled.  ROOT.DS is the bucket variable.  Since we are using localhost for profile images it's set to the project root followed by a directory separator variable.  It can also be set as the path to a host containing an S3 bucket on a cloud base service such as Amazon Web Services (AWS).  The last variable, `5mb`, is used for messaging purposes for file size validation.

When setting up the view we use a call to the inputBlock function.  In the example below we retrieve files from POST using the value `profileImage` as shown below:

```php
<?= FormHelper::inputBlock('file', "Upload Profile Image (Optional)", 'profileImage', '', ['class' => 'form-control', 'accept' => 'image/gif image/jpeg image/png'], ['class' => 'form-group mb-3'], $this->displayErrors) ?>
```
<br>

## 4. Multiple File Upload <a id="multiple-file"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents]
There are two main differences when it comes to setting up uploads with multiple files.  Let's look at the call for the Uploads constructor again.

```php
$uploads = Uploads::handleUpload(
    $_FILES['profileImage'],
    ProfileImages::class,
    ROOT . DS,
    "5mb",
    $user,
    'profileImage',
    true
);
```

This time the value for the $multiple parameter is set to true.  That is the only change needed in your model file to switch from single file to multiple file upload mode.  The view file needs two additional changes as shown below:

```php
<?= FormHelper::inputBlock('file', "Upload Profile Image (Optional)", 'profileImage[]', '', ['multiple' => 'multiple', 'class' => 'form-control', 'accept' => 'image/gif image/jpeg image/png'], ['class' => 'form-group mb-3'], $this->displayErrors) ?>
```

The profileImage, the name attribute's value, needs brackets so that we know we are using an array of files as the value for the inputBlock function call.  You also need to add 'multiple' => 'multiple' as an element for the $inputAttrs array.  Otherwise, the window that allows users to select a file will only allow you to select one file.

## 5. Configuring File Types <a id="file-types"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents]
The following is a sample list of file types you can place in the `$allowedFileTypes` array:

```php
protected static $allowedFileTypes = [
    // **Images**
    'image/gif',
    'image/jpeg',
    'image/pjpeg',
    'image/png',
    'image/apng',
    'image/webp',
    'image/svg+xml',
    'image/tiff',
    'image/x-tiff',
    'image/bmp',
    'image/x-ms-bmp',
    'image/heif',
    'image/heic',
    'image/x-icon',
    'image/vnd.microsoft.icon',

    // **Documents (Word, PDF, OpenDocument)**
    'application/pdf',                    // PDF
    'application/msword',                  // DOC (Microsoft Word 97-2003)
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX (Microsoft Word)
    'application/vnd.oasis.opendocument.text', // ODT (OpenDocument Text)

    // **Text Files**
    'text/plain',                          // TXT (Plain text)
    'text/csv',                            // CSV (Comma-separated values)
    'text/html',                           // HTML (HyperText Markup Language)
    'text/xml',                            // XML (Extensible Markup Language)
    'application/json',                    // JSON (JavaScript Object Notation)

    // **Spreadsheets**
    'application/vnd.ms-excel',            // XLS (Microsoft Excel 97-2003)
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // XLSX (Microsoft Excel)
    'application/vnd.oasis.opendocument.spreadsheet', // ODS (OpenDocument Spreadsheet)
    'text/csv',                            // CSV (Comma-separated values)

    // **Presentations**
    'application/vnd.ms-powerpoint',       // PPT (Microsoft PowerPoint 97-2003)
    'application/vnd.openxmlformats-officedocument.presentationml.presentation', // PPTX (Microsoft PowerPoint)
    'application/vnd.oasis.opendocument.presentation', // ODP (OpenDocument Presentation)

    // **Archives & Compressed Files**
    'application/zip',                      // ZIP (Compressed archive)
    'application/x-7z-compressed',          // 7Z (7-Zip archive)
    'application/x-rar-compressed',         // RAR (RAR archive)
    'application/x-tar',                    // TAR (Tape Archive)
    'application/gzip',                      // GZ (Gzip compressed file)

    // **Audio Files**
    'audio/mpeg',                           // MP3
    'audio/ogg',                            // OGG
    'audio/wav',                            // WAV
    'audio/x-wav',                          // WAV (alternative)
    'audio/flac',                           // FLAC (Free Lossless Audio Codec)
    'audio/aac',                            // AAC (Advanced Audio Codec)
    'audio/webm',                           // WEBM Audio

    // **Video Files**
    'video/mp4',                            // MP4 (MPEG-4)
    'video/x-msvideo',                      // AVI
    'video/x-ms-wmv',                       // WMV
    'video/webm',                           // WEBM
    'video/ogg',                            // OGG Video
    'video/quicktime',                      // MOV (Apple QuickTime)
    'video/x-flv',                          // FLV (Flash Video)

    // **Executable & Scripts**
    'application/x-msdownload',             // EXE (Windows Executable)
    'application/x-sh',                     // SH (Shell script)
    'application/x-python-code',            // PY (Python script)
    'application/javascript',               // JS (JavaScript file)
    'application/x-httpd-php',              // PHP (PHP script)

    // **Fonts**
    'font/otf',                             // OTF (OpenType Font)
    'font/ttf',                             // TTF (TrueType Font)
    'application/vnd.ms-fontobject',        // EOT (Embedded OpenType)
    'font/woff',                            // WOFF (Web Open Font Format)
    'font/woff2',                           // WOFF2 (Web Open Font Format v2)

    // **Miscellaneous**
    'application/x-shockwave-flash',        // SWF (Adobe Flash)
    'application/vnd.android.package-archive', // APK (Android Package)
];
```