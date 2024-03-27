<!-- stats.blade.php -->

@extends('dashboard') <!-- Assuming you have a master layout -->

@section('content')
    <div class="container" >
        
        @foreach($gates as $gate)
        <div class="card">
            <div class="card-header">
                <h4>{{ $gate->name }}</h4>
            </div>
            <div class="card-body">
                <!-- Other content for each gate -->
                
                <h3>الغير مصرح لهم بالدخول</h3>
                <p>عدد الموظفين الغير مصرح لهم: <span id="disallowedStaffCount_{{ $gate->id }}">{{ $gate->disallowedStaffCount() }}</span></p>
                <p>عدد الطلبة الغير مصرح لهم: <span id="disallowedStudentsCount_{{ $gate->id }}">{{ $gate->disallowedStudentsCount() }}</span></p>
                <p>كليات الطلبة الغير مصرح لهم:</p>
                <ul>
                    @foreach($gate->disallowedStudentsByFaculty() as $faculty => $count)
                    <li>{{ $faculty }}: <span id="disallowedStudentsByFaculty_{{ $gate->id }}">{{ $count }}</span></li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <p>عدد الطلاب (دخول) اليوم: <span id="studentEnteredCount_{{ $gate->id }}">{{ $gate->student_entered_today_count }}</span></p>
                <p>عدد الطلاب  (خروج) اليوم: <span id="studentExitedCount_{{ $gate->id }}">{{ $gate->student_outer_today_count }}</span></p>
                <p>عدد الموظفين (دخول) اليوم: <span id="staffEnteredCount_{{ $gate->id }}">{{ $gate->staff_entered_today_count }}</span></p>
                <p>عدد الموظفين (خروج) اليوم: <span id="staffExitedCount_{{ $gate->id }}">{{ $gate->staff_outer_today_count }}</span></p>
                <p>مجموع الزيارات (دخول) اليوم: <span id="totalEnteredVisitsCount_{{ $gate->id }}">{{ $gate->studentEnteredToday()->count() + $gate->staffEnteredToday()->count() }}</span></p>
                <p>مجموع الزيارات (خروج) اليوم: <span id="totalExitedVisitsCount_{{ $gate->id }}">{{ $gate->studentOuterToday()->count() + $gate->staffOuterToday()->count() }}</span></p>
                    <!-- Add a form for date filtering -->
                    <form action="{{ route('gate.details', ['gateId' => $gate->id]) }}" method="get">
                        @csrf
                        <label for="start_date"> من تاريخ:</label>
                        <input type="datetime-local" id="start_date" name="start_date" required>
                        
                        <label for="end_date">إلى تاريخ:</label>
                        <input type="datetime-local" id="end_date" name="end_date" required>

                        <button type="submit">تصفية</button>
                    </form>

                    <!-- Display additional details based on the filtered date -->

               <!-- -->
                </div>
            </div>
        @endforeach

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
            // Ajax script for dynamic updates
            function updateDashboard() {
                @foreach($gates as $gate)
                    $.ajax({
                        url: '{{ route("update.gate.details", ['gateId' => $gate->id]) }}',
                        method: 'GET',
                        data: {
                            start_date: $('#start_date').val(),
                            end_date: $('#end_date').val()
                        },
                        success: function (data) {
                            // Update the HTML elements with the received data
                            $('#studentEnteredCount_{{ $gate->id }}').text(data.studentEnteredTodayCount);
                            $('#studentExitedCount_{{ $gate->id }}').text(data.studentOuterTodayCount);
                            $('#staffEnteredCount_{{ $gate->id }}').text(data.staffEnteredTodayCount);
                            $('#staffExitedCount_{{ $gate->id }}').text(data.staffOuterTodayCount);
                            $('#totalEnteredVisitsCount_{{ $gate->id }}').text(data.staffEnteredTodayCount+data.studentEnteredTodayCount);
                            $('#totalExitedVisitsCount_{{ $gate->id }}').text(data.studentOuterTodayCount+data.staffOuterTodayCount);
                           $('#disallowedStaffCount_{{ $gate->id }}').text(data.disallowedStaffCount);
                            $('#disallowedStudentsCount_{{ $gate->id }}').text(data.disallowedStudentsCount);
                            $('#disallowedStudentsByFaculty_{{ $gate->id }}').text(data.disallowedStudentsByFaculty);
                            $('#currentlyInsideCount_{{ $gate->id }}').text(data.currentlyInsideCount);


                        },
                        error: function (error) {
                            console.log('Error fetching data for gate {{ $gate->id }}:', error);
                            // Handle errors if needed
                        }
                    });
                @endforeach

                // Call the function again after 2 seconds
                setTimeout(updateDashboard, 2000);
            }

            // Call the function initially
            updateDashboard();
        </script>
    </div>
@endsection
