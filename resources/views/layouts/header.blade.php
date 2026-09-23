<header class="header">
    <div class="header-container">

        @php
            use App\Models\Category;

            $categories = Category::all();
        @endphp

        @include('layouts.header.logo')
        @include('layouts.header.search')
        @include('layouts.header.categories')
        @include('layouts.header.actions')

    </div>

    @include('layouts.header.auth-modal')
    @include('layouts.header.auth-script')
</header>

@include('layouts.header.styles')
