<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <title>Laravel Image Upload</title>
    <style>
        dl,
        ol,
        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .imgPreview img {
            padding: 8px;
            max-width: 100px;
        }

        div.alert {
            text-align: center;
        }

        /* Image gallery css */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial;
        }

        /* The grid: Four equal columns that floats next to each other */
        .column {
            float: left;
            width: 25%;
            padding: 10px;
        }

        /* Style the images inside the grid */
        .column img {
            opacity: 1;
            cursor: pointer;
        }

        .column img:hover {
            opacity: 0.8;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* The expanding image container */
        .container {
            position: relative;
            /* display: none; */
        }

        /* Expanding image text */
        #imgtext {
            padding-left: 10px;
            position: absolute;
            bottom: 15px;
            left: 15px;
            color: white;
            font-size: 12px;
            word-wrap: break-word;
        }

        /* Closable button inside the expanded image */
        .closebtn {
            position: absolute;
            padding-left: 10px;
            color: white;
            font-size: 35px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h3 class="text-center mb-5">Upload your images</h3>
        <div class="row">
            <div class="col-4">
                <form action="{{route('imageUpload')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif

                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li class="text-center"><strong>{{ $error }}</strong></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="user-image mb-3 text-center">
                        <div class="imgPreview"> </div>
                    </div>

                    <div class="custom-file">
                        <input type="file" name="imageFile[]" class="custom-file-input" id="images" multiple="multiple">
                        <label class="custom-file-label" for="images">Choose image</label>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
                        Upload Images
                    </button>

                    <a href="{{ route('truncate') }}" class="btn btn-danger btn-block mt-4">Delete All Photos</a>
                </form>
            </div>
            <div class="col-8">
                <div class="container">
                    <span onclick="this.parentElement.style.display='none'" class="closebtn">&times;</span>
                    <img id="expandedImg" style="height:300px;" />
                    <div id="imgtext" style="width: 25%;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- The four columns -->
        <div class="row">
            @forelse ($image as $gambar)
            <div class="col-lg-3 col-sm-6">
                <div class="column">
                    <img src="{{ url('uploads/'.$gambar) }}" alt="{{ $gambar }}" width="250px"
                        onclick="myFunction(this);" />
                </div>
            </div>
            @empty
            <div class="col-12 mt-5">
                <div class="alert alert-warning">
                    <strong>Upload to see photos here</strong>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- jQuery -->
    <script>
        function myFunction(imgs) {
      var expandImg = document.getElementById("expandedImg");
      var imgText = document.getElementById("imgtext");
      expandImg.src = imgs.src;
      imgText.innerHTML = imgs.alt;
      expandImg.parentElement.style.display = "block";
    }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(function() {
        // Multiple images preview with JavaScript
        var multiImgPreview = function(input, imgPreviewPlaceholder) {

            if (input.files) {
                var filesAmount = input.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function(event) {
                        $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
                    }

                    reader.readAsDataURL(input.files[i]);
                }
            }

        };

        $('#images').on('change', function() {
            multiImgPreview(this, 'div.imgPreview');
        });
        });    
    </script>
</body>

</html>