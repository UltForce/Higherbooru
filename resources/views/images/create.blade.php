<x-app-layout>
    <link href="{{ asset('css/create.css') }}" rel="stylesheet">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="title text-2xl font-bold mb-6 text-white">Create New Image</h1>
        <form id="create-form" class="create-form-container" action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmCreate(event)">
            @csrf
            <div class="form-group">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" id="title" class="form-input" placeholder="Maximum 32 characters" required maxlength="32">
            </div>
            <div class="form-group">
                <label for="image" class="form-label">Image:</label>
                <input type="file" name="image" id="image" class="form-input" accept=".jpg,.jpeg,.png,.gif,.webp" required onchange="previewImage(event)">
                <!-- Image preview will show up here -->
                <div id="image-preview-container" style="display: none; margin-top: 10px; display: flex; justify-content: center;">
                    <img id="image-preview" src="" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                </div>
            </div>
            <div class="form-group tags-container">
                <label for="tags" class="form-label">Tags: (separated by commas)</label>
                <select name="tags[]" id="tags" class="form-input" multiple></select>
            </div>

            <button type="submit" class="upload-btn">Upload</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tags').select2({
                tags: true,  // Allow custom tag input
                tokenSeparators: [',', ' '],  // Allow space and comma as tag separators
                placeholder: 'Add tags',  // Placeholder text
                width: '100%',  // Ensure full width
            });
        });

        // Function to preview the selected image inside the form group
        function previewImage(event) {
            const image = event.target.files[0];
            const previewContainer = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'flex';  // Show the preview container and center the image
            };

            if (image) {
                reader.readAsDataURL(image);
            }
        }

        function confirmCreate(event) {
            event.preventDefault();

            const title = document.getElementById('title').value;
            if (!title) {
                Swal.fire({
                    title: 'Title is required!',
                    text: 'Please enter a title for the image.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                return false;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to create this image?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, create it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Created!',
                        text: 'The image has been created successfully.',
                        icon: 'success',
                        showConfirmButton: true,
                        timer: 1500
                    }).then(() => {
                        document.getElementById('create-form').submit();
                    });
                }
            });
        }
    </script>
</x-app-layout>
