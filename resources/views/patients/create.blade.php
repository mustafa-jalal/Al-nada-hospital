@extends('layouts.app')

@section('content')
    <h1>Add New Patient</h1>
    <form action="{{ route('patients.store') }}" method="POST">
        @csrf
        <input type="text" name="national_number" required placeholder="National Number">
        
        <label for="gender">Gender:</label>
        <select name="gender" id="gender">
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
        
        <input type="text" name="address" placeholder="Address">
        <input type="text" name="phone_number" placeholder="Phone Number">
        <button type="submit">Add Patient</button>
    </form>
@endsection