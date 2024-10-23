function loadPage(page) {
  const url = new URL(window.location.href);
  url.searchParams.set("page", page);
  window.location.href = url.toString();
}

document.querySelectorAll(".pagination button").forEach((button) => {
  button.addEventListener("click", () => {
    if (button.disabled || !button.dataset.page) {
      return;
    }

    const page = button.dataset.page;
    loadPage(page);
  });
});
