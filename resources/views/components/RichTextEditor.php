<?php
// STEP 1: Create the Component Class
// Run: php artisan make:component RichTextEditor
// Then replace the content with this:

// app/View/Components/RichTextEditor.php

namespace App\View\Components;

use Illuminate\View\Component;

class RichTextEditor extends Component
{
    public $name;
    public $value;
    public $placeholder;
    public $height;
    public $imageUpload;
    public $maxWidth;

    public function __construct(
        $name = 'content',
        $value = '',
        $placeholder = 'Start writing...',
        $height = '400px',
        $imageUpload = true,
        $maxWidth = '100%'
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->height = $height;
        $this->imageUpload = $imageUpload;
        $this->maxWidth = $maxWidth;
    }

    public function render()
    {
        return view('components.rich-text-editor');
    }
}
?>

