<x-app-layout>
    <link href="{{ asset('css/create.css') }}" rel="stylesheet">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="title text-2xl font-bold mb-6 text-white">Edit Image</h1>
        <form id="edit-form" class="create-form-container" action="{{ route('images.update', $image->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmEdit(event)">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" id="title" class="form-input" value="{{ $image->title }}" placeholder="Maximum 32 characters" required maxlength="32">
            </div>
            <div class="form-group">
                <label for="image" class="form-label">Image (Optional):</label>
                <input type="file" name="image" id="image" class="form-input" accept=".jpg,.jpeg,.png,.gif,.webp" onchange="previewImage(event)">
                
                <!-- Image preview will show up here -->
                <div id="image-preview-container" style="display: {{ $image->image_path ? 'flex' : 'none' }}; margin-top: 10px; justify-content: center;">
                    <img id="image-preview" src="{{ Storage::url($image->image_path) }}" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                </div>
            </div>
            <div class="form-group tags-container">
                <label for="tags" class="form-label">Tags: (separated by commas)</label>
                <select name="tags[]" id="tags" class="form-input" multiple>
                    @foreach($image->tags as $tag)
                        <option value="{{ $tag->id }}" selected>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="upload-btn">Update</button>
            </div>
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

        function confirmEdit(event) {
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
                text: "Do you want to update this image?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Updated!',
                        text: 'The image has been updated successfully.',
                        icon: 'success',
                        showConfirmButton: true,
                        timer: 1500
                    }).then(() => {
                        document.getElementById('edit-form').submit();
                    });
                }
            });
        }
    </script>
</x-app-layout>
