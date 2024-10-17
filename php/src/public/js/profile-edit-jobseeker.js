// Select elements
const editProfileBtn = document.getElementById("editProfileBtn");
const editProfileModal = document.getElementById("editProfileModal");
const closeModal = document.getElementById("closeModal");
const editForm = document.getElementById("editForm");

// Open the modal when the edit button is clicked
editProfileBtn.addEventListener("click", () => {
  editProfileModal.classList.remove("hidden");
});

// Close the modal when the close button is clicked
closeModal.addEventListener("click", () => {
  editProfileModal.classList.add("hidden");
});

// Close the modal when clicking outside of the modal content
window.addEventListener("click", (e) => {
  if (e.target === editProfileModal) {
    editProfileModal.classList.add("hidden");
  }
});

// Handle form submission with AJAX using XMLHttpRequest
editForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const formData = new FormData(editForm);
  const data = {
    name: formData.get("name"),
    email: formData.get("email"),
  };

  const xhr = new XMLHttpRequest();
  xhr.open("PATCH", "/update-profile", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  // Handle response
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        const result = JSON.parse(xhr.responseText);

        document.getElementById("displayName").innerText = result.name;
        document.getElementById("displayEmail").innerText = result.email;

        editProfileModal.classList.add("hidden");
      } else {
        alert("Failed to update profile. Please try again.");
      }
    }
  };
  xhr.send(JSON.stringify(data));
});
