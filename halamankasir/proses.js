function toggleDropdown() {
  document.getElementById("dropdownMenu").classList.toggle("show");
}

// Tutup dropdown kalau klik di luar
window.onclick = function(e) {
  if (!e.target.matches('.dropdown-btn')) {
    let dropdowns = document.getElementsByClassName("dropdown-content");
    for (let d of dropdowns) {
      d.classList.remove('show');
    }
  }
}

function showprofil() {
  const backdrop = document.getElementById('profilbackdrop');
  backdrop.classList.add('show');
  backdrop.setAttribute('aria-hidden', 'false');
}

function closeprofil() {
  const backdrop = document.getElementById('profilbackdrop');
  backdrop.classList.remove('show');
  backdrop.setAttribute('aria-hidden', 'true');
}

function logout() {
  if (confirm("Apakah Anda yakin ingin logout?")) {
    window.location.href = "../login.php";
  }
}

// =======================
// 🔍 FITUR SEARCH PRODUK (klik tombol, hasil rapat ke kiri atas)
// =======================
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".search-produk form");
  const input = form.querySelector('input[name="query"]');
  const produkCards = document.querySelectorAll(".produk .card");

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // cegah reload halaman

    const query = input.value.toLowerCase();

    produkCards.forEach(card => {
      const namaProduk = card.querySelector("h3").textContent.toLowerCase();
      const hargaProduk = card.querySelector("p").textContent.toLowerCase();

      // tampilkan semua jika input kosong
      if (query === "") {
        card.style.display = "flex";
        card.style.opacity = "1";
        card.style.transform = "scale(1)";
      }
      // tampilkan hanya yang cocok
      else if (namaProduk.includes(query) || hargaProduk.includes(query)) {
        card.style.display = "flex";
        card.style.opacity = "1";
        card.style.transform = "scale(1)";
      } 
      // sembunyikan yang tidak cocok
      else {
        card.style.display = "none";
      }
    });
  });
});

function konfirmasiBeli() {
  const yakin = confirm("Apakah Anda yakin ingin melanjutkan ke pembayaran dan mencetak struk?");
  if (yakin) {
    window.location.href = 'struk.php';
  } else {
    alert("Transaksi dibatalkan.");
  }
}
