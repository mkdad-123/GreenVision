<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    private string $base;

    public function __construct()
    {
        $this->base = config('services.ai_gateway.base');
    }

    public function triage(Request $req)
    {
        if (!$req->hasFile('file')) {
            return response()->json(['message' => 'الملف مطلوب'], 400);
        }
        $file = $req->file('file');
        $plant = $req->input('plant');

        $res = Http::attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                   ->attachHeaders(['Content-Type' => 'multipart/form-data'])
                   ->post($this->base.'/vision/triage', [
                       'plant' => $plant
                   ]);

        return response()->json($res->json(), $res->status());
    }

    public function symptoms(Request $req)
    {
        $res = Http::get($this->base.'/kbs/symptoms', $req->query());
        return response()->json($res->json(), $res->status());
    }

    public function diagnose(Request $req)
    {
        $res = Http::post($this->base.'/kbs/diagnose', $req->all());
        return response()->json($res->json(), $res->status());
    }
}
