<?php
// Load image path using public_path()
$imagePath = public_path('print/certificate_layout/achievement.png');

// Get image contents and encode to Base64
$imageData = base64_encode(file_get_contents($imagePath));
$imageBase64 = 'data:image/jpeg;base64,' . $imageData;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievement Certificate</title>
    <style>
        @page {
            size: 23.29cm 16.61cm;
            margin: 0;
        }

        @font-face {
            font-family: 'EdwardianScriptITC';
            src: url('{{ public_path('fonts/edwardianscriptitc.ttf') }}') format('truetype');
            font-style: normal;
            font-weight: normal;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            position: relative;
        }

        .certificate-container {
            width: 23.29cm;
            height: 16.61cm;
            position: relative;
            background-image: url('<?php echo $imageBase64; ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .date {
            width: 110px;
            font-size: 8pt;
            margin-left: 86.3%;
            padding-top: 62px;
            font-weight: bold;
        }

        .cn_number {
            width: 110px;
            font-size: 8pt;
            margin-left: 86.3%;
            padding-top: 19px;
            font-weight: bold;
        }

        .employee_name {
            font-family: 'EdwardianScriptITC', cursive;
            font-style: italic;
            color: #f07b0d;
            text-align: center;
            margin-top: 202px;
            margin-left: 25%;
            font-size: 20pt;
            width: 50%;
            font-weight: bold;
        }

        .description {

            text-align: center;
            width: 60%;
            margin-left: 20%;
            color: black;
            margin-top: 10px;
            font-size: 12pt;
            height: 125px;
            /* background: red; */
        }

        table {
            width: 50%;
            margin:auto;
            padding-top: 5px;

        }

        td {
            padding: 0px;
            font-size: 9pt;
        }

        .left-column {
            text-align: center;
            width: 190px;

        }

        .right-column {
            text-align: center;
            width: 200px;
        }
    </style>
</head>

<body>

    <div class="certificate-container">
        <div class="date">
            {{ \Carbon\Carbon::parse($category->date ?? '01-01-2025')->format('jS M Y') }}
        </div>
        <div class="cn_number">
            {{ $category->certificate_number ?? '' }}
        </div>
        <div class="employee_name">
            {{ $category->employee ?? '' }}
        </div>

        <div class="description">

            {{ strip_tags($category->description) ?? '' }}
        </div>
        <table>
            <tbody>
                <tr>
                    <td class="left-column">{{ $category->general_manager ?? '' }} </td>
                    <td class="right-column">{{ $category->lab_manager ?? '' }} </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
