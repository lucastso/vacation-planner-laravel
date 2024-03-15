<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function index($id)
    {
        $holiday = Holiday::findOrFail($id);

        $participantIds = explode(",", $holiday->participants);
        $participants = User::whereIn("id", $participantIds)
            ->pluck("name", "id")
            ->toArray();

        $pdf = PDF::loadView("pdf.holiday", compact("holiday", "participants"));

        return $pdf->download("holiday.pdf");
    }
}
