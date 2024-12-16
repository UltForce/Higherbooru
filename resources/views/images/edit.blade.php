<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Image</h1>
        <form id="edit-form" action="{{ route('images.update', $image->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ $image->title }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image (Optional)</label>
                <input type="file" name="image" id="image"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    accept=".jpg,.jpeg,.png,.gif,.webp">
            </div>
            <div>
                <button type="button" onclick="confirmEdit()" 
                        class="w-full py-2 px-4 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none">
                    Update
                </button>
            </div>
        </form>
    </div>

    <script>
        function confirmEdit() {
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
                    // Show success message before form submission
                    Swal.fire({
                        title: 'Updated!',
                        text: 'The image has been updated successfully.',
                        icon: 'success',
                        showConfirmButton: true,
                        timer: 1500
                    }).then(() => {
                        // Submit the form after success message
                        document.getElementById('edit-form').submit();
                    });
                }
            });
        }
    </script>
</x-app-layout>
