@extends('layouts.app')

@section('content')

<h4>Add Staff</h4>

<form method="POST" action="/staff/store">
@csrf

<input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
<input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
<input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

<select name="role" class="form-control mb-2">
    <option value="staff">Staff</option>
</select>

<button class="btn btn-primary">Save</button>

</form>

@endsection
