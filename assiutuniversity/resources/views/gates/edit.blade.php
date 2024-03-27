@extends('dashboard')

@section('title', 'تعديل بوابة')

@section('content')
    <h1>تعديل بوابة</h1>

    <form action="{{ route('gates.update', $gate->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">البوابة</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $gate->name }}">
        </div>
        <!-- Other fields as needed -->
        <button type="submit" class="btn btn-primary">تعديل</button>
    </form>
@endsection
