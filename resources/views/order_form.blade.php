@extends('layouts.master')
@section('content')
@include('layouts.header')

<div class="container py-5">
    <h2 class="text-center">Print Order</h2>

    <!-- File Upload Section -->
    <div class="dropzone text-center p-4 border border-primary rounded" id="dropzone">
        <label for="fileInput"
            class="w-100 h-100 d-flex flex-column align-items-center justify-content-center cursor-pointer">
            <input type="file" id="fileInput" accept="image/*,application/pdf" hidden>
            <p class="m-0">📂 Drag and Drop Your File Here or <span class="text-warning fw-bold">Click Here to
                    Select</span></p>
        </label>
    </div>


    <!-- File Preview Section -->
    <div class="preview-container mt-4" id="previewContainer" style="display:none;">
        <h3 class="text-center">File Preview</h3>
        <div class="swiper-container">
            <div class="swiper-wrapper row" id="previewSlider"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <div class="mt-3 text-center">
            <button type="button" id="continueBtn" class="btn btn-success">Continue</button>
        </div>
    </div>

    <!-- File Details Form -->
    <form id="printForm" style="display:none;">
        @csrf
        <h3 class="text-center">File Details</h3>
        <div id="file-upload-container"></div>

        <div class="text-center mt-4">
            {{-- <button type="button" id="addAnotherFileBtn" class="btn btn-warning">Add Another File</button> --}}
            <button type="button" id="continueToPersonalInfoBtn" class="btn btn-success">Continue to Personal
                Info</button>
        </div>
    </form>

    <!-- Personal Information Form -->
    <form id="personalInfoForm" style="display:none;">
        <h4 class="mt-4 text-center">Personal Information</h4>
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

        <!-- Submit Button -->
        <div class="d-flex align-items-center justify-content-between mt-4 text-center">
            <button type="button" id="addAnotherFileBtn" class="btn btn-warning">Add Another File</button>
            <button type="submit" class="btn btn-primary" id="submitOrderBtn">Submit Order</button>
        </div>
    </form>
</div>


@include('layouts.footer')

<script>
    $(document).ready(function () {
    let uploadedFiles = [];

    $("#fileInput").on("change", function (event) {
        handleFiles(event.target.files);
        $(this).val(""); // Reset file input
    });

    $("#dropzone").on("dragover", function (event) {
        event.preventDefault();
    });

    $("#dropzone").on("drop", function (event) {
        event.preventDefault();
        handleFiles(event.originalEvent.dataTransfer.files);
    });

    function handleFiles(files) {
        if (files.length === 0) return;

        let file = files[0]; // Get the latest uploaded file
        uploadedFiles.push(file); // Store all files but show only the latest

        // Hide previous previews and fields but keep their data for submission
        $(".swiper-slide").hide();
        $(".file-group").hide();

        let fileIndex = uploadedFiles.length - 1; // Latest file index

        let reader = new FileReader();
        reader.onload = function (e) {
            let fileData = e.target.result;
            let fileEntry = $("<div class='swiper-slide col-12 col-sm-6 col-md-4 text-center'></div>");

            let removeBtn = $("<button class='btn btn-danger btn-sm remove-file' data-index='" + fileIndex + "'>Remove</button>");
            removeBtn.on("click", function () {
                let index = $(this).data("index");
                uploadedFiles.splice(index, 1);
                fileEntry.remove();
                $(`.file-group[data-index='${index}']`).remove();
                $("#previewContainer").hide();
                $("#dropzone").fadeIn();
            });

            if (file.type.startsWith("image/")) {
                let img = $("<img>").attr("src", URL.createObjectURL(file)).addClass("file-preview img-thumbnail");
                fileEntry.append(img).append(removeBtn);
                addFileFields(file, fileIndex, 1); // Default to 1 page
            } else if (file.type === "application/pdf") {
                renderPDFPreview(fileData, fileEntry, file, fileIndex, removeBtn);
            }

            $("#previewSlider").append(fileEntry);
        };
        reader.readAsArrayBuffer(file);

        $("#continueBtn").fadeIn();
        $("#previewContainer").fadeIn();
    }

    function renderPDFPreview(fileData, fileEntry, file, fileIndex, removeBtn) {
        let loadingTask = pdfjsLib.getDocument({ data: fileData });

        loadingTask.promise.then(function (pdf) {
            let totalPages = pdf.numPages;
            let currentPage = 1;

            let canvas = $("<canvas class='pdf-canvas'></canvas>")[0];
            let context = canvas.getContext("2d");

            function renderPage(pageNumber) {
                pdf.getPage(pageNumber).then(function (page) {
                    let scale = 0.5;
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

            renderPage(currentPage);

            let prevBtn = $("<button class='btn btn-secondary btn-sm mx-1' id='prevBtn'>⬅️ Prev</button>");
            let nextBtn = $("<button class='btn btn-secondary btn-sm mx-1' id='nextBtn'>Next ➡️</button>");

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

            let navContainer = $("<div class='text-center mt-2'></div>").append(prevBtn, nextBtn);
            let previewContainer = $("<div class='pdf-preview text-center'></div>").append(canvas, navContainer);

            fileEntry.append(previewContainer, removeBtn);

            addFileFields(file, fileIndex, totalPages);
        });
    }

    function addFileFields(file, index, numPages) {
        $(".file-group").hide(); // Hide previous file fields but keep them for submission

        let fileFields = `
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
        `;

        $("#file-upload-container").append(fileFields);
    }

    $("#continueBtn").on("click", function () {
        $("#previewContainer").fadeOut();
        $("#dropzone").fadeOut();
        $("#addAnotherFileBtn").fadeOut();
        $("#printForm").fadeIn();
    });

    $("#addAnotherFileBtn").on("click", function () {
        $("#dropzone").fadeIn();
        $("#previewContainer").hide();
        $("#printForm").hide();
        $("#personalInfoForm").hide();
    });

    $("#continueToPersonalInfoBtn").on("click", function () {
        $("#personalInfoForm").fadeIn();
        $("#previewContainer").hide();
        $("#printForm").hide();
        $("#addAnotherFileBtn").fadeIn();
    });

    $("#submitOrderBtn").on("click", function (e) {
        e.preventDefault();

        let formData = new FormData($("#personalInfoForm")[0]);

        uploadedFiles.forEach((file, index) => {
            formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
            formData.append(`files[${index}][file]`, file);
            formData.append(`files[${index}][num_pages]`, $(`input[name='files[${index}][num_pages]']`).val());
            formData.append(`files[${index}][size]`, $(`select[name='files[${index}][size]']`).val());
            formData.append(`files[${index}][impression]`, $(`select[name='files[${index}][impression]']`).val());
            formData.append(`files[${index}][orientation]`, $(`select[name='files[${index}][orientation]']`).val());
            formData.append(`files[${index}][front_back]`, $(`input[name='files[${index}][front_back]']:checked`).val() || 0);
            formData.append(`files[${index}][binding]`, $(`input[name='files[${index}][binding]']:checked`).val() || 0);
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
});

</script>


@endsection