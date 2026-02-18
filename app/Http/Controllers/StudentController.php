<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller  
{
    public function search(Request $request)
    {
        $nim = $request->query('nim');
        $nama = $request->query('nama');
        $ymd = $request->query('ymd');

        $response = Http::get('https://ogienurdiana.com/career/ecc694ce4e7f6e45a5a7912cde9fe131');

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Failed fetch data'
            ], 500);
        }

        $rawData = $response->json()['DATA'];

        $lines = explode("\n", trim($rawData));
        
        $headerLine = array_shift($lines);
        $headers = array_map('strtoupper', explode('|', $headerLine));
        
        $results = [];

        foreach ($lines as $line)
        {
            $columns = explode('|', $line);

            $row = array_combine($headers, $columns);

            $NIM = $row['NIM'] ?? null;
            $NAMA = $row['NAMA'] ?? null;
            $YMD = $row['YMD'] ?? null;
            
            if (
                ($nim && $NIM !== $nim) ||
                ($nama && strcasecmp($NAMA, $nama) !== 0) ||
                ($ymd && $YMD !== $ymd)
            ) {
                continue;
            }

            $results[] = [
                'NIM' => $NIM,
                'NAMA' => $NAMA,
                'YMD' => $YMD,
            ];
        }

        return response()->json([
            'count' => count($results),
            'data' => $results,
        ]);
    }
}