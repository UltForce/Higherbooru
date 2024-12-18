<x-app-layout>
<link href="{{ asset('css/my-images.css') }}" rel="stylesheet">
<body>
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="title">
    My Images
    </div>
    <a href="{{ route('images.create') }}" class="upload-btn px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
            Upload New Image
    </a>
    <!-- Search and Filter Form -->
    <div class="search-function">
        <form method="GET" action="{{ route('images.my-images') }}" class="flex items-center mb-4">
            <!-- Search Input -->
            <input type="text" class="search-form" name="title" placeholder="Search by title" value="{{ request('title') }}">

            <!-- Tags Input -->
            <input type="text" class="tags-form" name="tags" placeholder="Search by tags" value="{{ request('tags') }}">

            <!-- Sorting Dropdown -->
            <select name="sort" class="ml-4 sort-form">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort by: Newest</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Sort by: Oldest</option>
            </select>

            <!-- Submit Button -->
            <button type="submit" class="search-btn ml-4 text-white search-btn">Search</button>

            <!-- Clear Search Button -->
            <a href="{{ route('images.my-images') }}" class="clear-btn text-blue-500 hover:text-blue-700 ml-4 clear-btn">Clear Search</a>
        </form>
    </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($images as $image)
                <div class="img-cntr bg-white shadow-md rounded-lg overflow-hidden">
                    <!-- Add a link to the view page -->
                    <div class="image-container">
                    <a href="{{ route('images.show', $image->id) }}">
                        <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->title }}">
                    </a>
                    </div>
                    <div class="p-4">
                        <h2 class="image-title text-lg font-bold text-gray-800">{{ $image->title }}</h2>
                        <p class="image-uploader text-gray-500 text-sm">Uploaded by: {{ $image->user->name }}</p>
                        <div class="mt-2">
                            <strong>Tags:</strong>
                            <ul class="image-tags-text list-none">
                                @foreach ($image->tags as $tag)
                                    <li class="image-tags inline-block text-sm text-gray-600">{{ $tag->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Bookmark Button -->
                        <div class="bookmark-cntr flex items-center mt-2 space-x-2 mt-auto">
                            <form action="{{ route('images.bookmark', $image->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-600">
                                    <!-- Conditional heart icon -->
                                    <i class="{{ $image->is_bookmarked ? 'fas fa-heart' : 'far fa-heart' }}"></i> Bookmark
                                </button>
                            </form>
                            <span class="image-bookmark-cntr text-sm text-gray-500">{{ $image->bookmarkCount() }} bookmarks</span>
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

        <!-- Pagination Links -->
        <div class="pagination-btn mt-6">
            {{ $images->appends(request()->query())->links() }} <!-- Ensure query parameters are kept in pagination links -->
        </div>

    </div>
</body>

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
