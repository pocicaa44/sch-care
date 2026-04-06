<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserSettingController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return response()->json([
            'auto_delete_days' => $user->auto_delete_days,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'auto_delete_days' => ['required', 'integer', Rule::in([3, 7, 14, 21, 30])],
        ]);

        $user = Auth::user();
        $user->update([
            'auto_delete_days' => $request->auto_delete_days,
        ]);

        return response()->json([
            'message' => 'Pengaturan berhasil diubah',
            'data' => [
                'auto_delete_days' => $user->auto_delete_days,
            ],
        ]);
    }
}
