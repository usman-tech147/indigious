<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class video extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $package;
    public $user;

    public function __construct($package)
    {
        $this->package=$package;
        $this->user=User::find(\auth()->guard('user')->user()->id);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.video');
    }
}
