<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;

class FooterComponent extends Component
{
    public $publicationlist;
    public $showPublications;

    /**
     * Create a new component instance.
     *
     * @param bool $showPublications
     */
    public function __construct($showPublications = false)
    {
        $this->showPublications = $showPublications;
        $this->publicationlist = $showPublications ? $this->getPublicationList() : [];
    }

    /**
     * Get publication list from database
     */
    private function getPublicationList()
    {
        return Category::active()
            ->ordered()
            ->get()
            ->map(function ($category) {
                // Truncate long category names to prevent layout issues
                $name = $category->name;
                if (strlen($name) > 50) {
                    $name = substr($name, 0, 47) . '...';
                }
                
                return [
                    'id' => $category->id,
                    'name' => $name,
                    'slug' => $category->slug,
                    'display_order' => $category->display_order,
                ];
            })
            ->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.footercomponent');
    }
}