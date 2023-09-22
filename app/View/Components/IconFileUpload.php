<?php

namespace App\View\Components;

use Illuminate\View\Component;

class IconFileUpload extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $type, $text, $uploadname;

    public function __construct($type = 'image', $text = null, $uploadname = null)
    {
        $this->type = $type;
        $this->text = $text;
        $this->uploadname = $uploadname;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.icon-file-upload');
    }
}
