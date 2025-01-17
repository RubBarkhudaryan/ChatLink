const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");

let img = new Image();
let fileName = "";

const downloadBtn = document.getElementById("download-btn");
const uploadFile = document.getElementById("upload-file");
const revertBtn = document.getElementById("revert-btn");
const uploadBtn = document.getElementById("upload-btn");

const canvasWidth = 500;
const canvasHeight = 350;
canvas.width = canvasWidth;
canvas.height = canvasHeight;

// Filter & Effect Handlers
document.addEventListener("click", e => {
  if (e.target.classList.contains("filter-btn")) {
    if (e.target.classList.contains("brightness-add")) {
      Caman("#canvas", img, function () {
        this.brightness(5).render();
      });
    } else if (e.target.classList.contains("brightness-remove")) {
      Caman("#canvas", img, function () {
        this.brightness(-5).render();
      });
    } else if (e.target.classList.contains("contrast-add")) {
      Caman("#canvas", img, function () {
        this.contrast(5).render();
      });
    } else if (e.target.classList.contains("contrast-remove")) {
      Caman("#canvas", img, function () {
        this.contrast(-5).render();
      });
    } else if (e.target.classList.contains("saturation-add")) {
      Caman("#canvas", img, function () {
        this.saturation(5).render();
      });
    } else if (e.target.classList.contains("saturation-remove")) {
      Caman("#canvas", img, function () {
        this.saturation(-5).render();
      });
    } else if (e.target.classList.contains("vibrance-add")) {
      Caman("#canvas", img, function () {
        this.vibrance(5).render();
      });
    } else if (e.target.classList.contains("vibrance-remove")) {
      Caman("#canvas", img, function () {
        this.vibrance(-5).render();
      });
    } else if (e.target.classList.contains("vintage-add")) {
      Caman("#canvas", img, function () {
        this.vintage().render();
      });
    } else if (e.target.classList.contains("lomo-add")) {
      Caman("#canvas", img, function () {
        this.lomo().render();
      });
    } else if (e.target.classList.contains("clarity-add")) {
      Caman("#canvas", img, function () {
        this.clarity().render();
      });
    } else if (e.target.classList.contains("sincity-add")) {
      Caman("#canvas", img, function () {
        this.sinCity().render();
      });
    } else if (e.target.classList.contains("crossprocess-add")) {
      Caman("#canvas", img, function () {
        this.crossProcess().render();
      });
    } else if (e.target.classList.contains("pinhole-add")) {
      Caman("#canvas", img, function () {
        this.pinhole().render();
      });
    } else if (e.target.classList.contains("nostalgia-add")) {
      Caman("#canvas", img, function () {
        this.nostalgia().render();
      });
    } else if (e.target.classList.contains("hermajesty-add")) {
      Caman("#canvas", img, function () {
        this.herMajesty().render();
      });
    }
  }
});

// Revert Filters
revertBtn.addEventListener("click", () => {
  Caman("#canvas", img, function () {
    this.revert();
  });
});

// Upload File
uploadFile.addEventListener("change", () => {
  const file = document.getElementById("upload-file").files[0];
  const reader = new FileReader();
  if (file) {
    fileName = file.name;
    reader.readAsDataURL(file);
  }
  reader.onload = () => {
    img = new Image();
    img.src = reader.result;
    img.onload = () => {
      const aspectRatio = img.width / img.height;
      let drawWidth, drawHeight;
      if (canvasWidth / aspectRatio <= canvasHeight) {
        drawWidth = canvasWidth;
        drawHeight = canvasWidth / aspectRatio;
      } else {
        drawWidth = canvasHeight * aspectRatio;
        drawHeight = canvasHeight;
      }
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      const offsetX = (canvasWidth - drawWidth) / 2;
      const offsetY = (canvasHeight - drawHeight) / 2;
      ctx.drawImage(img, offsetX, offsetY, drawWidth, drawHeight);
      canvas.style.display = 'block';
      canvas.removeAttribute("data-caman-id");
    };
  };
});

// Download Event
downloadBtn.addEventListener("click", () => {
  const fileExtension = fileName.slice(-4);
  let newFilename = fileName.substring(0, fileName.length - 4) + "-edited.jpg";
  download(canvas, newFilename);
});

function download(canvas, filename) {
  const link = document.createElement("a");
  link.download = filename;
  link.href = canvas.toDataURL("image/jpeg", 0.8);
  link.dispatchEvent(new MouseEvent("click"));
}


document.getElementById("upload-btn").addEventListener("click", () => {
  const canvas = document.getElementById('canvas'); // Add this line to ensure canvas is defined
  const imageData = canvas.toDataURL('image/jpeg');
  const caption = document.getElementById('caption').value;
  const status = document.getElementById("download");

  let download_ = 0;
  if (status.checked) {
    download_ = 1;
  }

  const formData = new FormData();
  formData.append('image', imageData);
  formData.append('caption', caption);
  formData.append('download', download_);

  $.ajax({
    url: './image_.php',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (data) {
      alert(data.success || data.error);
      if (data.success) {
        document.getElementById('redirect').style.display = 'block';
        startCountdownAndRedirect();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert("Error: " + textStatus + " " + errorThrown);
      console.error('Error uploading the image:', jqXHR, textStatus, errorThrown);
    }
  });
});

function startCountdownAndRedirect() {
  let countdown = 3; // Initial countdown time in seconds
  const countdownElement = document.getElementById("countdown");

  // Update the countdown every second
  const countdownInterval = setInterval(function () {
    countdown--;
    countdownElement.textContent = countdown;

    if (countdown <= 0) {
      clearInterval(countdownInterval);
      window.location.href = "./index.html";
    }
  }, 1000); // 1000 milliseconds (1 second)
}