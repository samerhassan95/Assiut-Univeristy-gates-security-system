@extends('dashboard')

@section('title', 'زيارات العاملين')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>زيارات العاملين</h1>
    </div>

    <form action="{{ route('staff_visits.index') }}" method="GET">
        <input type="text" name="nationalID" placeholder="بحث بالرقم القومي">
        <input type="text" name="name" placeholder="بحث بالإسم">
        <input type="text" name="position" placeholder="بحث بالوظيفة">
        <input type="datetime-local" name="from_datetime" placeholder="من">
        <input type="datetime-local" name="to_datetime" placeholder="إلي">
        <input type="text" name="gate_name" placeholder="بحث بالبوابة"> <!-- Include a field for Gate Name -->
        <input type="text" name="status" placeholder="بحث بالحالة">
        <input type="text" name="enter_outer" placeholder="بحث بالدخول او الخروج">
        <button type="submit" class="btn btn-primary">بحث</button>
    </form>

    <table class="table" id="staff-visits-table">
        <thead>
            <tr>
                <th>الرقم القومي</th>
                <th>الإسم</th>
                <th>الوظيفة</th>
                <th>الوقت و التاريخ</th>
                <th>البوابة</th>
                <th>الحالة</th>
                 <th>دخول/خروج</th><!-- Include the new table heading for Gate Name -->
                <th>تاريخ الزيارات</th>

                <!-- Add more table headings as needed -->
            </tr>
        </thead>
        <tbody>
            @foreach($staffVisits as $visit)
                <tr>
                    <td>{{ $visit->nationalID }}</td>
                    <td>{{ $visit->name }}</td>
                    <td>{{ $visit->position }}</td>
                    <td>{{ date('Y-m-d H:i:s', strtotime($visit->visit_datetime)) }}</td>
                    <td>{{ $visit->gate_name }}</td>
                    <td>{{ $visit->status }}</td>
                    <td>{{ $visit->enter_outer }}</td> <!-- Display the Enter/Outer status -->
                    <td>
                    <a href="{{ route('staffvisits.history', ['nationalID' => $visit->nationalID]) }}" class="btn btn-primary">عرض التاريخ</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
    {{ $staffVisits->links('vendor.pagination.bootstrap-4') }}
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<script>
    function updateStaffVisits() {
    $.ajax({
        url: '{{ route('staff_visits.index') }}',
        method: 'GET',
        dataType: 'json',
        data: {
            nationalID: $('input[name="nationalID"]').val(),
                name: $('input[name="name"]').val(),
                faculty: $('input[name="position"]').val(),
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
                $('#staff-visits-table tbody').empty();

                // Append new data to the table
                response.data.data.forEach(function(visit) {
                    var row = '<tr>' +
                        '<td>' + visit.nationalID + '</td>' +
                        '<td>' + visit.name + '</td>' +
                        '<td>' + visit.position + '</td>' +
                        '<td>' + visit.visit_datetime + '</td>' +
                        '<td>' + visit.gate_name + '</td>' +
                        '<td>' + visit.status + '</td>' +
                        '<td>' + visit.enter_outer + '</td>' +
                        '<td>' +
                        '<a href="/staffvisits/history/' + visit.nationalID + '" class="btn btn-primary">عرض التاريخ</a>' +
                        '</td>' +
                        '</tr>';

                    $('#staff-visits-table tbody').append(row);
                });
            }     else {
                console.error('Invalid response format. Expected JSON with data.');
            }
        },
        error: function(error) {
            console.error('Error updating staff visits:', error);
        }
    });
}

// Call the updateStudentVisits function on page load or after a new visit is created
$(document).ready(function() {
    updateStaffVisits();
    // Optionally, you can set an interval for periodic updates
    setInterval(updateStaffVisits, 2000); // Update every 5 seconds (adjust as needed)
});

</script>
@endsection
