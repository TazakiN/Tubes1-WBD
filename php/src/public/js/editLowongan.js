document.addEventListener("DOMContentLoaded", function () {
  quill.root.innerHTML = window.initialContent || "";

  let addedFiles = new Set();
  const deletedAttachments = new Set();
  const deletedAttachmentsInput = document.getElementById("deletedAttachments");
  const form = document.getElementById("editLowonganForm");

  const uploadArea = document.getElementById("uploadArea");
  const fileInput = document.getElementById("fileInput");
  const filePreviewContainer = document.getElementById("filePreviewContainer");

  uploadArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadArea.classList.add("dragover");
  });

  uploadArea.addEventListener("dragleave", () => {
    uploadArea.classList.remove("dragover");
  });

  uploadArea.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadArea.classList.remove("dragover");
    handleFiles(e.dataTransfer.files);
  });

  uploadArea.addEventListener("click", () => fileInput.click());
  fileInput.addEventListener("change", (e) => handleFiles(e.target.files));

  // Handle file removal
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-file")) {
      const attachmentId = e.target.getAttribute("data-attachment-id");
      if (attachmentId) deletedAttachments.add(attachmentId);
      e.target.closest(".file-preview-item").remove();
    }
  });

  function handleFiles(files) {
    Array.from(files).forEach((file) => {
      if (isValidFile(file)) {
        addedFiles.add(file);
        createPreviewElement(file);
      }
    });
  }

  function isValidFile(file) {
    const allowedTypes = [
      "image/jpeg",
      "image/png",
      "image/gif",
      "application/pdf",
      "application/msword",
      "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    ];
    return allowedTypes.includes(file.type);
  }

  function createPreviewElement(file) {
    const previewItem = document.createElement("div");
    previewItem.className = "file-preview-item";

    if (file.type.startsWith("image/")) {
      const img = document.createElement("img");
      img.src = URL.createObjectURL(file);
      previewItem.appendChild(img);
    } else {
      const icon = document.createElement("div");
      icon.className = "file-icon";
      icon.textContent = getFileIcon(file.type);
      previewItem.appendChild(icon);
    }

    const fileName = document.createElement("div");
    fileName.className = "file-name";
    fileName.textContent = file.name;
    previewItem.appendChild(fileName);

    const removeBtn = document.createElement("button");
    removeBtn.className = "remove-file";
    removeBtn.textContent = "×";
    previewItem.appendChild(removeBtn);

    filePreviewContainer.appendChild(previewItem);
  }

  function getFileIcon(fileType) {
    switch (fileType) {
      case "application/pdf":
        return "📄";
      case "application/msword":
      case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
        return "📝";
      default:
        return "📎";
    }
  }

  form.onsubmit = function (e) {
    e.preventDefault();
    deletedAttachmentsInput.value = Array.from(deletedAttachments).join(",");

    const formData = new FormData(form);

    // Tambahkan file yang di-upload
    addedFiles.forEach((file) => {
      formData.append("files[]", file);
    });

    const xhr = new XMLHttpRequest();
    const urlParams = new URLSearchParams(window.location.search);
    const lowonganId = urlParams.get("lowongan_id");
    xhr.open("POST", "/lowongan/edit?lowongan_id=" + lowonganId);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          try {
            const response = JSON.parse(xhr.responseText);
            console.log("Response:", response);
            if (response.id) {
              window.location.href = `/lowongan?lowongan_id=${response.id}`;
            } else {
              alert("Error updating vacancy: " + response.message);
            }
          } catch (error) {
            console.error("Error parsing response:", error);
            alert("Error updating vacancy.", error);
          }
        } else {
          alert(`Error: ${xhr.status} ${xhr.statusText}`);
        }
      }
    };

    xhr.send(formData);
  };
});
