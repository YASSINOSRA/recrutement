<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        /* Reset some default styles */
        body, h1, p {
            margin: 0;
            padding: 0;
        }

        /* Center the content on the page */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .thank-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h1 {
            color: #2c3e50;
            font-size: 26px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        .btn-home {
            display: inline-block;
            text-decoration: none;
            background-color: #3498db;
            color: #fff;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .btn-home:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="thank-container">
        <h1>Thank You!</h1>
        <p>Your Test has been submitted successfully.</p>
        <a href="{{ url('/') }}" class="btn-home">Go to Home</a>
    </div>
</body>
</html>
