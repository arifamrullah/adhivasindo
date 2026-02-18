<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller  
{
    public function search(Request $request)
    {
        $nama = $request->query('nama');
        $nim = $request->query('nim');
        $ymd = $request->query('ymd');

        $response = Http::get(
            'https://ogienurdiana.com/career/ecc694ce4e7f6e45a5a7912cde9fe131'
        );

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Failed fetch data'
            ], 500);
        }

        $rawData = $response->json()['DATA'];

        $lines = explode("\n", trim($rawData));
        
        array_shift($lines);
        
        $results = [];

        foreach ($lines as $line) {
            [$YMD, $NAMA, $NIM] = explode('|', $line);

            if (
                ($nama && strcasecmp($NAMA, $nama) !== 0) ||
                ($nim && $NIM !== $nim) ||
                ($ymd && $YMD !== $ymd)
            ) {
                continue;
            }

            $results[] = [
                'YMD' => $YMD,
                'NAMA' => $NAMA,
                'NIM' => $NIM,
            ];
        }

        return response()->json([
            'count' => count($results),
            'data' => $results,
        ]);
    }
}