@extends('layouts.app')

@section('content')

    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .quote-imgs-thumbs {
            background: #eee;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            margin: 1.5rem 0;
            padding: 0.75rem;
        }
        .quote-imgs-thumbs--hidden {
            display: none;
        }
        .img-preview-thumb {
            background: #fff;
            border: 1px solid #777;
            border-radius: 0.25rem;
            box-shadow: 0.125rem 0.125rem 0.0625rem rgba(0, 0, 0, 0.12);
            margin-right: 1rem;
            max-width: 140px;
            padding: 0.25rem;
        }
    </style>

    <div class="container">
        <h1>Create a Gallery</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('gallery.store') }}" id="img-upload-form" method="post" enctype="multipart/form-data">
            @csrf
            <p>
                <label for="gallery_name">Gallery Name:</label>
                <input type="text" id="gallery_name" name="gallery_name" class="form-control" placeholder="Enter gallery name" required>
            </p>
            <p>
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control" placeholder="Enter gallery description"></textarea>
            </p>
            <p>
                <label for="upload_imgs" class="button hollow">Select Your Images +</label>
                <input class="show-for-sr" type="file" id="upload_imgs" name="upload_imgs[]" multiple/>
            </p>
            <div class="quote-imgs-thumbs quote-imgs-thumbs--hidden" id="img_preview" aria-live="polite"></div>
            <p>
                <input class="btn btn-primary" type="submit" name="submit" value="Upload Images"/>
            </p>
        </form>
    </div>

    <script>
        var imgUpload = document.getElementById('upload_imgs'),
            imgPreview = document.getElementById('img_preview'),
            totalFiles,
            previewTitle,
            previewTitleText,
            img;

        imgUpload.addEventListener('change', previewImgs, false);

        function previewImgs(event) {
            totalFiles = imgUpload.files.length;
            console.log('Total files selected:', totalFiles);

            if (totalFiles) {
                imgPreview.classList.remove('quote-imgs-thumbs--hidden');
                previewTitle = document.createElement('p');
                previewTitle.style.fontWeight = 'bold';
                previewTitleText = document.createTextNode(totalFiles + ' Total Images Selected');
                previewTitle.appendChild(previewTitleText);
                imgPreview.appendChild(previewTitle);
                console.log('Preview title added:', previewTitleText.nodeValue);
            }

            for (var i = 0; i < totalFiles; i++) {
                img = document.createElement('img');
                img.src = URL.createObjectURL(event.target.files[i]);
                img.classList.add('img-preview-thumb');
                imgPreview.appendChild(img);
                console.log('Image preview added:', img.src);
            }
        }
    </script>
@endsection
