<!DOCTYPE html>
<html >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            .btn{
                width: 15%;
                height: 3.5rem;
                font-size: 25px;
                margin: auto;
                background-color: #080b3a;
                color: white;
                text-decoration: none;
                border-radius: 10px
            }
        </style>
            <script>
                // Function to enable or disable the search page button based on file upload
                function toggleSearchButton() {
                    var uploadInput = document.getElementById('folderInput');
                    var searchButton = document.getElementById('searchButton');

                    // Enable the button if a file has been selected
                    searchButton.disabled = !uploadInput.value;
                }
            </script>
    </head>
 <body>
    <section class="mt-5" >
        <div class="container " style="margin-top: 10%">
            <h1 class="text-center">Please Upload Folder contain Word Documents</h1>


            <div class="text-center mt-5">
                <form method="post" action="/upload" enctype="multipart/form-data">
                    @csrf
            <input id="folderInput" type="file" name="folder" directory webkitdirectory mozdirectory onchange="toggleSearchButton()">
                    <button type="submit">Upload Folder</button>
                </form>
            </div>

            @if(session('success'))
             <h2 class="text-center mt-5" style="color: green;">{!! session('success') !!}</h2>
            @endif
            <div class="text-center mt-5">
                <h3>When you upload the folder 3 things will happen</h3>
                <h4>1- Arabic and English will be dealt with</h4>
                <h4>2- A weighting algorithm will be applied</h4>
                <h4>3- The files will be indexed</h4>
            </div>

        </div>

    </section>

 </body>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
