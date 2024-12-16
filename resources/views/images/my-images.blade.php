<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">My Images</h1>
        <a href="{{ route('images.create') }}" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
            Upload New Image
        </a>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @foreach ($images as $image)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">                  
                    <a href="{{ route('images.show', $image->id) }}">
                    <img class="w-full h-48 object-cover border-b-2 border-gray-300" 
                         src="{{ asset('storage/' . $image->image_path) }}" 
                         alt="{{ $image->title }}">
                         </a>
                    <div class="p-4">
                        <h2 class="text-lg font-bold text-gray-800">{{ $image->title }}</h2>
                        <div class="mt-2">
                <strong>Tags:</strong>
                <ul class="list-none">
                    @foreach ($image->tags as $tag)
                        <li class="inline-block text-sm text-gray-600">{{ $tag->name }}</li>
                    @endforeach
                </ul>
                <span class="text-sm text-gray-500 ml-2">{{ $image->bookmarkCount() }} bookmarks</span>

            </div>
                        <div class="mt-4 flex space-x-2">
                            <!-- Edit Button -->
                            <a href="{{ route('images.edit', $image->id) }}" 
                               class="px-4 py-2 text-black bg-yellow-500 border border-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-all">
                                Edit
                            </a>
                            <!-- Delete Button -->
                            <form id="delete-form-{{ $image->id }}" action="{{ route('images.destroy', $image->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button 
                                class="px-4 py-2 text-black bg-red-500 border border-red-600 rounded hover:bg-red-600 hover:text-white transition-all"
                                onclick="confirmDelete({{ $image->id }})">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function confirmDelete(imageId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show success message before form submission
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'The image has been deleted successfully.',
                        icon: 'success',
                        showConfirmButton: true,
                        timer: 1500
                    }).then(() => {
                        // Submit the form after success message
                        document.getElementById('delete-form-' + imageId).submit();
                    });
                }
            });
        }
    </script>
</x-app-layout>
