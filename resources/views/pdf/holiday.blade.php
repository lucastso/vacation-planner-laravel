<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Holiday Plan</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap"
            rel="stylesheet"
        />

        <style>
            body {
                font-family: "Inter", sans-serif;
            }
        </style>
    </head>
    <body>
        <h1>Holiday Info</h1>
        <p><strong>Title:</strong> {{ $holiday->title }}</p>
        <p><strong>Description:</strong> {{ $holiday->description }}</p>
        <p><strong>Date:</strong> {{ $holiday->date }}</p>
        <p><strong>Location:</strong> {{ $holiday->location }}</p>
        <p><strong>Participants:</strong></p>
        <ul>
            @foreach($participants as $index => $participant)
            <li>{{ $participant }} #{{ $index }}</li>
            @endforeach
        </ul>
    </body>
</html>
