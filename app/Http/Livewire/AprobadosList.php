<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AlpOrdenes;
use Livewire\WithPagination;

class AprobadosList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search ="";

    public $cantid = 10;

    public $sortBy = 'id';
    public $sortAsc = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true]
    ];

    public function render()
    {
        $aprobados = AlpOrdenes::when($this->search, function($query){
            return $query->where(function ($query){
                $query->where('referencia','like','%'.$this->search.'%')
                ->orWhere('id_cliente','like','%'.$this->search.'%');
                });
        })
        ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC');

        $query = $aprobados->toSql();
        $aprobados = $aprobados->paginate($this->cantid);

        return view('livewire.aprobados-list',[
            'aprobados' => $aprobados,
            'query' => $query
        ]);


    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy( $field)
    {
        if($field == $this->sortBy){
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }
}
