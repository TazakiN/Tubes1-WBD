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
      if (isValidFile(file)) {
        selectedFiles.add(file);
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

    // preview
    if (file.type.startsWith("image/")) {
      const img = document.createElement("img");
      img.src = URL.createObjectURL(file);
      previewItem.appendChild(img);
    } else {
      const icon = document.createElement("div");
      icon.className = "file-icon";
      icon.textContent = getFileIcon(file.type);
      icon.style.fontSize = "48px";
      icon.style.textAlign = "center";
      previewItem.appendChild(icon);
    }

    // file name
    const fileName = document.createElement("div");
    fileName.className = "file-name";
    fileName.textContent = file.name;
    previewItem.appendChild(fileName);

    // remove button
    const removeBtn = document.createElement("button");
    removeBtn.className = "remove-file";
    removeBtn.textContent = "×";
    removeBtn.onclick = (e) => {
      e.stopPropagation();
      selectedFiles.delete(file);
      previewItem.remove();
    };
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
        if (xhr.status === 200) {
          try {
            console.log("Response:", xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            console.log("Success:", response);

            window.location.href = `/lowongan?lowongan_id=${response.id}`;
          } catch (error) {
            console.error("Error parsing response:", error);
          }
        } else {
          console.error("Error:", xhr.status, xhr.statusText);
        }
      }
    };

    xhr.upload.onprogress = function (event) {
      if (event.lengthComputable) {
        const percentComplete = (event.loaded / event.total) * 100;
        console.log(`Upload progress: ${percentComplete}%`);
      }
    };

    xhr.send(formData);
  });
});
