<!DOCTYPE html>
<html>
<head>
    <title>Top Viewed Posts Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
            background-color: black;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: black;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Top Viewed Posts Report</h2>
    <table>
        <thead>
            <tr>
                <th>Post ID</th>
                <th>Post Title</th>
                <th>Category</th>
                <th>Views</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                @foreach ($category->posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $category->name }}</td>
                        <td><strong>{{ $post->visitors_count }}</strong></td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
