<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Shop\Category;
use Dompdf\Dompdf;

class DownloadPdfController extends Controller
{
    public function downloadAll() {
        $categories = Category::all();

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadView('filament.pdf.category.all', [
            'categories' => $categories->toArray(),
            'pdf' => $pdf
        ]);

        // $html = view('filament.pdf.category.all', ['categories' => $categories->toArray()])->render();
        // $dompdf = new Dompdf(['chroot' => __DIR__]);
        // $dompdf->loadHtml($html);
        // $dompdf->render();
        // return $html;

        // return $dompdf->stream();

        // dd($pdf);

        return $pdf->stream('categorie.pdf');
    }
}
