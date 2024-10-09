<!-- resources/views/pdf/header.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        /* Page margins */
        @page {
            margin: 0;
        }

        .profit {
            color: green;
        }

        .loss {
            color: red;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
        }

        h1, p {
            text-align: center;
        }

        /* Ensure the table fits well within the margins and is centered */
        table {
            width: 90%;
            border-collapse: collapse;
            margin: 0 auto 20px auto; /* Center table horizontally */
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 6px;
            text-align: left;
            word-wrap: break-word; /* Prevent text overflow */
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            font-size: 8px; /* Reduce font size for better fit */
        }

        /* Handling page breaks for large tables */
        tr {
            page-break-inside: avoid; /* Prevent rows from breaking across pages */
        }

        /* Page footer */
        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            text-align: center;
            font-size: 8px;
            margin-top: 20px;
            margin-bottom: 5px;
        }

        .footer {
            position: fixed;
            bottom: 5px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 8px;
            line-height: 50px;
        }
    </style>
</head>
<body>

<!-- The Title and Description -->
<h1>{{ $title }}</h1>
<p>{{ $description }}</p>
