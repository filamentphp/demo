<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Slider;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class FrontendController extends Controller
{
    public function index()
    {
        $slider = Slider::where('status', 1)->get();
        $products = Product::with('media')->where('is_visible', 1)->get();
        return Inertia::render('Index',[
            'slider' =>  $slider,
            'products' => $products
        ]);
    }

    public function viewProduct($slug)
    {
        $pageContent = Product::with('media')->where('slug', $slug)->first();

        return Inertia::render('Products/Product', [
            'product' => $pageContent
        ]);
    }


    public function addTextToImage(Request $request)
    {

        // $image = public_path('/storage/' . $request->image);

        // // Define the text to be added
        // $text = $request->name;

        // // Open the image using Intervention Image
        // $img = Image::make($image);
        // $fontSize = 170;
        // $fonted = public_path('images/Arsenal-Regular.ttf');

        // // Calculate curve radius and angle
        // $radius = 250;
        // $angle = 90; // Angle in degrees

        // // Calculate the position along the curve
        // $positionX = $radius * cos(deg2rad($angle));
        // $positionY = $radius * sin(deg2rad($angle));
        // // Add the text to the image

        // $img->text($text, $positionX, $positionY, function($font) use ($fonted, $fontSize) {
        //     $font->file($fonted); // Use the loaded font
        //     $font->size($fontSize);
        //     $font->color('#000'); // Text color
        //     $font->align('center'); // Text alignment
        // });
        // // Save the modified image
        // $img->save(public_path('images/Arsenali.jpg'));



        $width = 800;
$height = 600;
$image = imagecreatetruecolor($width, $height);
$backgroundColor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $backgroundColor);

// Load a font
$font = public_path('images/Arsenal-Regular.ttf');

// Define the text and font size
$text = 'Curved Text';
$fontSize = 36;

$circleCenterX = $width / 4;
$circleCenterY = $height;
$circleRadius = 200;

// Angle increment for the curve
$angleStep = 180 / (strlen($text) - 50);

// Text color (white in this case)
$textColor = imagecolorallocate($image, 0, 0, 0);

// Loop through each character and position them along the semicircle
for ($i = 0; $i < strlen($text); $i++) {
    $character = $text[$i];
    $angle = deg2rad(90 - $i * $angleStep);

    // Calculate the position along the semicircle
    $x = $circleCenterX + $circleRadius * cos($angle);
    $y = $circleCenterY - $circleRadius * sin($angle);

    imagettftext($image, $fontSize, 0, $x, $y, $textColor, $font, $character);
}

// Output the image to the browser
header('Content-Type: image/png');
dd(imagepng($image));
public_path('images/' . imagepng($image));

        return response()->json(['message' => 'Text added to image', 'myimage' => imagepng($image)]);
    }
}
