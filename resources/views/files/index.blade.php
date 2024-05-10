<!-- index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">File Management</div>

                <div class="card-body">
                    <!-- Search Form -->
                    <form action="{{ url('/files') }}" method="GET" class="form-inline mb-3">
                        <div class="form-group mr-2">
                            <input type="text" name="search" class="form-control" placeholder="Search by filename">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>

                    <!-- Upload Form -->
                    <form action="{{ url('/files') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <input type="file" name="file" class="form-control-file">
                        </div>
                        <button type="submit" class="btn btn-success">Upload</button>
                    </form>

                    <!-- Files Table -->
                    <table class="table mt-4">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>File Type</th>
                                <th>Size</th>
                                <th>Upload Date</th>
                                <th>Owner</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                            <tr>
                                <td>{{ $file->filename }}</td>
                                <td>{{ pathinfo($file->filename, PATHINFO_EXTENSION) }}</td>
                                <td>{{ round(Storage::size($file->filepath) / 1048576, 2) }}MB</td>
                                <td>{{ $file->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>USER.NAME</td>
                                <td>
                                    <a href="{{ url('/files/'.$file->id.'/download') }}" class="btn btn-sm btn-secondary">Download</a>
                                    <a href="{{ url('/files/'.$file->id.'/edit') }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ url('/files/'.$file->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this file?')">Delete</button>
                                    </form>
                                </td>
                                <!-- <td>
                                    <form action="{{ url('/files/'.$file->id.'/rename') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <input type="text" name="new_filename" class="form-control" placeholder="New Filename" required>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Rename</button>
                                    </form>
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
