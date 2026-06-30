document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('live-search-input');
    const searchResults = document.getElementById('search-results');
    
    // Nếu trang nào không có thanh tìm kiếm (ví dụ trang admin) thì bỏ qua để không báo lỗi
    if (!searchInput) return;

    let searchTimeout;

    // Lấy trạng thái đăng nhập từ thẻ HTML (đã được PHP in ra sẵn)
    const isLoggedIn = searchInput.getAttribute('data-logged-in') === 'true';

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        let query = this.value.trim();

        if (query.length === 0) {
            searchResults.style.display = 'none';
            return;
        }

        // Delay 300ms để tránh spam server khi gõ nhanh
        searchTimeout = setTimeout(() => {
            fetch('ajax_search.php?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                searchResults.innerHTML = '';
                
                if (data.length > 0) {
                    data.forEach(item => {
                        let a = document.createElement('a');
                        a.className = 'dropdown-item d-flex align-items-center gap-3 py-2 border-bottom border-secondary';
                        
                        // Xử lý điều hướng dựa trên biến isLoggedIn
                        if (isLoggedIn) {
                            a.href = '?page=detail&id=' + item.id;
                        } else {
                            a.href = '#';
                            a.setAttribute('data-bs-toggle', 'modal');
                            a.setAttribute('data-bs-target', '#loginModal');
                        }

                        a.innerHTML = `
                            <img src="images/${item.image}" alt="img" style="width: 45px; height: 45px; object-fit: contain; background: #fff; border-radius: 4px; padding: 2px;">
                            <div class="text-truncate" style="flex: 1;">
                                <div class="text-white fw-bold text-truncate" style="font-size: 13px;">${item.name}</div>
                                <div class="text-danger fw-bold" style="font-size: 12px;">${new Intl.NumberFormat('vi-VN').format(item.price)} đ</div>
                            </div>
                        `;
                        searchResults.appendChild(a);
                    });
                } else {
                    searchResults.innerHTML = '<div class="dropdown-item text-light small py-3 text-center">Không tìm thấy sản phẩm nào...</div>';
                }
                searchResults.style.display = 'block';
            })
            .catch(err => console.error(err));
        }, 300);
    });

    // Tắt hộp kết quả khi click ra ngoài vùng tìm kiếm
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});