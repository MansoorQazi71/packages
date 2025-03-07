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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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

        .file-preview {
            width: 150px;
            /* Set a fixed width */
            height: 200px;
            /* Set a fixed height */
            object-fit: contain;
            /* Ensure image fits well */
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .top-header {
            position: sticky;
            top: 0;
            z-index: 1100;
        }
    </style>
</head>

<body class="bg-primary text-white">
    @yield('content')
</body>

</html>