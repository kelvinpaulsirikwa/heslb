<tr class="border-bottom">
    <td class="py-3">
        <span class="text-dark fw-medium">#{{ $story->id }}</span>
    </td>
    <td class="py-3">
        <a href="{{ route('admin.user-stories.show', $story->id) }}" class="text-dark fw-bold text-decoration-none">
            {{ Str::limit($story->message, 50) }}
        </a>
    </td>
    <td class="py-3">
        <span class="text-dark">{{ trim(($story->first_name ?? '') . ' ' . ($story->last_name ?? '')) ?: 'Anonymous' }}</span>
    </td>
    <td class="py-3">
        <span class="text-dark">{{ $story->created_at->format('M j, Y') }}</span>
    </td>
    <td class="py-3 text-center">
        <div class="btn-group" role="group">
            <!-- View Button -->
            <a href="{{ route('admin.user-stories.show', $story->id) }}" 
               class="btn btn-sm" 
               style="border: 2px solid #17a2b8; color: #17a2b8; background: white;" 
               title="View Details"
               data-bs-toggle="tooltip">
                <i class="fas fa-eye"></i>
            </a>
            
            <!-- Edit Button -->
            <a href="{{ route('admin.user-stories.edit', $story->id) }}" 
               class="btn btn-sm" 
               style="border: 2px solid #ffc107; color: #ffc107; background: white;" 
               title="Edit Story"
               data-bs-toggle="tooltip">
                <i class="fas fa-edit"></i>
            </a>
            
            <!-- Delete Button (Form) -->
            <form action="{{ route('admin.user-stories.destroy', $story->id) }}" 
                  method="POST" 
                  class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this story?')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="btn btn-sm" 
                        style="border: 2px solid #dc3545; color: #dc3545; background: white;" 
                        title="Delete Story"
                        data-bs-toggle="tooltip">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
            
            <!-- Approve Button (if not seen) -->
            @if($story->status === 'not seen')
                <form action="{{ route('admin.user-stories.approve', $story->id) }}" 
                      method="POST" 
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to approve this story?')">
                    @csrf
                    <button type="submit" 
                            class="btn btn-sm" 
                            style="border: 2px solid #28a745; color: #28a745; background: white;" 
                            title="Approve Story"
                            data-bs-toggle="tooltip">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
            @endif

            <!-- Reject Button (if not seen) -->
            @if($story->status === 'not seen')
                <form action="{{ route('admin.user-stories.reject', $story->id) }}" 
                      method="POST" 
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to reject this story?')">
                    @csrf
                    <input type="hidden" name="admin_notes" value="Rejected by admin">
                    <button type="submit" 
                            class="btn btn-sm" 
                            style="border: 2px solid #dc3545; color: #dc3545; background: white;" 
                            title="Reject Story"
                            data-bs-toggle="tooltip">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
            @endif

            <!-- Post Button (if seen but not published) -->
            @if($story->status === 'seen' && !$story->published)
                <form action="{{ route('admin.user-stories.post', $story->id) }}" 
                      method="POST" 
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to post this story to the website?')">
                    @csrf
                    <button type="submit" 
                            class="btn btn-sm" 
                            style="border: 2px solid #28a745; color: #28a745; background: white;" 
                            title="Post to Website"
                            data-bs-toggle="tooltip">
                        <i class="fas fa-globe"></i>
                    </button>
                </form>
            @endif
            
            <!-- Unpost Button (if published) -->
            @if($story->status === 'seen' && $story->published)
                <form action="{{ route('admin.user-stories.unpost', $story->id) }}" 
                      method="POST" 
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to remove this story from the website?')">
                    @csrf
                    <button type="submit" 
                            class="btn btn-sm" 
                            style="border: 2px solid #ffc107; color: #ffc107; background: white;" 
                            title="Remove from Website"
                            data-bs-toggle="tooltip">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </form>
            @endif
        </div>
    </td>
</tr>

 