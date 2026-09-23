<script>
    function openAuthModal(type) {

        const modal = document.getElementById('authModal');

        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const profileForm = document.getElementById('profileForm');

        // Mở modal
        modal.style.display = 'flex';

        // Ẩn TẤT CẢ form trước
        loginForm.style.display = 'none';
        registerForm.style.display = 'none';

        if (profileForm) {
            profileForm.style.display = 'none';
        }

        // Sau đó chỉ hiện form được chọn
        if (type === 'login') {
            loginForm.style.display = 'block';
        }

        if (type === 'register') {
            registerForm.style.display = 'block';
        }

        if (type === 'profile') {
            profileForm.style.display = 'block';
        }
    }

    function closeAuthModal() {
        document.getElementById('authModal').style.display = 'none';
    }

    // Bấm ra ngoài hộp cũng đóng Modal
    document.getElementById('authModal').addEventListener('click', function(event) {

        if (event.target === this) {
            closeAuthModal();
        }

    });
</script>
