import '../css/app.css';

// Import TinyMCE and core assets
import tinymce from 'tinymce';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';

// Optional: Plugins
import 'tinymce/plugins/code';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/table';

// Required: Skin CSS
import 'tinymce/skins/ui/oxide/skin.css';

// Make tinymce available globally (before using it!)
window.tinymce = tinymce;

// Then run your custom TinyMCE init script
import './profileDescriptionTinyMCE';

console.log('Hello from Vite!');
