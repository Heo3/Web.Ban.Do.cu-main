        <div class="categories">
            <a href="{{ route('home') }}" class="category-item {{ !request('category') ? 'active' : '' }}">
                <div class="cat-icon-wrap">
                    <i class="fa-solid fa-layer-group" style="font-size: 24px; color: var(--orange);"></i>
                </div>
                <span>Tất cả</span>
            </a>

            @foreach($categories as $category)
                <a href="{{ route('home', ['category' => $category->slug]) }}" 
                   class="category-item {{ request('category') == $category->slug ? 'active' : '' }}">
                    <img
                        src="{{ $category->image }}"
                        alt="{{ $category->name }}"
                        loading="lazy"
                    >
                    <span>{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
