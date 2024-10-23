// toast.js
const showToast = (data) => {
  // Remove existing toast panel if any
  const existingPanel = document.querySelector(".toast-panel");
  if (existingPanel) {
    existingPanel.remove();
  }

  // Create new panel
  const panel = document.createElement("div");
  panel.className = "toast-panel";

  const createToastHTML = (type, message) => {
    const toast = document.createElement("div");
    toast.className = `toast-item ${type}`;

    // Set initial styles using a class instead of inline styles
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

  // Create toasts based on data
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

    // Force a reflow before adding the show class
    panel.offsetHeight;

    // Add show class to trigger animation
    const toasts = panel.querySelectorAll(".toast-item");
    toasts.forEach((toast, index) => {
      toast.classList.remove("toast-initial");
      toast.classList.add("toast-show");

      // Auto-remove toast after delay
      setTimeout(() => {
        if (toast.isConnected) {
          // Check if toast is still in DOM
          toast.classList.add("toast-hiding");
          setTimeout(() => {
            if (toast.isConnected) {
              // Check again before removal
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

// Close toast on click outside
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
