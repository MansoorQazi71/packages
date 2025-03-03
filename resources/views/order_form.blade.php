<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Order Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>


</head>

<body class="bg-dark text-white">
    <div class="container py-5">
        <h2 class="text-center">Print Order Form</h2>
        <form action="{{ route('order.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- File Upload Section -->
           <div id="file-upload-container">
            <div class="file-entry"> <!-- Wrapped in file-entry -->
                <div class="mb-3">
                    <label class="form-label">Upload File (PDF or Images)</label>
                    <input type="file" name="files[]" class="form-control file-input" required> <!-- Added file-input class -->
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Number of Pages</label>
                        <input type="text" name="num_pages[]" class="form-control num-pages" readonly required>
                    </div>
                    @if($settings['price_module'])
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Price</label>
                        <input type="text" name="price[]" class="form-control price-input">
                    </div>
                    @endif
                </div>
            </div>
        </div>

            <button type="button" id="add-file" class="btn btn-secondary">+ Add another file</button>

            <!-- Personal Info Section -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Name and Surname</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Address</label>
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

            <!-- Order Options Section -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Impression</label><br>
                    <input type="radio" name="impression" value="color" required> Colour
                    <input type="radio" name="impression" value="black_white"> Black and White
                    <input type="radio" name="impression" value="mixed"> Mixed
                </div>
                <div class="col-md-6">
                    <label class="form-label">Orientation</label><br>
                    <input type="radio" name="orientation" value="portrait" required> Portrait
                    <input type="radio" name="orientation" value="landscape"> Landscape
                </div>
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
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary mt-3">Submit Order</button>
        </form>
    </div>

    {{-- <script>
        $(document).ready(function () {
        // Add another file input section
        $('#add-file').click(function () {
            let fileRow = `
                <div class="file-entry">
                    <div class="mb-3">
                        <input type="file" name="files[]" class="form-control file-input" required>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Number of Pages</label>
                            <input type="text" name="num_pages[]" class="form-control num-pages" readonly required>
                        </div>
                        @if($settings['price_module'])
                        <div class="col-md-6">
                            <label class="form-label">Price</label>
                            <input type="text" name="price[]" class="form-control price-input">
                        </div>
                        @endif
                    </div>
                </div>
            `;
            $('#file-upload-container').append(fileRow);
        });

        // Detect file selection and calculate pages
        $(document).on('change', '.file-input', function (event) {
            let input = $(this);
            let file = event.target.files[0];

            if (file) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    let fileData = e.target.result;
                    let numPages = 0;

                    if (file.type === 'application/pdf') {
                        // PDF.js logic to get the number of pages
                        pdfjsLib.getDocument({data: fileData}).promise.then(function (pdf) {
                            numPages = pdf.numPages;
                            input.closest('.file-entry').find('.num-pages').val(numPages);
                        });
                    } else if (['image/jpeg', 'image/png'].includes(file.type)) {
                        // For images, assume 1 page
                        numPages = 1;
                        input.closest('.file-entry').find('.num-pages').val(numPages);
                    } else {
                        alert('Unsupported file type.');
                    }
                };

                reader.readAsArrayBuffer(file);
            }
        });
    });
    </script> --}}

    <!-- Include PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    <script>
        $(document).ready(function () {
            // Add another file input section
            $('#add-file').click(function () {
                let fileRow = `
                    <div class="file-entry">
                        <div class="mb-3">
                            <input type="file" name="files[]" class="form-control file-input" required>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Number of Pages</label>
                                <input type="text" name="num_pages[]" class="form-control num-pages" readonly required>
                            </div>
                            @if($settings['price_module'])
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Price</label>
                                <input type="text" name="price[]" class="form-control price-input">
                            </div>
                            @endif
                        </div>
                    </div>
                `;
                $('#file-upload-container').append(fileRow);
            });
    
            // Detect file selection and calculate pages
            $(document).on('change', '.file-input', function (event) {
                let input = $(this);
                let file = event.target.files[0];
    
                if (file) {
                    let reader = new FileReader();
    
                    reader.onload = function (e) {
                        let fileData = e.target.result;
                        let numPages = 0;
    
                        if (file.type === 'application/pdf') {
                            // PDF.js logic to get the number of pages
                            pdfjsLib.getDocument({data: fileData}).promise.then(function (pdf) {
                                numPages = pdf.numPages;
                                input.closest('.file-entry').find('.num-pages').val(numPages);
                            });
                        } else if (['image/jpeg', 'image/png'].includes(file.type)) {
                            // For images, assume 1 page
                            numPages = 1;
                            input.closest('.file-entry').find('.num-pages').val(numPages);
                        } else {
                            alert('Unsupported file type.');
                        }
                    };
    
                    reader.readAsArrayBuffer(file);
                }
            });
        });
    </script>
    

</body>

</html>