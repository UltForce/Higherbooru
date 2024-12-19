<x-app-layout>
<link href="{{ asset('css/create.css') }}" rel="stylesheet">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="title text-2xl font-bold mb-6 text-white">Create New Post</h1>
        <form id="create-form" class="create-form-container" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmCreate(event)">
            @csrf
            <div class="form-group">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" id="title" class="form-input" placeholder="Maximum 32 characters" required maxlength="32">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description:</label>
                <textarea name="description" id="description" class="form-input" placeholder="Write your post description" required></textarea>
            </div>

            <div class="form-group">
                <label for="images" class="form-label">Images (You can select multiple images):</label>
                <input type="file" name="images[]" id="images" class="form-input" accept=".jpg,.jpeg,.png,.gif,.webp" required multiple onchange="previewImages(event)">
                <!-- Image preview will show up here -->
                <div id="image-preview-container" style="display: none; margin-top: 10px; display: flex; flex-wrap: wrap;">
                    <!-- Image previews will be appended here dynamically -->
                </div>
            </div>

            <div class="form-group tags-container">
                <label for="tags" class="form-label">Tags: (separated by commas)</label>
                <select name="tags[]" id="tags" class="form-input" multiple></select>
            </div>

            <button type="submit" class="upload-btn">Create Post</button>
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

        // Function to preview selected images inside the form group
        function previewImages(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('image-preview-container');
            previewContainer.innerHTML = ''; // Clear previous previews

            Array.from(files).forEach((file) => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.maxHeight = '100px';
                    img.style.marginRight = '10px';
                    img.style.marginBottom = '10px';
                    img.style.borderRadius = '8px';
                    previewContainer.appendChild(img);
                };

                if (file) {
                    reader.readAsDataURL(file);
                }
            });

            previewContainer.style.display = 'flex';  // Show the preview container
        }

        function confirmCreate(event) {
            event.preventDefault();

            const title = document.getElementById('title').value;
            if (!title) {
                Swal.fire({
                    title: 'Title is required!',
                    text: 'Please enter a title for the post.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                return false;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to create this post?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, create it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Created!',
                        text: 'The post has been created successfully.',
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
