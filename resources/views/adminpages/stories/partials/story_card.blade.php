<tr>
    <td>{{ $story->id }}</td>
    <td>
        <a href="{{ route('admin.user-stories.show', $story->id) }}">
            {{ Str::limit($story->title, 50) }}
        </a>
    </td>
    <td>{{ $story->author }}</td>
    <td>{{ $story->category_name }}</td>
    <td>
        @if(isset($story->publication_status_name))
            <span class="badge bg-{{ $story->publication_status === 'approved' ? 'success' : ($story->publication_status === 'rejected' ? 'danger' : 'warning') }}">
                {{ $story->publication_status_name }}
            </span>
        @endif
    </td>
    <td>{{ $story->created_at->format('M j, Y') }}</td>
    <td>
        @include('admin.user-stories.partials._actions', ['story' => $story])
    </td>
</tr>