<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Shop\Category;

class DownloadPdfController extends Controller
{
    public function downloadAll() {
        $categories = Category::all();

        $pdf = Pdf::loadView('filament.pdf.category.all', ['categories' => $categories->toArray()]);

        return $pdf->stream('categorie.pdf');
    }
}
