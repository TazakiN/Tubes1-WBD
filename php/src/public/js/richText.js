var quill = new Quill("#editor", {
  theme: "snow",
});

const textarea = document.querySelector("#quillTextArea");
const form =
  document.querySelector("#registerForm") ??
  document.querySelector("#updateForm") ??
  document.querySelector("#lowonganForm");
form.addEventListener("submit", (e) => {
  // will still trigger basic form submission and textarea value in formdata will be updated, see network inspect after submit
  textarea.value = quill.root.innerHTML;
});
