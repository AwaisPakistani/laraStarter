<?php

namespace App\Http\Controllers;
use App\Jobs\ImportExcelJob;
use App\Jobs\ExportExcelJob;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:512000',
            ],
        ]);

        $path = $request->file('file')->store('imports');

        ImportExcelJob::dispatch($path);

        return response()->json([
            'message' => 'Sales import has been queued successfully.',
            'file' => $path,
        ]);
    }

   public function export() 
   { 
        $timestamp = now()->format('Y_m_d_H_i_s');
        $fileNameOnly = 'sales_' . $timestamp . '.xlsx';
        $fullPath = 'exports/' . $fileNameOnly;

        // Dispatch job
        ExportExcelJob::dispatch($fullPath, auth()->user()); 

        // return redirect()->back()->with([
        //     'success' => 'Sales export has been queued! Click the button to download once ready.',
        //     'export_file' => $fileNameOnly,
        // ]); 

        return redirect()->back()->with('success', 'Sales export has been queued! We will email you the download link when it is ready.');
    }
}
