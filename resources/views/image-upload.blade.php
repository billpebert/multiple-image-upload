<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}" />

    <title>Multiple image upload</title>

</head>

<body>

    <div class="container mt-5">
        <h3 class="text-center mb-5">Upload your images here!</h3>
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
                    <div id="imgtext"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- The four columns -->
        <div class="row">
            @forelse ($image as $gambar)
            <div class="col-lg-3 col-sm-6">

                {{-- {{ dd(Storage::url('app/uploads')) }} --}}
                <div class="column">
                    {{-- Show image from folder public/uploads --}}
                    {{-- <img src="{{ url('uploads/'.$gambar) }}" alt="{{ $gambar }}" width="250px"
                    onclick="myFunction(this);" /> --}}

                    {{-- Show image from folder storage/app/public/uploads --}}
                    <img src="{{ Storage::url('uploads/'.$gambar) }}" alt="{{ $gambar }}" width="250px"
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