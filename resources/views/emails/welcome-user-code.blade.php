<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome from nogra-user-voucher project</title>
    <style>
        body, table, td, a {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
        }
        .main-message {
            background-color: #faedcb;
            padding: 25px;
        }
        .main-message-highlight {
            color: #426309;
        }
    </style>
</head>
<body>
    <h3><center>Hello, {{ $first_name }}</center></h3>
    <h1 class="main-message">
        <center>
            Your first voucher code is <span class="main-message-highlight">{{ $code }}</span>!
        </center>
    </h1>
    <p><center>This is only a test project.</center></p>
</body>
</html>