const editProfileBtn = document.getElementById("editProfileBtn");
const editProfileModal = document.getElementById("editProfileModal");
const closeModal = document.getElementById("closeModal");
const editForm = document.getElementById("editForm");

editProfileBtn.addEventListener("click", () => {
  editProfileModal.classList.remove("hidden");
});

closeModal.addEventListener("click", () => {
  editProfileModal.classList.add("hidden");
});

window.addEventListener("click", (e) => {
  if (e.target === editProfileModal) {
    editProfileModal.classList.add("hidden");
  }
});

editForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const formData = new FormData(editForm);
  const data = {
    nama: formData.get("nama").trim(),
    email: formData.get("email").trim(),
  };

  const xhr = new XMLHttpRequest();
  xhr.open("PATCH", "/profile", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  // Handle response
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        console.log(xhr.responseText);
        const result = JSON.parse(xhr.responseText);

        document.getElementById("displayNama").innerText = result.nama;
        document.getElementById("displayEmail").innerText = result.email;

        editProfileModal.classList.add("hidden");
      } else {
        alert(xhr.responseText);
      }
    }
  };
  xhr.send(JSON.stringify(data));
});
