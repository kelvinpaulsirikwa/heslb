<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ReusableNotepad extends Component
{
    public string $name;
    public string $value;
    public array $options;
    public string $id;

    public function __construct(
        string $name = 'content',
        string $value = '',
        array $options = []
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->id = $options['id'] ?? 'notepad-' . uniqid();
        
        $this->options = array_merge([
            'height' => '400px',
            'placeholder' => 'Start typing...',
            'max_file_size' => '5MB',
            'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'spell_check' => true,
        ], $options);
    }

    public function render(): View
    {
        return view('components.reusable-notepad');
    }
}