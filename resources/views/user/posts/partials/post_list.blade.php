@foreach ($posts as $post)
<tr>
    <th scope="row">{{ $post->id }}</th>
    <td>{{ $post->title }}</td>
    <td>{{ $post->excerpt }}</td>
    <td>{{ $post->created_at }}</td>
    <td>
        @if(is_null($post->posted_at))
        Not Posted Yet
        @else
        {{ $post->posted_at }}
        @endif
    </td>
    <td>{{ $post->status }}</td>
    <td>
        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>
        <button type="button" class="btn btn-danger btn-sm delete-post-button" data-post-id="{{ $post->id }}">
            <i class="fas fa-trash-alt"></i> Delete
        </button>

    </td>

</tr>
@endforeach