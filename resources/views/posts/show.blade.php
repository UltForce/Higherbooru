<x-app-layout>
    <link href="{{ asset('css/show.css') }}" rel="stylesheet">
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="carousel-container overflow-hidden">
                                        <div class="carousel-images flex transition-transform duration-500" id="carousel-images-{{ $post->id }}">
                                            @foreach ($post->images as $image)
                                                <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $post->title }}">
                                            @endforeach
                                        </div>
                                    </div>
                                    <button class="absolute top-1/2 left-2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 p-2 rounded-full prevBtn" id="prevBtn-{{ $post->id }}">←</button>
                                    <button class="absolute top-1/2 right-2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 p-2 rounded-full nextBtn" id="nextBtn-{{ $post->id }}">→</button>
            <div class="info-container p-6">
                <h1 class="post-title text-2xl font-bold text-gray-800">{{ $post->title }}</h1>
                <p class="post-author text-gray-600 mt-2">Posted by: {{ $post->user->name }}</p>

                <div class="mt-4">
                <strong>Tags:</strong>
                    @foreach ($post->tags as $tag)
                        <span class="image-tags inline-block bg-gray-200 text-sm text-gray-600 px-2 py-1 rounded">{{ $tag->name }}</span>
                    @endforeach
                </div>

                <div class="mt-4">
                    <strong>Description:</strong>
                    <p class="post-description text-gray-800">{{ $post->description }}</p>
                </div>

                <div class="mt-4 flex items-center">
                    <!-- Bookmark Button -->
                    <div class="bookmark-cntr flex items-center mt-2 space-x-2 mt-auto">
                        <form action="{{ route('posts.bookmark', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-600">
                                <i class="{{ $post->is_bookmarked ? 'fas fa-heart' : 'far fa-heart' }}"></i> Bookmark
                            </button>
                        </form>
                        <span class="post-bookmark-cntr text-sm text-gray-500 ml-2">{{ $post->bookmarkCount() }} bookmarks</span>
                    </div>
                </div>

                <!-- Edit and Delete Buttons for Post Owner -->
                @if(auth()->id() === $post->user_id || auth()->user()->isAdmin())
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
                @endif
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comments-container mt-8">
            <h2 class="comments-header text-xl font-bold text-gray-800">Comments</h2>

            <!-- Add a Comment -->
            <div class="comment-form">
                <form id="comment-form" class="comment-field" action="{{ route('comments.store', $post->id) }}" method="POST" onsubmit="return confirmPostComment(event)">
                    @csrf
                    <textarea name="content" placeholder="Write a comment..." required class="comment-textarea"></textarea>
                    <button type="submit" class="post-comment-btn">Post Comment</button>
                </form>
            </div>

            <!-- List of Comments -->
            <div class="list-container mt-6 space-y-4">
                @foreach ($post->comments as $comment)
                    <div class="comment-box bg-gray-100 p-4 rounded">
                        <p class="comment-content text-gray-800">{{ $comment->content }}</p>
                        <div class="commenter-username mt-2 text-sm text-gray-500">
                            Commented by: {{ $comment->user->name }} - {{ $comment->created_at->diffForHumans() }}
                        </div>
                        <div class="likes-container mt-2 flex items-center">
                            <form action="{{ route('comments.like', $comment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="like-btn like-text text-blue-500 hover:text-red-600">
                                    <i class="{{ $comment->isLikedBy(auth()->user()) ? 'fas fa-thumbs-up' : 'far fa-thumbs-up' }}"></i> Like
                                </button>
                            </form>
                            <span class="like-counter text-sm text-gray-500 ml-2">{{ $comment->likes->count() }} likes</span>

                            @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                                <!-- Delete Comment Button -->
                                <form id="delete-comment-form-{{ $comment->id }}" action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button onclick="confirmDeleteComment({{ $comment->id }})" class="delete-text ml-4 text-red-500 hover:text-red-600">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function confirmPostComment(event) {
        event.preventDefault(); // Prevent default form submission

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to post this comment?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, post it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Posted!',
                    text: 'Your comment has been successfully posted.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Submit the form after confirmation
                    document.getElementById('comment-form').submit();
                });
            }
        });
    }

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

    function confirmDeleteComment(commentId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This comment will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'The comment has been deleted successfully.',
                    icon: 'success',
                    showConfirmButton: true,
                    timer: 1500
                }).then(() => {
                    // Submit the form
                    document.getElementById('delete-comment-form-' + commentId).submit();
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
