<?php

namespace App\View\Components;

use Illuminate\View\Component;

class dataTable extends Component
{
    public $table;
    public $statusUrl;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($table, $statusUrl)
    {
        $this->table = $table;
        $this->statusUrl = $statusUrl;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.data-table');
    }
}
