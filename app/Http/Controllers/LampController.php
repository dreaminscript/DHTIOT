<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lamp;

class LampController extends Controller
{
    public function __construct()
    {
        // pastikan record selalu ada
        if (Lamp::count() == 0) {
            Lamp::create([
                'lamp1' => 'off',
                'lamp2' => 'off',
                'lamp3' => 'off',
                'lamp4' => 'off',
                'lamp5' => 'off',
                'lamp6' => 'off',
            ]);
        }
    }

    // ============================
    // GET STATUS SEMUA LAMPU
    // ============================
    public function getLampStatus()
    {
        return response()->json(Lamp::first());
    }

    // ============================
    // GET SATU LAMPU (untuk ESP)
    // ============================
    public function getLamp($number)
    {
        if ($number < 1 || $number > 6) {
            return response()->json(['error' => 'Nomor lampu tidak valid'], 400);
        }

        $lamp = Lamp::first();
        $status = $lamp->{'lamp' . $number};

        return response()->json([
            'lamp' => $number,
            'status' => $status ?? 'off'
        ]);
    }

    // ============================
    // UPDATE 1 LAMPU
    // ============================
    public function controlLamp(Request $request)
{
    $request->validate([
        'lamp' => 'required|integer|min:1|max:6', // ✅ Ubah min= jadi min:
        'status' => 'required|in:on,off',
    ]);

    $lampColumn = 'lamp' . $request->lamp;
    $lamp = Lamp::first();

    $lamp->$lampColumn = $request->status;
    $lamp->save();

    return response()->json([
        'message' => "Lampu {$request->lamp} berubah menjadi {$request->status}",
        'lamp' => $lamp
    ]);
}

    // ============================
    // UPDATE SEMUA LAMPU
    // ============================
    public function controlAllLamps(Request $request)
    {
        $request->validate([
            'status' => 'required|in:on,off'
        ]);

        $lamp = Lamp::first();

        for ($i = 1; $i <= 6; $i++) {
            $lamp->{'lamp' . $i} = $request->status;
        }

        $lamp->save();

        return response()->json(['message' => "Semua lampu {$request->status}"]);
    }
}