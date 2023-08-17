!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF Example</title>

    <link rel="stylesheet" href="{{ public_path('css/bootstrap.min.css') }}">
    <style>
        body, html {
            font-family: 'Inter', sans-serif;
        }
        table,
        table thead tr,
        thead tr th,
        table tbody tr,
        table tbody td {
            border: 2px solid #000 !important;
            padding: 5px 2px !important;
            font-size: 1rem !important;
        }
    </style>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700');
    </style>
</head>
<body>
    <div class="text-center" style="margin-left: auto; margin-right: auto;">
        <img src="{{ public_path('images/logo.png') }}" height="50" width="50"/>
        <div class="text-center mt-2">Un logo d'exemple, samantha.johns@example.com, Prof. Destini Emmerich I</div>
    </div>

    <div class="mt-2" style="border: 1px solid black;width:100%"></div>

    <h1 class="text-center mt-3 mb-3">La liste<h1>

    <table class="table mb-5">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Parent</th>
                <th>Visible</th>
                <th>Last update</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <th>{{ $category['id'] }}</th>
                    <td>{{ $category['name'] }}</td>
                    <td>{{ $category['parent_id'] }}</td>
                    <td>{{ $category['is_visible'] == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ $category['updated_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script type="text/php">
        if (isset($pdf)) {
            $x = 480;
            $y = 810;
            $text = "Page {PAGE_NUM} sur {PAGE_COUNT}";
            $font = null;
            $size = 12;
            $color = array(0, 0, 0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script>

</body>
</html>
