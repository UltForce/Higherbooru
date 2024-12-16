<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">My Bookmarked Images</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($bookmarkedImages as $image)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->title }}">
                    <div class="p-4">
                        <h2 class="text-lg font-bold text-gray-800">{{ $image->title }}</h2>
                        <p class="text-gray-500 text-sm">Uploaded by: User #{{ $image->user_id }}</p>
                    </div>
                    <div class="mt-2">
                <strong>Tags:</strong>
                <ul class="list-none">
                    @foreach ($image->tags as $tag)
                        <li class="inline-block text-sm text-gray-600">{{ $tag->name }}</li>
                    @endforeach
                </ul>
            </div>
                    <div class="flex items-center mt-2 space-x-2">
                        <!-- Bookmark Button with Conditional Heart Icon -->
                        <form action="{{ route('images.bookmark', $image->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-600">
                                @if(auth()->user()->bookmarks()->where('image_id', $image->id)->exists())
                                    <i class="fas fa-heart"></i> Unbookmark
                                @else
                                    <i class="far fa-heart"></i> Bookmark
                                @endif
                            </button>
                        </form>
                        <span class="text-sm text-gray-500">{{ $image->bookmarkCount() }} bookmarks</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
