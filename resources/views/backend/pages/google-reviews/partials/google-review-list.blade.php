<div class="table-responsive">
    <table class="table table-nowrap align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width:50px;">#</th>
                <th style="width:80px;">Logo</th>
                <th>Name</th>
                <th>Address</th>
                <th style="width:140px;">Added By</th>
                <th style="width:100px;">Status</th>
                <th style="width:150px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($shops as $shop)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if ($shop->logo)
                            <img src="{{ asset('storage/images/google-review/' . $shop->logo) }}"
                                 alt="{{ $shop->name }}"
                                 width="40" height="40"
                                 style="object-fit:cover;border-radius:6px;">
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
                        {{-- Primary action: Edit --}}
                        <a href="{{ route('manage-google-reviews.edit', ['manage_google_review' => $shop->id]) }}"
                           class="btn btn-sm btn-soft-primary">
                            <i class="ri-pencil-line align-bottom"></i> Edit
                        </a>

                        {{-- More actions dropdown --}}
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-sm btn-soft-secondary" type="button"
                                    data-bs-toggle="dropdown" data-bs-boundary="viewport"
                                    aria-expanded="false">
                                <i class="ri-more-2-fill align-bottom"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" target="_blank"
                                       href="{{ route('review.show', $shop->slug) }}">
                                        <i class="ri-external-link-line align-bottom me-2 text-muted"></i>
                                        View Public Page
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('manage-google-reviews.qrcode', ['manage_google_review' => $shop->id]) }}">
                                        <i class="ri-qr-code-line align-bottom me-2 text-muted"></i>
                                        Download QR Code
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('manage-google-reviews.questions.index', $shop->id) }}">
                                        <i class="ri-survey-line align-bottom me-2 text-muted"></i>
                                        Manage Questions
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('manage-google-reviews.destroy', ['manage_google_review' => $shop->id]) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="dropdown-item text-danger show_confirm"
                                                data-name="shop">
                                            <i class="ri-delete-bin-line align-bottom me-2"></i>
                                            Delete Shop
                                        </button>
                                    </form>
                                </li>
                            </ul>
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
</div>
{{ $shops->links() }}