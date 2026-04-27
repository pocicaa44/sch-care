/* ─── LIGHTBOX ────────────────────────────── */
const photos = Array.from(document.querySelectorAll(".photo-thumb img")).map(
    (img) => img.src,
);
let currentIndex = 0;

function openLightbox(index) {
    currentIndex = index;
    updateLightbox();
    document.getElementById("lightbox").classList.add("d-flex");
    document.getElementById("lightbox").classList.remove("d-none");
    document.body.style.overflow = "hidden";
}

function closeLightbox() {
    document.getElementById("lightbox").classList.remove("d-flex");
    document.getElementById("lightbox").classList.add("d-none");
    document.body.style.overflow = "";
}

function closeLightboxOnBg(e) {
    if (e.target.id === "lightbox") closeLightbox();
}

function lightboxNav(dir) {
    currentIndex = (currentIndex + dir + photos.length) % photos.length;
    updateLightbox();
}

function updateLightbox() {
    document.getElementById("lightboxImg").src = photos[currentIndex];
    document.getElementById("lightboxCounter").textContent =`${currentIndex + 1} / ${photos.length}`;
}

// Keyboard navigation
document.addEventListener("keydown", (e) => {
    if (!document.getElementById("lightbox").classList.contains("open")) return;
    if (e.key === "ArrowRight") lightboxNav(1);
    if (e.key === "ArrowLeft") lightboxNav(-1);
    if (e.key === "Escape") closeLightbox();
});

/* ─── HAPUS ───────────────────────────────── */
function confirmHapus() {
    if (
        confirm(
            "Yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.",
        )
    ) {
        alert("Laporan dihapus. (simulasi)");
    }
}

/* ─── OFFCANVAS NAV ───────────────────────── */
document
    .getElementById("mobileNav")
    ?.querySelectorAll(".nav-link:not(.logout)")
    .forEach((link) => {
        link.addEventListener("click", function (e) {
            const instance = bootstrap.Offcanvas.getInstance(
                document.getElementById("mobileNav"),
            );
            if (instance) {
                e.preventDefault();
                const href = this.getAttribute("href");
                instance.hide();
                document.getElementById("mobileNav").addEventListener(
                    "hidden.bs.offcanvas",
                    () => {
                        window.location.href = href;
                    },
                    { once: true },
                );
            }
        });
    });
