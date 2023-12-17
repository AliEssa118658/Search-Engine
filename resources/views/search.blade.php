<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    <style>
        .highlight {
            background-color: yellow;
        }
        body{
            max-width:1550px
        }

        .clickable {
            cursor: pointer;
            color: blue;
            border: 2px solid black;
            margin: 0.2rem;
            padding: 0.5rem;
            list-style: none
        }
        #documentDetails{
            border: 2px solid black;
            height: 25rem;
            overflow-y: auto

        }
        .docx{
            height: 25rem;
            overflow-y: auto

        }
    </style>
</head>
<body>

    <h1 class="text-center mt-5">Document Search Results</h1>

    <form class="text-center mt-5" action="{{ route('search') }}" method="GET">
        <input type="text" name="content" value="{{ $searchQuery }}" required>
        <select name="model">
            <option value="Boolean">Boolean Model</option>
            <option value="Vector">Vector Model</option>
            <option value="Extended">Extended Model</option>
        </select>
        <br>
        <button class="mt-2" type="submit">Search</button>
    </form>


    @if(count($documents) > 0)
        <h4 class="text-center mt-5">Search Results:</h4>
       <h4 style="position: relative;left:2.2%"> Click on the document to view it in full</h4>
        <div class="row">
            <div class="col-6 docx">
                <ul>
                    @foreach($documents as $document)
                        {{-- make each li clickable --}}
                        <li class="clickable" onclick="showDocumentDetails('{{$document->path}}', '{{urlencode($document->content)}}')">
                            {{ $document->path }} <br>
                             {{ substr($document->content, 0, 150) . '...' }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-6" id="documentDetails">
                <h2 id="title"></h2>
                <p id="cont"></p>
            </div>
        </div>
    @else
        <p>No documents found for the search query.</p>
    @endif

    <script>
        function showDocumentDetails(path, content) {
            // Decode the content
            content = decodeURIComponent(content);

            // Remove plus signs
            content = content.replace(/\+/g, ' ');

            // Highlight search results
            var highlightedContent = highlightSearchResults(content, '{{ $searchQuery }}');

            // Update the content of the second div with the details of the clicked document
            var documentDetailsDiv = document.getElementById('documentDetails');
            var title = document.getElementById('title');
            var cont = document.getElementById('cont');

            title.innerHTML = path;
            cont.innerHTML = highlightedContent;
        }

        function highlightSearchResults(content, searchQuery) {
            // Use a case-insensitive pattern for the search query
            var pattern = new RegExp(searchQuery, 'ig');
            // Replace matches with the highlighted version
            var highlightedContent = content.replace(pattern, '<span class="highlight">$&</span>');
            return highlightedContent;
        }
    </script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
