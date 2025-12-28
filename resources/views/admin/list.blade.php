@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 50px">
    <h3>User Lists</h3>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if(!$user->is_approved)
                        <form method="POST" action="{{ route('admin.approve', $user) }}">
                            @csrf
                            <button class="btn btn-success btn-sm">Approve</button>
                        </form>
                    @else
                        <span class="badge " style="background-color: green;">Approved</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection
