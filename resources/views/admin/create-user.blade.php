@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 50px">
    <h3>Create User</h3>

    <form method="POST" action="{{ route('admin.store') }}">
        @csrf

        <input class="form-control mb-2" name="name" placeholder="Name">
        <input class="form-control mb-2" name="email" placeholder="Email">
        <input class="form-control mb-2" name="phone_number" placeholder="Phone Number">
        <input class="form-control mb-2" type="password" name="password" placeholder="Password">

        <select class="form-control mb-3" name="role">
            <option value="reporter">Reporter</option>
            <option value="designated">Designated</option>
        </select>

        <button class="btn btn-primary">Create User</button>
    </form>
</div>
@endsection
