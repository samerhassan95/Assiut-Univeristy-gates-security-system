@extends('dashboard')

@section('title', 'إنشاء بوابة')

@section('content')
    <h1>إنشاء بوابة</h1>

    <form action="{{ route('gates.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">البوابة</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
        </div>
        <!-- Other fields as needed -->
        <button type="submit" class="btn btn-primary">إنشاء</button>
    </form>
@endsection
