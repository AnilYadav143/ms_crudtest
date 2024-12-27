<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function csvUpload(){
        return view('csv_upload');
    }
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:5120',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Invalid file uploaded. Please upload a valid CSV file.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = fopen($request->file('file'), 'r');
        $header = fgetcsv($file);

        $importedCount = 0;
        $skippedRows = [];
        $rowNumber = 1; // To track rows for errors based on indexing

        while ($row = fgetcsv($file)) {
            $rowNumber++;
            $userData = array_combine($header, $row);

            // Validate row data
            $rowValidator = Validator::make($userData, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            if ($rowValidator->fails()) {
                $skippedRows[] = "Row $rowNumber: " . implode(', ', $rowValidator->errors()->all());
                continue;
            }

            // Create user
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);

            $importedCount++;
        }

        fclose($file);

        if ($importedCount > 0) {
            Alert::success('Success', "$importedCount Users Imported successfully!");
        }

        if (!empty($skippedRows)) {
            Alert::warning('Skipped Rows', implode('<br>', $skippedRows));
        }

        return redirect()->route('csv-upload');
    }


    public function export()
    {
        $users = User::all();

        $fileName = 'users.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'email', 'password']);

            foreach ($users as $user) {
                fputcsv($file, [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $user->password,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


}
