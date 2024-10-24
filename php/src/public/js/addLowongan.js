document.addEventListener("DOMContentLoaded", function () {
  const uploadArea = document.getElementById("uploadArea");
  const fileInput = document.getElementById("fileInput");
  const filePreviewContainer = document.getElementById("filePreviewContainer");
  const form = document.getElementById("lowonganForm");

  let selectedFiles = new Set();

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
  uploadArea.addEventListener("click", () => {
    fileInput.click();
  });

  fileInput.addEventListener("change", (e) => {
    handleFiles(e.target.files);
  });

  function handleFiles(files) {
    Array.from(files).forEach((file) => {
      if (isValidImage(file)) {
        selectedFiles.add(file);
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
    removeBtn.onclick = (e) => {
      e.stopPropagation();
      selectedFiles.delete(file);
      previewItem.remove();
      URL.revokeObjectURL(img.src); // Clean up the object URL
    };
    previewItem.appendChild(removeBtn);

    filePreviewContainer.appendChild(previewItem);
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(form);

    selectedFiles.forEach((file) => {
      formData.append("files[]", file);
    });

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/lowongan/add", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        try {
          const response = JSON.parse(xhr.responseText);
          if (response.status === "success") {
            window.location.href = `/lowongan?lowongan_id=${response.id}`;
          } else {
            showToast({
              error:
                response.message || "Terjadi kesalahan saat membuat lowongan",
            });
          }
        } catch (error) {
          showToast({ error: "Terjadi kesalahan saat membuat lowongan" });
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
  });
});
