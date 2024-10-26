document.addEventListener("DOMContentLoaded", function () {
  const deleteButtons = document.querySelectorAll(".delete-btn");

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const lowonganId = this.getAttribute("data-id");
      const row = this.closest("tr") || this.closest(".position-card");

      if (confirm("Are you sure you want to delete this position?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("DELETE", "/lowongan/delete?lowongan_id=" + lowonganId, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onload = function () {
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
              window.location.reload();
            } else {
              showToast({
                error:
                  response.message ||
                  "Terjadi kesalahan saat menghapus lowongan",
              });
            }
          } catch (e) {
            showToast({
              error: "Terjadi kesalahan saat memproses respons server",
            });
          }
        };

        xhr.onerror = function () {
          showToast({
            error: "Terjadi kesalahan koneksi. Silakan coba lagi.",
          });
        };

        const payload = JSON.stringify({ lowongan_id: lowonganId });
        xhr.send(payload);
      }
    });
  });
});
