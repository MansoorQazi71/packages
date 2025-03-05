<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Order Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->

    <!-- Bootstrap & Swiper.js CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">

    <!-- jQuery, PDF.js, Swiper.js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <style>
        .dropzone {
            border: 2px dashed #fff;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 600px;
            margin: auto;
        }

        .dropzone:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .preview-container {
            display: none;
        }

        #continueBtn,
        #printForm {
            display: none;
        }

    </style>
</head>

<body class="bg-dark text-white">
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
                <div class="swiper-wrapper" id="previewSlider"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <button id="continueBtn" class="btn btn-success mt-3">Continue</button>
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

            {{-- <h4 class="mt-4">Printing Options</h4>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Impression</label><br>
                    <input type="radio" name="impression" value="color" required> Colour
                    <input type="radio" name="impression" value="black_white" checked> Black and White
                    <input type="radio" name="impression" value="mixed"> Mixed
                </div>
                <div class="col-md-6">
                    <label class="form-label">Orientation</label><br>
                    <input type="radio" name="orientation" value="portrait" required checked> Portrait
                    <input type="radio" name="orientation" value="landscape"> Landscape
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Size of Paper</label><br>
                        <input type="radio" name="size[]" value="A4" required> A4
                        <input type="radio" name="size[]" value="A3"> A3
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Front and Back</label><br>
                        <input type="checkbox" name="front_back"> Yes
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Need Binding</label><br>
                        <input type="checkbox" name="binding"> Yes
                    </div>
                </div>

                <!-- Billing Section -->
                <div class="mt-3">
                    <input type="checkbox" name="billing_same" value="1" checked> Billing same as shipping
                </div> --}}
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit Order</button>
        </form>
    </div>

    {{-- <script>
        $(document).ready(function () {
    let uploadedFiles = [];

    $("#dropzone").on("click", function () {
        $("#fileInput").click();
    });

    $("#fileInput").on("change", function (event) {
        handleFiles(event.target.files);
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

        $("#previewSlider").empty();
        uploadedFiles = [];

        for (let file of files) {
            let fileIndex = uploadedFiles.length;
            uploadedFiles.push(file);

            let reader = new FileReader();
            reader.onload = function (e) {
                let fileData = e.target.result;
                let fileEntry = $("<div class='file-entry mt-2 text-center'></div>");

                if (file.type.startsWith("image/")) {
                    let img = $("<img>").attr("src", URL.createObjectURL(file)).css("max-width", "100px");
                    fileEntry.append(img);
                    addFileFields(file, fileIndex, 1);
                } else if (file.type === "application/pdf") {
                    renderPDFPreview(fileData, fileEntry, file, fileIndex);
                }

                $("#previewSlider").append($("<div class='swiper-slide'></div>").append(fileEntry));
            };

            reader.readAsArrayBuffer(file);
        }

        $("#previewContainer").fadeIn();
        $("#continueBtn").fadeIn();

        new Swiper(".swiper-container", {
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            slidesPerView: 3,
            spaceBetween: 10,
        });
    }

    function renderPDFPreview(fileData, fileEntry, file, fileIndex) {
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
                page.render({ canvasContext: context, viewport: viewport }).promise.then(function () {
                    fileEntry.append(canvas);
                    addFileFields(file, fileIndex, totalPages);
                });
            });
        });
    }

    function addFileFields(file, index, numPages) {
        $("#file-upload-container").append(`
            <div class="file-group mt-3">
                <input type="hidden" name="files[${index}][name]" value="${file.name}">
                <label>Pages</label>
                <input type="text" name="files[${index}][num_pages]" class="form-control num-pages-input" data-index="${index}" value="${numPages}" readonly>
            </div>
        `);
    }

    $("#continueBtn").on("click", function () {
        $("#printForm").fadeIn();
        $("html, body").animate({ scrollTop: $("#printForm").offset().top }, 500);
    });

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
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert("Error submitting order. Please try again.");
            }
        });
    });
});

    </script> --}}
    <script>
        $(document).ready(function() {
            let uploadedFiles = [];

            $("#dropzone").on("click", function() {
                $("#fileInput").click();
            });

            $("#fileInput").on("change", function(event) {
                handleFiles(event.target.files);
            });

            $("#dropzone").on("dragover", function(event) {
                event.preventDefault();
            });

            $("#dropzone").on("drop", function(event) {
                event.preventDefault();
                handleFiles(event.originalEvent.dataTransfer.files);
            });

            function handleFiles(files) {
                if (files.length === 0) return;

                for (let file of files) {
                    let fileIndex = uploadedFiles.length;
                    uploadedFiles.push(file);

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let fileData = e.target.result;
                        let fileEntry = $("<div class='file-entry mt-2 text-center'></div>");

                        if (file.type.startsWith("image/")) {
                            let img = $("<img>").attr("src", URL.createObjectURL(file)).css("max-width", "100px");
                            fileEntry.append(img);
                            addFileFields(file, fileIndex, 1);
                        } else if (file.type === "application/pdf") {
                            renderPDFPreview(fileData, fileEntry, file, fileIndex);
                        }

                        $("#previewSlider").append($("<div class='swiper-slide'></div>").append(fileEntry));
                    };

                    reader.readAsArrayBuffer(file);
                }

                $("#previewContainer").fadeIn();
                $("#continueBtn").fadeIn();

                new Swiper(".swiper-container", {
                    navigation: {
                        nextEl: ".swiper-button-next"
                        , prevEl: ".swiper-button-prev"
                    }
                    , slidesPerView: 3
                    , spaceBetween: 10
                , });
            }

            function renderPDFPreview(fileData, fileEntry, file, fileIndex) {
                let loadingTask = pdfjsLib.getDocument({
                    data: fileData
                });
                loadingTask.promise.then(function(pdf) {
                    let totalPages = pdf.numPages;
                    pdf.getPage(1).then(function(page) {
                        let scale = 0.5;
                        let viewport = page.getViewport({
                            scale: scale
                        });
                        let canvas = $("<canvas></canvas>")[0];
                        let context = canvas.getContext("2d");
                        canvas.width = viewport.width;
                        canvas.height = viewport.height;
                        page.render({
                            canvasContext: context
                            , viewport: viewport
                        }).promise.then(function() {
                            fileEntry.append(canvas);
                            addFileFields(file, fileIndex, totalPages);
                        });
                    });
                });
            }

            // function addFileFields(file, index, numPages) {
            //     $("#file-upload-container").append(`
            //         <div class="file-group mt-3">
            //             <input type="hidden" name="files[${index}][name]" value="${file.name}">
            //             <label>Pages</label>
            //             <input type="text" name="files[${index}][num_pages]" class="form-control num-pages-input" value="${numPages}" readonly>
            //         </div>
            //     `);
            // }
            function addFileFields(file, index, numPages) {
                $("#file-upload-container").append(`
        <div class="file-group mt-3 p-3 border rounded">
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


            $("#continueBtn").on("click", function() {
                $("#printForm").fadeIn();
                $("html, body").animate({
                    scrollTop: $("#printForm").offset().top
                }, 500);
            });

            $("#addAnotherFileBtn").on("click", function() {
                $("#fileInput").click();
            });

            $("#printForm").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                uploadedFiles.forEach((file, index) => {
                    formData.append(`files[${index}]`, file);
                    let numPages = parseInt($(`input[name='files[${index}][num_pages]']`).val()) || 1;
                    formData.append(`num_pages[${index}]`, numPages);
                });

                $.ajax({
                    url: "/order/submit"
                    , type: "POST"
                    , data: formData
                    , contentType: false
                    , processData: false
                    , success: function() {
                        alert("Order submitted successfully!");
                    }
                    , error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Error submitting order. Please try again.");
                    }
                });
            });
        });

    </script>
</body>

</html>
