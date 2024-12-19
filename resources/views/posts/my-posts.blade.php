<x-app-layout>
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <body>
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <div class="title">
                My Posts
            </div>

            <!-- Create New Post -->
            <a href="{{ route('posts.create') }}" class="upload-btn px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                Create New Post
            </a>

            <!-- Search and Filter Form -->
            <div class="search-function">
                <form method="GET" action="{{ route('posts.my-posts') }}" class="flex items-center mb-4">
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
                    <a href="{{ route('posts.my-posts') }}" class="clear-btn text-blue-500 hover:text-blue-700 ml-4 clear-btn">Clear Search</a>
                </form>
            </div>

            <!-- Post List Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                    <div class="post-cntr bg-white shadow-md rounded-lg overflow-hidden">
                        <!-- Add a link to the view page -->
                        <div class="post-container">
                            <a href="{{ route('posts.show', $post->id) }}">
                                <!-- Carousel Container -->
                                <div class="relative">
                                    <div class="carousel-container overflow-hidden">
                                        <div class="carousel-images flex transition-transform duration-500" id="carousel-images-{{ $post->id }}">
                                            @foreach ($post->images as $image)
                                                <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $post->title }}">
                                            @endforeach
                                        </div>
                                    </div>
                                    </a>
                                    <!-- Carousel Navigation Buttons -->
                                    <button class="absolute top-1/2 left-2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 p-2 rounded-full prevBtn" id="prevBtn-{{ $post->id }}">←</button>
                                    <button class="absolute top-1/2 right-2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 p-2 rounded-full nextBtn" id="nextBtn-{{ $post->id }}">→</button>
                                </div>
                            </div>

                        <div class="p-4">
                            <h2 class="post-title text-lg font-bold text-gray-800">{{ $post->title }}</h2>
                            <p class="post-author text-gray-500 text-sm">Posted by: {{ $post->user->name }}</p>
                            <div class="mt-2">
                                <strong>Tags:</strong>
                                <ul class="post-tags-text list-none">
                                    @foreach ($post->tags as $tag)
                                        <li class="post-tags inline-block text-sm text-white">{{ $tag->name }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Bookmark Button -->
                            <div class="bookmark-cntr flex items-center mt-2 space-x-2 mt-auto">
                                <form action="{{ route('posts.bookmark', $post->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-600">
                                        <i class="{{ $post->is_bookmarked ? 'fas fa-heart' : 'far fa-heart' }}"></i> Bookmark
                                    </button>
                                </form>
                                <span class="post-bookmark-cntr text-sm text-gray-500">{{ $post->bookmarkCount() }} bookmarks</span>
                            </div>

                            <div class="mt-4 flex space-x-2">
    <!-- Edit Button -->
    <button class="edit-button">
        <a href="{{ route('posts.edit', $post->id) }}" class="text-white">Edit</a>
    </button>

    <!-- Delete Button -->
    <form id="delete-form-{{ $post->id }}" action="{{ route('posts.destroy', $post->id) }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
    <button class="delete-button" onclick="confirmDelete({{ $post->id }})">
        Delete
    </button>
</div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="pagination-btn mt-6">
                {{ $posts->appends(request()->query())->links() }}
            </div>

        </div>
    </body>

    <script>
        function confirmDelete(postId) {
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
                        text: 'The post has been deleted successfully.',
                        icon: 'success',
                        showConfirmButton: true,
                        timer: 1500
                    }).then(() => {
                        // Submit the form after success message
                        document.getElementById('delete-form-' + postId).submit();
                    });
                }
            });
        }

        // Carousel functionality
        document.querySelectorAll('.carousel-container').forEach((carouselContainer) => {
            const postId = carouselContainer.querySelector('.carousel-images').id.split('-')[2];
            const prevBtn = document.getElementById(`prevBtn-${postId}`);
            const nextBtn = document.getElementById(`nextBtn-${postId}`);
            const carouselImages = document.getElementById(`carousel-images-${postId}`);
            let currentIndex = 0;

            function moveCarousel() {
                const totalImages = carouselImages.children.length;
                if (currentIndex < 0) currentIndex = totalImages - 1;
                if (currentIndex >= totalImages) currentIndex = 0;
                const offset = -currentIndex * 100;
                carouselImages.style.transform = `translateX(${offset}%)`;
            }

            prevBtn.addEventListener('click', () => {
                currentIndex--;
                moveCarousel();
            });

            nextBtn.addEventListener('click', () => {
                currentIndex++;
                moveCarousel();
            });
        });

           // SweetAlert for bookmarking/unbookmarking success
    @if(session('bookmark_success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session("bookmark_success") }}',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
        });
    @endif
    </script>
</x-app-layout>
