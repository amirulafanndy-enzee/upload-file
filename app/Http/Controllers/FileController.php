<?php

namespace App\Http\Controllers;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    // public function index()
    // {
    //     $files = File::all();
    //     return view('files.index', compact('files'));
    // }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $files = File::query()
            ->where('filename', 'like', "%$search%")
            ->get();

        return view('files.index', compact('files'));
    }

    public function create()
    {
        return view('files.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $filepath = $file->storeAs('uploads', $filename);

        File::create([
            'filename' => $filename,
            'filepath' => $filepath,
        ]);

        return redirect('/files')->with('success', 'File uploaded successfully!');
    }

    public function edit($id)
    {
        $file = File::findOrFail($id);
        return view('files.edit', compact('file'));
    }

    public function update(Request $request, $id)
    {
        // Similar to store method, but update instead of create
    }

    // public function download($id)
    // {
    //     $file = File::findOrFail($id);
    //     $filePath = storage_path('app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $file->filename);

    //     // Check if the file exists
    //     if (!file_exists($filePath)) {
    //         // Log error
    //         \Log::error('File not found: ' . $filePath);
    //         abort(404);
    //     }

    //     // Log file path
    //     \Log::info('Download file path: ' . $filePath);

    //     // Download the file
    //     return response()->download($filePath, $file->filename);
    // }

    // public function download($id)
    // {
    //     $file = File::findOrFail($id);
    //     $filePath = storage_path('app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $file->filename);

    //     // Check if the file exists
    //     if (!file_exists($filePath)) {
    //         // Log error
    //         \Log::error('File not found: ' . $filePath);
    //         abort(404);
    //     }

    //     // Log file path
    //     \Log::info('Download file path: ' . $filePath);

    //     // Response headers
    //     $headers = [
    //         'Content-Type' => 'application/octet-stream', // Change content type if necessary
    //     ];

    //     // Download the file
    //     return Response::download($filePath, $file->filename, $headers);
    // }

    // public function download($id)
    // {
    //     $file = File::findOrFail($id);
    //     $filePath = storage_path('app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $file->filename);

    //     // Check if the file exists
    //     if (!file_exists($filePath)) {
    //         // Log error
    //         \Log::error('File not found: ' . $filePath);
    //         abort(404);
    //     }

    //     // Log file path
    //     \Log::info('Download file path: ' . $filePath);

    //     // Download the file
    //     return response()->download($filePath);
    // }

    
    // public function download($id)
    // {
    //     $file = File::findOrFail($id);
    //     $filePath = storage_path('app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $file->filename);

    //     // Check if the file exists
    //     if (!file_exists($filePath)) {
    //         // Log error
    //         \Log::error('File not found: ' . $filePath);
    //         abort(404);
    //     }

    //     // Log file path
    //     \Log::info('Download file path: ' . $filePath);

    //     // Response headers
    //     $headers = [
    //         'Content-Type' => 'application/octet-stream', // or 'application/pdf' for PDF files
    //     ];
    //     return response()->download($filePath, $file->filename, $headers);
    // }

    public function download($id)
    {
        $file = File::findOrFail($id);
        $filePath = storage_path('app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $file->filename);

        // Check if the file exists
        if (!file_exists($filePath)) {
            // Log error
            \Log::error('File not found: ' . $filePath);
            abort(404);
        }

        // Log file path
        \Log::info('Download file path: ' . $filePath);

        // Response headers
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $file->filename . '"',
        ];

        // Download the file
        return response()->download($filePath, null, $headers);
    }

    public function rename(Request $request, $id)
    {
        $request->validate([
            'new_filename' => 'required|string|max:255',
        ]);

        $file = File::findOrFail($id);
        $file->filename = $request->input('new_filename');
        $file->save();

        return redirect('/files')->with('success', 'File renamed successfully!');
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $file->delete();
        return redirect('/files')->with('success', 'File deleted successfully!');
    }
}
