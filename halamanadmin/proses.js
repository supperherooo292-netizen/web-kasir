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

function showtambah() {
  const backdrop = document.getElementById('tambahbackdrop');
  backdrop.classList.add('show');
  backdrop.setAttribute('aria-hidden', 'false');
}

function closetambah() {
  const backdrop = document.getElementById('tambahbackdrop');
  backdrop.classList.remove('show');
  backdrop.setAttribute('aria-hidden', 'true');
}


document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".search-produk form");

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const query = form.querySelector("input[name='query']").value.trim();
      if (query === "") {
        alert("Masukkan kata kunci pencarian!");
        return;
      }

      // Kirim permintaan ke search.php pakai AJAX
      fetch(`proses.php?query=${encodeURIComponent(query)}`)
        .then(response => response.text())
        .then(data => {
          // Misalnya hasil pencarian dikembalikan dalam bentuk tabel
          const tableContainer = document.querySelector(".produk table");
          tableContainer.innerHTML = data;
        })
        .catch(error => {
          console.error("Terjadi kesalahan:", error);
        });
    });
  }
});

