<?php

namespace App\View\Components\Layouts\App;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Free extends Component
{
    public string $title;

    public string $header;

    public function __construct(string $title = '', string $header = '')
    {
        $this->title = $title;
        $this->header = $header;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.app.free');
    }
}
