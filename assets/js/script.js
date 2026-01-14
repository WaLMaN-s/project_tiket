// Smooth Scrolling untuk navigasi
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll untuk anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if(href !== '#' && href !== '#0') {
                e.preventDefault();
                const target = document.querySelector(href);
                
                if(target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // Konfirmasi sebelum logout
    const logoutLinks = document.querySelectorAll('a[href*="logout.php"]');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if(!confirm('Apakah Anda yakin ingin keluar?')) {
                e.preventDefault();
            }
        });
    });

    // Format input rupiah
    const inputRupiah = document.querySelectorAll('input[type="number"][name="harga"]');
    inputRupiah.forEach(input => {
        input.addEventListener('blur', function() {
            const value = this.value;
            if(value) {
                console.log('Harga: Rp ' + parseInt(value).toLocaleString('id-ID'));
            }
        });
    });

    // Validasi form pesanan
    const formPesan = document.getElementById('formPesan');
    if(formPesan) {
        formPesan.addEventListener('submit', function(e) {
            const jumlahTiket = document.querySelector('input[name="jumlah_tiket"]');
            const maxTiket = parseInt(jumlahTiket.getAttribute('max'));
            
            if(parseInt(jumlahTiket.value) > maxTiket) {
                e.preventDefault();
                alert('Jumlah tiket melebihi stok yang tersedia!');
                return false;
            }
            
            if(parseInt(jumlahTiket.value) < 1) {
                e.preventDefault();
                alert('Minimal pembelian 1 tiket!');
                return false;
            }
            
            return confirm('Konfirmasi pemesanan tiket ini?');
        });
    }

    // Animasi card on hover
    const cards = document.querySelectorAll('.ticket-card, .card-custom');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Loading animation untuk form submit
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if(submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                submitBtn.disabled = true;
            }
        });
    });

    // Real-time search untuk tabel (opsional)
    const searchInput = document.getElementById('searchTable');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if(text.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Show password toggle (jika ada)
    const passwordToggles = document.querySelectorAll('.toggle-password');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if(input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    });

    // Countdown timer untuk event (opsional)
    const eventDate = new Date('2025-11-29T19:30:00');
    const countdownElement = document.getElementById('countdown');
    
    if(countdownElement) {
        setInterval(() => {
            const now = new Date().getTime();
            const distance = eventDate - now;
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            
            if(distance < 0) {
                countdownElement.innerHTML = 'Event Started!';
            }
        }, 1000);
    }
});

// Fungsi helper format rupiah
function formatRupiah(angka, prefix = 'Rp ') {
    const number_string = angka.toString().replace(/[^,\d]/g, '');
    const split = number_string.split(',');
    const sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    const ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
    if(ribuan) {
        const separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    
    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix + rupiah;
}

// Fungsi konfirmasi hapus
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return confirm(message);
}


// Console log untuk debugging (remove in production)
console.log('PENTAS.HUB Website Loaded Successfully!');
console.log('Event Date: 29 November 2025');
console.log('Venue: GBK Senayan, Jakarta');

