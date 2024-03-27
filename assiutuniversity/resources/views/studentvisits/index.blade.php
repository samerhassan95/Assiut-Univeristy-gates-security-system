@extends('dashboard')

@section('title', 'زيارات الطلاب')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>زيارات الطلاب</h1>
    </div>

    <form action="{{ route('student_visits.index') }}" method="GET">
        <!-- Create input fields for each search filter -->
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="nationalID">الرقم القومي</label>
                <input type="text" name="nationalID" id="nationalID" class="form-control" placeholder="بحث بالرقم القومي">
            </div>
            <div class="form-group col-md-3">
                <label for="name">الإسم</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="بحث بالإسم">
            </div>
            <div class="form-group col-md-3">
                <label for="faculty">الكلية</label>
                <input type="text" name="faculty" id="faculty" class="form-control" placeholder="بحث بالكلية">
            </div>
            <div class="form-group col-md-3">
                <label for="status">الحالة</label>
                <input type="text" name="status" id="status" class="form-control" placeholder="بحث بالحالة">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="from_datetime">من</label>
                <input type="datetime-local" name="from_datetime" id="from_datetime" class="form-control" placeholder="من">
            </div>
            <div class="form-group col-md-3">
                <label for="to_datetime">إلى </label>
                <input type="datetime-local" name="to_datetime" id="to_datetime" class="form-control" placeholder="إلى">
            </div>
            <div class="form-group col-md-3">
            <label for="gate_name">البوابة</label>
            <input type="text" name="gate_name" class="form-control" placeholder="بحث بالبوابة"> <!-- Include a field for Gate Name -->
</div>
<div class="form-group col-md-3">
<label for="enter_outer">دخول/خروج</label>
            <input type="text" name="enter_outer" class="form-control" placeholder="بحث بالدخول او الخروج">
</div>
            <!-- Add more search fields as needed -->
        </div>
        <button type="submit" class="btn btn-primary">بحث</button>
    </form>

    <table class="table" id="student-visits-table" >
        <thead>
            <tr>
                <th>الرقم القومي</th>
                <th>الإسم</th>
                <th>الكلية</th>
                <th>الحالة</th>
                <th>الوقت و التاريخ</th>
                <th>البوابة</th>
                 <th>دخول/خروج</th>
                <!-- Add more table headings as needed -->
                <th>عرض تاريخ الزيارات</th> <!-- Add an extra column for the action button -->

            </tr>
        </thead>
        <tbody>
            @foreach($studentVisits as $visit)
                <tr>
                    <td>{{ $visit->nationalID }}</td>
                    <td>{{ $visit->name }}</td>
                    <td>{{ $visit->faculty }}</td>
                    <td>{{ $visit->status }}</td>
                    <td>{{ date('Y-m-d H:i:s', strtotime($visit->visit_datetime)) }}</td>
                    <td>{{ $visit->gate_name }}</td>
                    <td>{{ $visit->enter_outer }}</td>
                    <td>
                <!-- Add a button to view the history for this specific student visit -->
                <a href="{{ route('studentvisits.history', ['nationalID' => $visit->nationalID]) }}" class="btn btn-primary">عرض التاريخ</a>
            </td>
                    <!-- Add more table cells for other columns if required -->
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
    {{ $studentVisits->links('vendor.pagination.bootstrap-4') }}
</div>
<!-- Add this to your HTML -->
<!-- Add this to your HTML -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script>
function updateStudentVisits() {
    $.ajax({
        url: '{{ route('student_visits.index') }}',
        method: 'GET',
        dataType: 'json',
        data: {
            nationalID: $('input[name="nationalID"]').val(),
                name: $('input[name="name"]').val(),
                faculty: $('input[name="faculty"]').val(),
                gate_name: $('input[name="gate_name"]').val(),
                status: $('input[name="status"]').val(),
                enter_outer: $('input[name="enter_outer"]').val(),
                from_datetime: $('input[name="from_datetime"]').val(),
                to_datetime: $('input[name="to_datetime"]').val(),
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response && response.data) {
                // Clear existing table rows
                $('#student-visits-table tbody').empty();

                // Append new data to the table
                response.data.data.forEach(function(visit) {
                    var row = '<tr>' +
                        '<td>' + visit.nationalID + '</td>' +
                        '<td>' + visit.name + '</td>' +
                        '<td>' + visit.faculty + '</td>' +
                        '<td>' + visit.status + '</td>' +
                        '<td>' + visit.visit_datetime + '</td>' +
                        '<td>' + visit.gate_name + '</td>' +
                        '<td>' + visit.enter_outer + '</td>' +
                        '<td>' +
                        '<a href="/studentvisits/history/' + visit.nationalID + '" class="btn btn-primary">عرض التاريخ</a>' +
                        '</td>' +
                        '</tr>';

                    $('#student-visits-table tbody').append(row);
                });
            }     else {
                console.error('Invalid response format. Expected JSON with data.');
            }
        },
        error: function(error) {
            console.error('Error updating student visits:', error);
        }
    });
}

// Call the updateStudentVisits function on page load or after a new visit is created
$(document).ready(function() {
    updateStudentVisits();
    // Optionally, you can set an interval for periodic updates
    setInterval(updateStudentVisits, 2000); // Update every 5 seconds (adjust as needed)
});


</script>


@endsection
