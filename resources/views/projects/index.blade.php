<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المشاريع</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>قائمة المشاريع</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>الاسم</th>
                    <th>الوصف</th>
                    <th>معرف المالك</th>
                    <th>معرف الحالة</th>
                    <th>البادئة</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->description }}</td>
                        <td>{{ $project->owner_id }}</td>
                        <td>{{ $project->status_id }}</td>
                        <td>{{ $project->ticket_prefix }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>