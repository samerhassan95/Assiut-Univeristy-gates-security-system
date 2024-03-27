@extends('dashboard')

@section('title', 'Create Staff Visit')

@section('content')
    <h1>Create Staff Visit</h1>

    <form action="{{ route('staff_visits.store') }}" method="POST">
        @csrf

        <div>
            <label for="nationalID">National ID:</label>
            <input type="text" id="nationalID" name="nationalID" required>
        </div>

        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div>
            <label for="position">Position:</label>
            <input type="text" id="position" name="position" required>
        </div>

        <div>
            <label for="datetime">Date and Time:</label>
            <input type="datetime-local" id="datetime" name="datetime[]" required>
            <!-- Use JavaScript to add more datetime inputs dynamically -->
            <button type="button" onclick="addDateTimeInput()">Add More</button>
        </div>

        <button type="submit">Submit</button>
    </form>

    <script>
        function addDateTimeInput() {
            const datetimeDiv = document.createElement('div');
            const label = document.createElement('label');
            label.textContent = 'Date and Time:';
            const input = document.createElement('input');
            input.type = 'datetime-local';
            input.name = 'datetime[]';
            input.required = true;
            datetimeDiv.appendChild(label);
            datetimeDiv.appendChild(input);

            const form = document.querySelector('form');
            form.insertBefore(datetimeDiv, form.querySelector('button'));
        }
    </script>
@endsection
