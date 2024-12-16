<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <img class="w-full h-96 object-cover" src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->title }}">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800">{{ $image->title }}</h1>
                <p class="text-gray-600 mt-2">Uploaded by: User #{{ $image->user_id }}</p>

                <div class="mt-4">
                    <strong>Tags:</strong>
                    @foreach ($image->tags as $tag)
                        <span class="inline-block bg-gray-200 text-sm text-gray-600 px-2 py-1 rounded">{{ $tag->name }}</span>
                    @endforeach
                </div>

                <div class="mt-4 flex items-center">
                    <!-- Bookmark Button -->
                    <form action="{{ route('images.bookmark', $image->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-600">
                            <i class="{{ $image->is_bookmarked ? 'fas fa-heart' : 'far fa-heart' }}"></i> Bookmark
                        </button>
                    </form>
                    <span class="text-sm text-gray-500 ml-2">{{ $image->bookmarkCount() }} bookmarks</span>
                </div>

                <!-- Edit and Delete Buttons for Image Owner -->
                @if(auth()->id() === $image->user_id || auth()->user()->isAdmin())
                    <div class="mt-4 flex space-x-2">
                        <!-- Edit Button -->
                        <a href="{{ route('images.edit', $image->id) }}" class="px-4 py-2 text-black bg-yellow-500 border border-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-all">
                            Edit
                        </a>
                        <!-- Delete Button -->
                        <form id="delete-form-{{ $image->id }}" action="{{ route('images.destroy', $image->id) }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button class="px-4 py-2 text-black bg-red-500 border border-red-600 rounded hover:bg-red-600 hover:text-white transition-all"
                                onclick="confirmDelete({{ $image->id }})">
                            Delete
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Comments Section -->
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-800">Comments</h2>

            <!-- Add a Comment -->
            <form id="comment-form" action="{{ route('comments.store', $image->id) }}" method="POST" onsubmit="return confirmPostComment(event)">
                @csrf
                <textarea name="content" placeholder="Write a comment..." required></textarea>
                <button type="submit" class="text-white">Post Comment</button>
            </form>

            <!-- List of Comments -->
            <div class="mt-6 space-y-4">
                @foreach ($image->comments as $comment)
                    <div class="bg-gray-100 p-4 rounded">
                        <p class="text-gray-800">{{ $comment->content }}</p>
                        <div class="mt-2 text-sm text-gray-500">
                            By User #{{ $comment->user_id }} - {{ $comment->created_at->diffForHumans() }}
                        </div>
                        <div class="mt-2 flex items-center">
                            <form action="{{ route('comments.like', $comment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-600">
                                    <i class="{{ $comment->isLikedBy(auth()->user()) ? 'fas fa-thumbs-up' : 'far fa-thumbs-up' }}"></i> Like
                                </button>
                            </form>
                            <span class="text-sm text-gray-500 ml-2">{{ $comment->likes->count() }} likes</span>

                            @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                                <!-- Delete Comment Button -->
                                <form id="delete-comment-form-{{ $comment->id }}" action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button onclick="confirmDeleteComment({{ $comment->id }})" class="ml-4 text-red-500 hover:text-red-600">
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
</script>
