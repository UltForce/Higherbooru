<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('images.index') }}" class="mb-4">
            <input type="text" name="title" placeholder="Search by title" value="{{ request('title') }}">
            <input type="text" name="tags" placeholder="Search by tags" value="{{ request('tags') }}">

            <!-- Sorting Dropdown -->
            <select name="sort" class="ml-4">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            </select>

            <button type="submit" class="ml-4 text-white">Search</button>

            <!-- Clear Search Button -->
            <a href="{{ route('images.index') }}" class="text-blue-500 hover:text-blue-700 ml-4">Clear Search</a>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($images as $image)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <!-- Add a link to the view page -->
                    <a href="{{ route('images.show', $image->id) }}">
                        <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->title }}">
                    </a>
                    <div class="p-4">
                        <h2 class="text-lg font-bold text-gray-800">{{ $image->title }}</h2>
                        <p class="text-gray-500 text-sm">Uploaded by: User #{{ $image->user_id }}</p>
                        <div class="mt-2">
                            <strong>Tags:</strong>
                            <ul class="list-none">
                                @foreach ($image->tags as $tag)
                                    <li class="inline-block text-sm text-gray-600">{{ $tag->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <!-- Bookmark Button -->
                        <div class="flex items-center mt-2 space-x-2">
                            <form action="{{ route('images.bookmark', $image->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-600">
                                    <!-- Conditional heart icon -->
                                    <i class="{{ $image->is_bookmarked ? 'fas fa-heart' : 'far fa-heart' }}"></i> Bookmark
                                </button>
                            </form>
                            <span class="text-sm text-gray-500">{{ $image->bookmarkCount() }} bookmarks</span>
                        </div>

                        <!-- Delete Button (Admin only) -->
                        @if(auth()->user()->isAdmin())
                            <div class="mt-4">
                                <form id="delete-form-{{ $image->id }}" action="{{ route('images.destroy', $image->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="text-red-500 hover:text-red-600" onclick="confirmDelete({{ $image->id }})">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $images->links() }}
        </div>
    </div>

    <!-- SweetAlert Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        showConfirmButton: false,
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
