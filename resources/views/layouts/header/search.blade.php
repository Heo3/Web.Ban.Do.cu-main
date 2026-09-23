<!-- Thanh tìm kiếm -->
<form action="{{ route('home') }}" method="GET" class="search-box">
  @if(request('category'))
    <input type="hidden" name="category" value="{{ request('category') }}">
  @endif

  <!-- Chọn tỉnh: JS sẽ đổ <option> từ API vào đây -->
  <div class="province-select">
    <span class="pin-icon"><i class="fa-solid fa-location-dot"></i></span>
    <select id="provinceSelect" name="province" aria-label="Chọn tỉnh" onchange="this.form.submit()">
      <option value="">Toàn quốc</option>
      @if(request('province'))
        <option value="{{ request('province') }}" selected>{{ request('province') }}</option>
      @endif
    </select>
  </div>

  <input 
    type="text" 
    name="search" 
    id="searchInput" 
    placeholder="Tìm kiếm sản phẩm trên Chợ Tốt..." 
    value="{{ request('search') }}"
    autocomplete="off"
  >

  <button type="submit" id="searchBtn" aria-label="Tìm kiếm">
    <i class="fa-solid fa-magnifying-glass"></i>
  </button>
</form>

<script>
  // API danh sách tỉnh/thành phố Việt Nam
  const PROVINCES_API = 'https://provinces.open-api.vn/api/p/';
  const CURRENT_PROVINCE = @json(request('province'));

  async function loadProvinces() {
    const select = document.getElementById('provinceSelect');
    if (!select) return;

    try {
      const res = await fetch(PROVINCES_API);
      if (!res.ok) throw new Error('Không thể tải danh sách tỉnh');

      const provinces = await res.json();
      provinces.sort((a, b) => a.name.localeCompare(b.name, 'vi'));

      // Xóa các option phụ ngoại trừ Toàn quốc
      select.innerHTML = '<option value="">Toàn quốc</option>';

      const fragment = document.createDocumentFragment();
      provinces.forEach(p => {
        const opt = document.createElement('option');
        // Lưu tên tỉnh để tìm kiếm chính xác
        const cleanName = p.name.replace(/^(Thành phố|Tỉnh)\s+/i, '');
        opt.value = p.name;
        opt.textContent = p.name;
        if (CURRENT_PROVINCE && (CURRENT_PROVINCE === p.name || CURRENT_PROVINCE.includes(cleanName))) {
          opt.selected = true;
        }
        fragment.appendChild(opt);
      });

      select.appendChild(fragment);
    } catch (err) {
      console.warn('Lỗi tải danh sách tỉnh từ API:', err);
    }
  }

  document.addEventListener('DOMContentLoaded', loadProvinces);
</script>