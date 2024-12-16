<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-4 text-white">Create New Image</h1>
        <form id="create-form" action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmCreate(event)">
            @csrf
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
                <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mt-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Image:</label>
                <input type="file" name="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" accept=".jpg,.jpeg,.png,.gif,.webp" required>
            </div>
            <div class="mt-4">
                <label for="tags" class="block text-sm font-medium text-gray-700">Tags:</label>
                <select name="tags[]" id="tags" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" multiple>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="mt-6 px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Upload</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tags').select2({
                tags: true,  // Allow custom tag input
                tokenSeparators: [',', ' '],
                placeholder: 'Add tags (separate with commas or spaces)',
                width: '100%',
            });
        });

        function confirmCreate(event) {
            // Prevent the form from submitting initially
            event.preventDefault();

            // Check if the title field is empty
            const title = document.getElementById('title').value;
            if (!title) {
                Swal.fire({
                    title: 'Title is required!',
                    text: 'Please enter a title for the image.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                return false; // Stop form submission
            }

            // Show confirmation popup
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
                    // Show success message before form submission
                    Swal.fire({
                        title: 'Created!',
                        text: 'The image has been created successfully.',
                        icon: 'success',
                        showConfirmButton: true,
                        timer: 1500
                    }).then(() => {
                        // Submit the form after success message
                        document.getElementById('create-form').submit();
                    });
                }
            });
        }
    </script>
</x-app-layout>
