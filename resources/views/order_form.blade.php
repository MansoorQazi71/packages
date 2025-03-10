@extends('layouts.master')
@section('content')
@include('layouts.header')
<div class="container py-5">
    <h2 class="text-center">Print Order Form</h2>
    <div class="dropzone" id="dropzone">
        <input type="file" id="fileInput" multiple accept="image/*,application/pdf">
        <p>📂 Drag and Drop Your Files Here or <span class="text-warning">Click Here to Select</span></p>
    </div>

    <!-- File Preview Section -->
    <div class="preview-container mt-4" id="previewContainer">
        <h3 class="text-center">File Preview</h3>
        <div class="swiper-container">
            <div class="swiper-wrapper row" id="previewSlider"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    <!-- Form -->
    <form id="printForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="file-upload-container"></div>

        <!-- Personal Info -->
        <h4 class="mt-4">Personal Information</h4>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" required>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label class="form-label">Code Postal</label>
                <input type="text" name="code_postal" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Commune</label>
                <input type="text" name="commune" class="form-control" required>
            </div>
        </div>
</div>
<div class="button-container">
    <button type="button" id="continueBtn" class="btn btn-success">Continue</button>
    <button type="button" id="addAnotherFileBtn" class="btn btn-warning mt-3" style="display: none;">➕ Add Another
        File</button>
    <button type="submit" class="btn btn-primary mt-3 text-center" id="submitOrderBtn"
        style="background-color: #E50051;">Submit Order</button>
</div>
</form>
</div>
@include('layouts.footer')

