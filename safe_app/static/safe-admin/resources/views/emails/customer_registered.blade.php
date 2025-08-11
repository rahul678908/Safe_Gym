<!DOCTYPE html>
<html>
<head>
    <title>Registration Successful</title>
</head>
<body>
    <h1>Welcome, {{ $customer['name'] }}!</h1>
    <p>Your registration was successful.</p>
    <p>Your Customer ID is: <strong>{{ $customer['customer_id'] }}</strong></p>
    <p>Thank you for joining us!</p>
    <h4>Safe Gym</h4>
    <h6>Kollenkode</h6>
</body>
</html>
