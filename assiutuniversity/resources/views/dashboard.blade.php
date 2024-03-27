<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        html {
    direction: rtl;
    text-right
}
        /* Your common styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f4ee; /* Light background color */
        }
        .navbar {
            background-color: #272321; /* Darker gray for navbar */
            color: white;
            padding: 10px;
            text-align: center;
        }
        .content {
            flex: 1;
            display: flex;
            min-height: calc(100vh - 40px); /* Adjust for navbar height */
        }
        /* Sidebar styles */
        .sidebar {
            width: 250px;
            background-color: #cdad3d; /* Dark goldenrod for sidebar */
            color: #272321; /* Darker gray for text */
            padding-top: 20px;
            min-height: 100%;
        }
        .sidebar a {
            display: block;
            padding: 15px;
            color: #272321; /* Darker gray for text */
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #d5c58a; /* Lighter goldenrod on hover */
        }
        /* Main content styles */
        .main-content {
            padding: 20px;
            flex: 1;
            background-color: #979592; /* Grayish for content area */
        }
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #c4c3c3; /* Lighter gray for borders */
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #6c6c66; /* Darker gray for table headers */
            color: white;
        }
        tr:nth-child(even) {
            background-color: #c4c3c3; /* Light gray for even table rows */
        }
        .logo img {
            width: 100px; /* Adjust logo width as needed */
            height: auto; /* Maintain aspect ratio */
        }
    </style>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyBkC" crossorigin="anonymous">

</head>
<body>
    <div class="navbar">
    <div class="logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
        </div>
        <h2>لوحة تحكم جامعة أسيوط</h2>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf <!-- CSRF protection -->
        <button type="submit">تسجيل خروج</button>
    </form>
    <div class="content" >
        <div class="sidebar">
        <a href="/users">المستخدمين</a>
        <a href="/staff_visits">زيارات العاملين</a>
        <a href="/student_visits">زيارات الطلاب</a>
        <a href="/gates">البوابات</a>
        <a href="/faculties">الكليات</a>
        <a href="/staff">العاملين</a>
        <a href="/students">الطلاب</a>
        <a href="/stats">الإحصائيات</a>


        </div>
        <div class="main-content">
            @yield('content')
        </div>
    </div>
</body>
</html>
