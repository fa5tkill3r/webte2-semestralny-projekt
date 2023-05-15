<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class PDFController extends Controller
{
    public function generatePDF()
    {
       $html = View::make('myPDF')->render();

       $dom = new DOMDocument();
       libxml_use_internal_errors(true);
       $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
       libxml_use_internal_errors(false);

       $xpath = new DOMXPath($dom);
       $navbar = $xpath->query("//nav[contains(@class, 'navbar')]")->item(0);
       if ($navbar !== null) {
           $navbar->parentNode->removeChild($navbar);
       }

       $modifiedHtml = $dom->saveHTML();

       $pdf = PDF::loadHTML($modifiedHtml);
       $pdf->setPaper('A4', 'portrait');

       return $pdf->download('my_pdf.pdf');
   }
}