{{-- <script>
    $(document).ready(function () {
    let uploadedFiles = [];

   // Ensure file input is hidden (visually) but still functional
   $("#fileInput").css({
        position: "absolute",
        left: "-9999px"
    });

    // Click anywhere on dropzone should trigger file input
    $("#dropzone").on("click", function (event) {
        if (!$(event.target).is("#fileInput")) {  // Prevent self-triggering
            $("#fileInput").click();
        }
    });

    // Handle file selection
    $("#fileInput").on("change", function (event) {
        handleFiles(event.target.files);
    });

    // Drag-and-drop functionality
    $("#dropzone").on("dragover", function (event) {
        event.preventDefault();
    });

    $("#dropzone").on("drop", function (event) {
        event.preventDefault();
        handleFiles(event.originalEvent.dataTransfer.files);
    });

    function handleFiles(files) {
        if (files.length === 0) return;

        for (let file of files) {
            let fileIndex = uploadedFiles.length;
            uploadedFiles.push(file);

            let reader = new FileReader();
            reader.onload = function (e) {
                let fileData = e.target.result;
                let fileEntry = $("<div class='file-entry mt-2 text-center position-relative'></div>");

                let removeBtn = $("<button class='btn btn-danger btn-sm remove-file' data-index='" + fileIndex + "'>Remove</button>");
                removeBtn.css({
                    position: "absolute",
                    top: "5px",
                    right: "5px"
                });

                removeBtn.on("click", function () {
                    let index = $(this).data("index");

                    // Remove file from array
                    uploadedFiles.splice(index, 1);

                    // Remove preview entry
                    fileEntry.remove();

                    // Remove corresponding form fields
                    $(`.file-group[data-index='${index}']`).remove();
                });

                if (file.type.startsWith("image/")) {
    let img = $("<img>")
        .attr("src", URL.createObjectURL(file))
        .addClass("file-preview"); // Apply fixed size
    
    fileEntry.append(img).append(removeBtn);
    addFileFields(file, fileIndex, 1);
}
 else if (file.type === "application/pdf") {
                    renderPDFPreview(fileData, fileEntry, file, fileIndex, removeBtn);
                }

                $("#previewSlider").append($("<div class='swiper-slide'></div>").append(fileEntry));
            };

            reader.readAsArrayBuffer(file);
        }

        $("#previewContainer").fadeIn();
        $("#continueBtn").fadeIn();

        new Swiper(".swiper-container", {
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            slidesPerView: 3,
            spaceBetween: 10
        });
    }

    function renderPDFPreview(fileData, fileEntry, file, fileIndex, removeBtn) {
        let loadingTask = pdfjsLib.getDocument({ data: fileData });
        loadingTask.promise.then(function (pdf) {
            let totalPages = pdf.numPages;
            pdf.getPage(1).then(function (page) {
                let scale = 0.5;
                let viewport = page.getViewport({ scale: scale });
                let canvas = $("<canvas></canvas>")[0];
                let context = canvas.getContext("2d");
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                page.render({
                    canvasContext: context,
                    viewport: viewport
                }).promise.then(function () {
                    fileEntry.append(canvas);
                    fileEntry.append(removeBtn); // Ensure Remove button is appended
                    addFileFields(file, fileIndex, totalPages);
                });
            });
        });
    }

    function addFileFields(file, index, numPages) {
        $("#file-upload-container").append(`
            <div class="file-group mt-3 p-3 border rounded" data-index="${index}">
                <h5>File: ${file.name}</h5>
                <input type="hidden" name="files[${index}][name]" value="${file.name}">

                <label>Number of Pages</label>
                <input type="text" name="files[${index}][num_pages]" class="form-control" value="${numPages}" readonly>

                <label>Size of Paper</label>
                <select name="files[${index}][size]" class="form-control">
                    <option value="A4">A4</option>
                    <option value="A3">A3</option>
                </select>

                <label>Impression</label>
                <select name="files[${index}][impression]" class="form-control">
                    <option value="color">Color</option>
                    <option value="black_white">Black & White</option>
                    <option value="mixed">Mixed</option>
                </select>

                <label>Orientation</label>
                <select name="files[${index}][orientation]" class="form-control">
                    <option value="portrait">Portrait</option>
                    <option value="landscape">Landscape</option>
                </select>

                <label>Front and Back</label>
                <input type="checkbox" name="files[${index}][front_back]" value="1">

                <label>Need Binding</label>
                <input type="checkbox" name="files[${index}][binding]" value="1">
            </div>
        `);
    }

    // Show the print order form when Continue is clicked
    $("#continueBtn").on("click", function () {
        $("#previewContainer").fadeOut(); // Hide the preview section
        $("#dropzone").fadeOut(); // Hide the file upload section
        $("#printForm").fadeIn();
        $("#addAnotherFileBtn").fadeIn();
        $("html, body").animate({
            scrollTop: $("#printForm").offset().top
        }, 500);
    });

    // Handle form submission
    $("#printForm").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        uploadedFiles.forEach((file, index) => {
            formData.append(`files[${index}]`, file);
            let numPages = parseInt($(`input[name='files[${index}][num_pages]']`).val()) || 1;
            formData.append(`num_pages[${index}]`, numPages);
        });

        $.ajax({
            url: "/order/submit",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                alert("Order submitted successfully!");
                location.reload();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("Error submitting order. Please try again.");
            }
        });
    });
    $("#addAnotherFileBtn").on("click", function() {
    $("#dropzone").fadeIn();  // Show the file upload dropzone
    $("#previewContainer").hide(); // Hide preview container
    $("#printForm").hide();  // Hide the form to let users upload more files
    $(this).hide();
});

});

