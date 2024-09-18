<?php

namespace App\View\Components\document;

use App\Models\Document;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class show extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Document $document, public $isUserInRoute, public $userCanEdit, public $routeIsFinished)
    {
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.document.show');
    }
}
