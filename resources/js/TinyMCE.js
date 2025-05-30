function initializeTinyMCE(id) {
    tinymce.init({
        selector: `#${id}`,
        branding: false,
        menubar: false,
        height: 300,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
        toolbar: 'undo redo | bold italic backcolor underline strikethrough | outdent indent | alignleft aligncenter alignright alignjustify | removeformat | help',
        license_key: 'gpl'
    });
}
