const showToast = (data) => {
  const existingPanel = document.querySelector(".toast-panel");
  if (existingPanel) {
    existingPanel.remove();
  }

  const panel = document.createElement("div");
  panel.className = "toast-panel";

  const createToastHTML = (type, message) => {
    const toast = document.createElement("div");
    toast.className = `toast-item ${type}`;

    toast.classList.add("toast-initial");

    toast.innerHTML = `
          <div class="toast ${type}">
            <label class="close"></label>
            <h3>${type.charAt(0).toUpperCase() + type.slice(1)}!</h3>
            <p>${message}</p>
          </div>
      `;

    toast.querySelector(".close").addEventListener("click", () => {
      toast.classList.add("toast-hiding");
      setTimeout(() => {
        toast.remove();
        if (panel.children.length === 0) {
          panel.remove();
        }
      }, 300);
    });

    return toast;
  };

  const toastTypes = ["help", "success", "warning", "error"];

  toastTypes.forEach((type) => {
    if (data[type]) {
      const message =
        typeof data[type] === "string"
          ? data[type]
          : JSON.stringify(data[type]);
      panel.appendChild(createToastHTML(type, message));
    }
  });

  if (panel.children.length > 0) {
    document.body.appendChild(panel);

    panel.offsetHeight;

    const toasts = panel.querySelectorAll(".toast-item");
    toasts.forEach((toast, index) => {
      toast.classList.remove("toast-initial");
      toast.classList.add("toast-show");

      setTimeout(() => {
        if (toast.isConnected) {
          toast.classList.add("toast-hiding");
          setTimeout(() => {
            if (toast.isConnected) {
              toast.remove();
              if (panel.children.length === 0) {
                panel.remove();
              }
            }
          }, 300);
        }
      }, 4000 + index * 500);
    });
  }
};

document.addEventListener("click", (e) => {
  if (e.target.matches(".close")) {
    const toastItem = e.target.closest(".toast-item");
    if (toastItem) {
      toastItem.classList.add("toast-hiding");
      setTimeout(() => {
        toastItem.remove();
        const panel = document.querySelector(".toast-panel");
        if (panel && panel.children.length === 0) {
          panel.remove();
        }
      }, 300);
    }
  }
});