</script> --}}
<script>
    $(document).ready(function () {
    let uploadedFiles = [];

    // Ensure file input is hidden (visually) but still functional
    $("#fileInput").css({
        position: "absolute",
        left: "-9999px"
    });

    // Click anywhere on dropzone should trigger file input
    $("#dropzone").on("click", function (event) {
        if (!$(event.target).is("#fileInput")) {  // Prevent self-triggering
            $("#fileInput").click();
        }
    });

    // Handle file selection
    $("#fileInput").on("change", function (event) {
        handleFiles(event.target.files);
        $(this).val(""); // Reset input after selection to allow re-upload
    });

    // Drag-and-drop functionality
    $("#dropzone").on("dragover", function (event) {
        event.preventDefault();
    });

    $("#dropzone").on("drop", function (event) {
        event.preventDefault();
        handleFiles(event.originalEvent.dataTransfer.files);
    });

    new Swiper(".swiper-container", {
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev"
    },
    slidesPerView: 3, // Show 3 previews at a time
    spaceBetween: 30, // Add space between previews
    loop: false,
});


    // function handleFiles(files) {
    //     if (files.length === 0) return;

    //     for (let file of files) {
    //         if (uploadedFiles.some(f => f.name === file.name)) {
    //             alert("File already uploaded!");
    //             continue;
    //         }

    //         let fileIndex = uploadedFiles.length;
    //         uploadedFiles.push(file);

    //         let reader = new FileReader();
    //         reader.onload = function (e) {
    //             let fileData = e.target.result;
    //             // let fileEntry = $("<div class='file-entry mt-2 text-center position-relative col-md-4'></div>");
    //             let fileEntry = $("<div class='swiper-slide'></div>");

    //             let removeBtn = $("<button class='btn btn-danger btn-sm remove-file' data-index='" + fileIndex + "'>Remove</button>");
    //             removeBtn.css({
    //                 position: "absolute",
    //                 top: "5px",
    //                 right: "5px"
    //             });

    //             removeBtn.on("click", function () {
    //                 let index = $(this).data("index");

    //                 // Remove file from array
    //                 uploadedFiles = uploadedFiles.filter((_, i) => i !== index);

    //                 // Remove preview entry
    //                 fileEntry.remove();

    //                 // Remove corresponding form fields
    //                 $(`.file-group[data-index='${index}']`).remove();

    //                 // Show file input again if no files left
    //                 if (uploadedFiles.length === 0) {
    //                     $("#dropzone").fadeIn();
    //                 }
    //             });

    //             if (file.type.startsWith("image/")) {
    //                 let img = $("<img>")
    //                     .attr("src", URL.createObjectURL(file))
    //                     .addClass("file-preview img-thumbnail"); // Fixed size preview

    //                 fileEntry.append(img).append(removeBtn);
    //                 addFileFields(file, fileIndex, 1);
    //             } else if (file.type === "application/pdf") {
    //                 renderPDFPreview(fileData, fileEntry, file, fileIndex, removeBtn);
    //             }

    //             $("#previewSlider").append(fileEntry);
    //         };

    //         reader.readAsArrayBuffer(file);
    //     }

    //     $("#previewContainer").fadeIn();
    //     $("#continueBtn").fadeIn();
    // }
    function handleFiles(files) {
    if (files.length === 0) return;

    for (let file of files) {
        if (uploadedFiles.some(f => f.name === file.name)) {
            alert("File already uploaded!");
            continue;
        }

        let fileIndex = uploadedFiles.length;
        uploadedFiles.push(file);

        let reader = new FileReader();
        reader.onload = function (e) {
            let fileData = e.target.result;
            let fileEntry = $("<div class='swiper-slide col-12 col-sm-6 col-md-4 text-center'></div>"); 

            let removeBtn = $("<button class='btn btn-danger btn-sm remove-file' data-index='" + fileIndex + "'>Remove</button>");
            removeBtn.css({ position: "absolute", top: "5px", right: "5px" });

            removeBtn.on("click", function () {
                let index = $(this).data("index");
                uploadedFiles = uploadedFiles.filter((_, i) => i !== index);
                fileEntry.remove();
                $(`.file-group[data-index='${index}']`).remove();
                if (uploadedFiles.length === 0) $("#dropzone").fadeIn();
            });

            if (file.type.startsWith("image/")) {
                let img = $("<img>").attr("src", URL.createObjectURL(file)).addClass("file-preview img-thumbnail");
                fileEntry.append(img).append(removeBtn);
                addFileFields(file, fileIndex, 1);
            } else if (file.type === "application/pdf") {
                renderPDFPreview(fileData, fileEntry, file, fileIndex, removeBtn);
            }

            $("#previewSlider").append(fileEntry);
        };

        reader.readAsArrayBuffer(file);
    }

    $("#previewContainer").fadeIn();
    $("#continueBtn").fadeIn();

    // Swiper Initialization (Ensures 3 per row)
    new Swiper(".swiper-container", {
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        slidesPerView: 3,
        spaceBetween: 15,
        breakpoints: {
            0: { slidesPerView: 1 },
            576: { slidesPerView: 2 },
            768: { slidesPerView: 3 }
        }
    });
}


    // function renderPDFPreview(fileData, fileEntry, file, fileIndex, removeBtn) {
    //     let loadingTask = pdfjsLib.getDocument({ data: fileData });
    //     loadingTask.promise.then(function (pdf) {
    //         let totalPages = pdf.numPages;
    //         pdf.getPage(1).then(function (page) {
    //             let scale = 0.5;
    //             let viewport = page.getViewport({ scale: scale });
    //             let canvas = $("<canvas></canvas>")[0];
    //             let context = canvas.getContext("2d");
    //             canvas.width = viewport.width;
    //             canvas.height = viewport.height;
    //             page.render({
    //                 canvasContext: context,
    //                 viewport: viewport
    //             }).promise.then(function () {
    //                 fileEntry.append(canvas);
    //                 fileEntry.append(removeBtn); // Ensure Remove button is appended
    //                 addFileFields(file, fileIndex, totalPages);
    //             });
    //         });
    //     });
    // }
    function renderPDFPreview(fileData, fileEntry, file, fileIndex, removeBtn) {
    let loadingTask = pdfjsLib.getDocument({ data: fileData });

    loadingTask.promise.then(function (pdf) {
        let totalPages = pdf.numPages;
        let currentPage = 1;

        let canvas = $("<canvas class='pdf-canvas'></canvas>")[0]; 
        let context = canvas.getContext("2d");

        function renderPage(pageNumber) {
            pdf.getPage(pageNumber).then(function (page) {
                let scale = 0.5; // Increased scale for better visibility
                let viewport = page.getViewport({ scale: scale });

                canvas.width = viewport.width;
                canvas.height = viewport.height;

                let renderContext = {
                    canvasContext: context,
                    viewport: viewport,
                };

                page.render(renderContext);
            });
        }

        // Initial render
        renderPage(currentPage);

        // Create navigation buttons
        let prevBtn = $("<button class='btn btn-secondary btn-sm mx-1'>⬅️ Prev</button>");
        let nextBtn = $("<button class='btn btn-secondary btn-sm mx-1'>Next ➡️</button>");

        prevBtn.on("click", function () {
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        nextBtn.on("click", function () {
            if (currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);
            }
        });

        // Style adjustments
        let navContainer = $("<div class='text-center mt-2'></div>").append(prevBtn, nextBtn);
        let previewContainer = $("<div class='pdf-preview text-center'></div>").append(canvas, navContainer);

        fileEntry.append(previewContainer, removeBtn);

        // Add file details in the form
        addFileFields(file, fileIndex, totalPages);
    });
}


    function addFileFields(file, index, numPages) {
        $("#file-upload-container").append(`
            <div class="file-group mt-3 p-3 border rounded" data-index="${index}">
                <h5>File: ${file.name}</h5>
                <input type="hidden" name="files[${index}][name]" value="${file.name}">

                <label>Number of Pages</label>
                <input type="text" name="files[${index}][num_pages]" class="form-control" value="${numPages}" readonly>

                <label>Size of Paper</label>
                <select name="files[${index}][size]" class="form-control">
                    <option value="A4">A4</option>
                    <option value="A3">A3</option>
                </select>

                <label>Impression</label>
                <select name="files[${index}][impression]" class="form-control">
                    <option value="color">Color</option>
                    <option value="black_white">Black & White</option>
                    <option value="mixed">Mixed</option>
                </select>

                <label>Orientation</label>
                <select name="files[${index}][orientation]" class="form-control">
                    <option value="portrait">Portrait</option>
                    <option value="landscape">Landscape</option>
                </select>

                <label>Front and Back</label>
                <input type="checkbox" name="files[${index}][front_back]" value="1">

                <label>Need Binding</label>
                <input type="checkbox" name="files[${index}][binding]" value="1">
            </div>
        `);
    }

    // Show the print order form when Continue is clicked
    $("#continueBtn").on("click", function () {
        $("#previewContainer").fadeOut(); // Hide the preview section
        $("#dropzone").fadeOut(); // Hide the file upload section
        $("#printForm").fadeIn();
        $("#addAnotherFileBtn").fadeIn();
        $("#submitOrderBtn").fadeIn(); // Show submit button after continue is clicked
        $("html, body").animate({
            scrollTop: $("#printForm").offset().top
        }, 500);
    });

    // Handle form submission
    $("#printForm").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        uploadedFiles.forEach((file, index) => {
            formData.append(`files[${index}]`, file);
            let numPages = parseInt($(`input[name='files[${index}][num_pages]']`).val()) || 1;
            formData.append(`num_pages[${index}]`, numPages);
        });

        $.ajax({
            url: "/order/submit",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                alert("Order submitted successfully!");
                location.reload();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("Error submitting order. Please try again.");
            }
        });
    });

    $("#addAnotherFileBtn").on("click", function () {
        $("#dropzone").fadeIn();
        $("#previewContainer").hide();
        $("#printForm").hide();
        $(this).hide();
    });

    // Hide submit button initially
    $("#submitOrderBtn").hide();
});

</script>
@endsection