<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Minicard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $size, $title, $content, $classes, $alignFooter;

    public function __construct($title, $content = null, $size = 'sm', $alignFooter = 'justify-end')
    {

        switch ($size) {
            case 'md':
                $classSize = 'w-28 h-28';
                break;
            case 'lg':
                $classSize = 'w-32 h-32';
                break;
            case 'xl':
                $classSize = 'w-36 h-36';
                break;
            default:
                $classSize = 'w-24 h-24';
        }
        $this->title = $title;
        $this->content = $content;
        $this->alignFooter = $alignFooter;
        $this->classes = $classSize . ' inline-flex flex-col items-center justify-between relative bg-fondominicard text-colorlinknav shadow shadow-shadowminicard p-1 rounded-xl hover:shadow-md hover:shadow-shadowminicard transition-shadow ease-out duration-150';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.minicard');
    }
}
