@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
    <div class="search-filter">
        <div class="search-bar">
            <i class="material-icons">search</i>
            <input type="text" id="searchInput" placeholder="Search for food..." />
        </div>

        <div class="filter-buttons">
            <button class="filter-btn active" data-category="all">All</button>
            <button class="filter-btn" data-category="Western">Western</button>
            <button class="filter-btn" data-category="Chinese">Chinese</button>
            <button class="filter-btn" data-category="Japanese">Japanese</button>
            <button class="filter-btn" data-category="Filipino">Filipino</button>
            <button class="filter-btn" data-category="Desserts">Desserts</button>
        </div>
    </div>

    <div class="food-grid" id="foodGrid">
        <p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #888;">
            Loading menu...
        </p>
    </div>
@endsection

{{-- Push the Menu Modal to the layout --}}
@push('modals')
    @include('menu-details')
@endpush

{{-- Push the JS to the layout --}}
@push('scripts')
    <script src="{{ asset('js/user-dashboard.js') }}"></script>
@endpush