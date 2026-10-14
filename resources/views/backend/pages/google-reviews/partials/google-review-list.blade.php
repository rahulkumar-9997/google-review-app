<table class="table table-nowrap align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Logo</th>
            <th>Name</th>
            <th>Address</th>
            <th>Added By</th>
            <th>Status</th>
            <th style="width: 210px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($shops as $shop)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if ($shop->logo)
                        <img src="{{ asset('storage/images/google-review/' . $shop->logo) }}" alt="{{ $shop->name }}"
                             width="40" height="40" style="object-fit:cover;border-radius:6px;">
                    @else
                        &mdash;
                    @endif
                </td>
                <td>{{ $shop->name }}</td>
                <td>{{ $shop->address }}</td>
                <td>{{ $shop->user->name ?? '—' }}</td>
                <td>
                    <span class="badge {{ $shop->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $shop->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div class="hstack gap-2">
                        <a href="{{ route('review.show', $shop->slug) }}" target="_blank" class="btn btn-sm btn-soft-success" title="Open public review page">
                            <i class="ri-external-link-line align-bottom"></i>
                        </a>
                        <a href="{{ route('manage-google-reviews.qrcode', ['manage_google_review' => $shop->id]) }}" class="btn btn-sm btn-soft-info" title="Download QR code">
                            <i class="ri-qr-code-line align-bottom"></i>
                        </a>
                        <a href="{{ route('manage-google-reviews.edit', ['manage_google_review' => $shop->id]) }}" class="btn btn-sm btn-soft-primary">
                            <i class="ri-pencil-line align-bottom"></i>
                        </a>
                        <form action="{{ route('manage-google-reviews.destroy', ['manage_google_review' => $shop->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-soft-danger show_confirm" data-name="shop">
                                <i class="ri-delete-bin-line align-bottom"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">No shops added yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $shops->links() }}