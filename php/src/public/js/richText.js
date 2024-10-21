var quill = new Quill("#editor", {
  placeholder: "Describe your company...",
  theme: "snow",
});

const textarea = document.querySelector("#about");
const form = document.querySelector("#registerForm");
form.addEventListener("submit", (e) => {
  // will still trigger basic form submission and textarea value in formdata will be updated, see network inspect after submit
  textarea.value = quill.root.innerHTML;
});
