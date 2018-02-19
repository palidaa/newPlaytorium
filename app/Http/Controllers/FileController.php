<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\File;

class FileController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'fileToUpload' => 'required|max:5000'
        ]);
        if($request->hasFile('fileToUpload')) {
            $file = File::updateOrCreate(
                [
                    'name' => $request->file('fileToUpload')->getClientOriginalName(),
                    'prj_no' => $request->input('prj_no')
                ],
                [
                    'size' => $request->file('fileToUpload')->getClientSize(),
                    'updated_at' => date("Y-m-d H:i:s")
                ]
            );
            Storage::putFileAs('files/' . $request->input('prj_no'), $request->file('fileToUpload'), $request->file('fileToUpload')->getClientOriginalName());
        }
        return redirect()->back();
    }

    public function download(Request $request)
    {
        $path = storage_path('app/files/' . $request->input('prj_no') . '/' . $request->input('name'));
        return response()->download($path);
    }

    public function delete(Request $request)
    {
        File::where('name', $request->input('name'))
            ->where('prj_no', $request->input('prj_no'))
            ->delete();
        Storage::delete('files/' . $request->input('prj_no') . '/' . $request->input('name'));
        return redirect()->back();
    }
}
