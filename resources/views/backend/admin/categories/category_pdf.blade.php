<!DOCTYPE html>
<html>
<head>
    <title>Categories Report</title>
    <style>
        /* table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        h2 {
            text-align: center;
            background-color: black;
            color: white;
            padding: 10px;
            border-radius: 5px;
        } */
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
    <h2>Categories Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Type</th>
                <th>Number of Posts</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->type }}</td>
                    <td>{{ $category->posts->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
