@if($notifications->isEmpty())
    <p class="text-muted">No alerts at the moment 🚫</p>
@else
    <ul class="list-group">
        @foreach($notifications as $notification)
    <div class="d-flex justify-content-between align-items-center glass-section mb-2">
        <span>{{ $notification->message }}</span>

        @if(Auth::user()->role === 'bfp')
            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Mark this fire as under control?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    Fire Under Control
                </button>
            </form>
        @endif
    </div>
@endforeach
    </ul>
@endif
