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
        <h1>Edit Gallery</h1>
        <form action="{{ route('gallery.update', $gallery->id) }}" id="img-upload-form" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <p>
                <label for="gallery_name">Gallery Name:</label>
                <input type="text" id="gallery_name" name="gallery_name" class="form-control" value="{{ $gallery->name }}" required>
            </p>
            <p>
                <label for="upload_imgs" class="button hollow">Select Your Images +</label>
                <input class="show-for-sr" type="file" id="upload_imgs" name="upload_imgs[]" multiple/>
            </p>
            <div class="quote-imgs-thumbs quote-imgs-thumbs--hidden" id="img_preview" aria-live="polite"></div>
            <p>
                <input class="btn btn-primary" type="submit" name="submit" value="Update Gallery"/>
            </p>
        </form>
    </div>

    <script>
        var imgUpload = document.getElementById('upload_imgs'),
            imgPreview = document.getElementById('img_preview'),
            imgUploadForm = document.getElementById('img-upload-form'),
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

        imgUploadForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);

            fetch('{{ route('gallery.update', $gallery->id) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Gallery updated successfully.');
                        window.location.href = '{{ route('gallery.index') }}';
                    } else {
                        if (data.errors) {
                            let errorMessages = '';
                            for (const [key, value] of Object.entries(data.errors)) {
                                errorMessages += `${value.join(', ')}\n`;
                            }
                            alert('Validation errors:\n' + errorMessages);
                        } else {
                            alert('An error occurred while updating the gallery.');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
