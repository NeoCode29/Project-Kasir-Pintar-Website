<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormHeader extends Component
{
    //atribute
    public $judul;
    public $deskripsi;

    public function __construct($judul,$deskripsi)
    {
        $this->judul = $judul;
        $this->deskripsi = $deskripsi;
    }

    public function render(): View|Closure|string
    {
        return view('components.form-header');
    }
}
