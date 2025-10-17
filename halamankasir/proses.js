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