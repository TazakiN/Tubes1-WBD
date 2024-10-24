document.addEventListener("DOMContentLoaded", function () {
  quill.root.innerHTML = window.initialContent || "";

  let addedFiles = new Set();
  const deletedAttachments = new Set();
  const deletedAttachmentsInput = document.getElementById("deletedAttachments");
  const form = document.getElementById("editLowonganForm");

  const uploadArea = document.getElementById("uploadArea");
  const fileInput = document.getElementById("fileInput");
  const filePreviewContainer = document.getElementById("filePreviewContainer");

  // Drag and drop handlers
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

  // Click to upload
  uploadArea.addEventListener("click", () => fileInput.click());
  fileInput.addEventListener("change", (e) => handleFiles(e.target.files));

  // Handle existing file removal
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-file")) {
      const attachmentId = e.target.getAttribute("data-attachment-id");
      if (attachmentId) {
        deletedAttachments.add(attachmentId);
      }
      const previewItem = e.target.closest(".file-preview-item");
      const img = previewItem.querySelector("img");
      if (img && img.src.startsWith("blob:")) {
        URL.revokeObjectURL(img.src); // Clean up blob URL
      }
      previewItem.remove();
    }
  });

  function handleFiles(files) {
    Array.from(files).forEach((file) => {
      if (isValidImage(file)) {
        addedFiles.add(file);
        createPreviewElement(file);
      } else {
        showToast({ error: "Please upload only image files (JPEG, PNG, GIF)" });
      }
    });
  }

  function isValidImage(file) {
    const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
    return allowedTypes.includes(file.type);
  }

  function createPreviewElement(file) {
    const previewItem = document.createElement("div");
    previewItem.className = "file-preview-item";

    // Create image preview
    const img = document.createElement("img");
    img.src = URL.createObjectURL(file);
    previewItem.appendChild(img);

    // Add file name
    const fileName = document.createElement("div");
    fileName.className = "file-name";
    fileName.textContent = file.name;
    previewItem.appendChild(fileName);

    // Add remove button
    const removeBtn = document.createElement("button");
    removeBtn.className = "remove-file";
    removeBtn.textContent = "×";
    previewItem.appendChild(removeBtn);

    filePreviewContainer.appendChild(previewItem);
  }

  form.onsubmit = function (e) {
    e.preventDefault();
    deletedAttachmentsInput.value = Array.from(deletedAttachments).join(",");

    const formData = new FormData(form);

    // Add newly uploaded files
    addedFiles.forEach((file) => {
      formData.append("files[]", file);
    });

    const xhr = new XMLHttpRequest();
    const urlParams = new URLSearchParams(window.location.search);
    const lowonganId = urlParams.get("lowongan_id");
    xhr.open("POST", "/lowongan/edit?lowongan_id=" + lowonganId);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.status === "success") {
            window.location.href = `/lowongan?lowongan_id=${response.id}`;
          } else {
            showToast({
              error:
                response.message || "Terjadi kesalahan saat mengubah lowongan",
            });
          }
        } catch (error) {
          showToast({ error: "Terjadi kesalahan saat mengubah lowongan" });
        }
      }
    };

    xhr.upload.onprogress = function (event) {
      if (event.lengthComputable) {
        const percentComplete = (event.loaded / event.total) * 100;
        showToast({ info: `Upload Progress: ${Math.round(percentComplete)}%` });
      }
    };

    xhr.send(formData);
  };
});
