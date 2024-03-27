@extends('dashboard')

@section('title', 'تعديل الكلية')

@section('content')
    <h1>تعديل الكلية</h1>

    <form action="{{ route('faculties.update', $faculty->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">الكلية</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $faculty->name }}">
        </div>
        <!-- Other fields as needed -->
        <button type="submit" class="btn btn-primary">تعديل</button>
    </form>
@endsection
