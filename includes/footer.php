</div><!-- tutup wrapper -->

<footer class="footer">
    <div class="footer-content">
        <div class="footer-brand">
            <img src="/project_perpustakaan/assets/img/logo.png" alt="Logo" style="height:35px; width:auto;">
            <strong style="color:white;">Perpustakaan Digital</strong>
        </div>
        <p class="footer-sub">© 2026 Perpustakaan Digital — Made by Aril. All rights reserved.</p>
    </div>
</footer>

<script>
const toggle = document.getElementById('darkToggle');
const html   = document.documentElement;

if (localStorage.getItem('theme') === 'dark') {
    html.setAttribute('data-theme', 'dark');
    if (toggle) toggle.textContent = '☀️';
}

if (toggle) {
    toggle.addEventListener('click', () => {
        const isDark = html.getAttribute('data-theme') === 'dark';
        html.setAttribute('data-theme', isDark ? 'light' : 'dark');
        toggle.textContent = isDark ? '🌙' : '☀️';
        localStorage.setItem('theme', isDark ? 'light' : 'dark');
    });
}

function isDarkMode() {
    return document.documentElement.getAttribute('data-theme') === 'dark';
}

function konfirmasiAksi(event, url, pesan, tombolTeks = 'Ya, Lanjutkan!') {
    event.preventDefault();
    Swal.fire({
        title: 'Konfirmasi',
        text: pesan,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#c9922a',
        cancelButtonColor: '#e74c3c',
        confirmButtonText: tombolTeks,
        cancelButtonText: 'Batal',
        background: isDarkMode() ? '#16213e' : '#ffffff',
        color: isDarkMode() ? '#e8e0d5' : '#2c2416'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

function konfirmasiLogout(event) {
    konfirmasiAksi(event, '/project_perpustakaan/logout.php', 'Apakah kamu yakin ingin keluar dari sistem?', 'Ya, Logout');
}
</script>

</body>
</html>