@props(['sortBy','sortAsc','sortField'])

@if( $sortBy == "$sortField")
    @if( !$sortAsc)
    <span  wire:click="sortBy('id')">
        <img src="{{ secure_asset('assets/img/ic_arriba.png') }}" title="Ascendete">
    </span>
    @endif
    @if( $sortAsc)
    <span  wire:click="sortBy('id')">
        <img src="{{ secure_asset('assets/img/ic_abajo.png') }}" title="Descendente">
    </span>
    @endif
@endif