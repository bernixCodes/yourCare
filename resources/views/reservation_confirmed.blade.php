<html>
<head>
    <title>Complete Your Appointment Payment</title>
</head>
<body>
    <p>Dear {{ auth()->user()->name }},</p>
    <p>Your appointment has been reserved. Please complete your payment to confirm the appointment.</p>
    <p>Date: {{ $emailData['date'] }}</p>
    <p>Time: {{ $emailData['time'] }}</p>
    <p>Pay your $10 to secure your reservation. </p>

    <form action="/" method="POST">
        @csrf
        <input type="hidden" name="appointment_id" value="{{ $emailData['appointment_id'] }}">
        <input type="hidden" name="user_id" value="{{ $emailData['user_id'] }}">
        <input type="hidden" name="amount" value="10"> 
        <input type="hidden" name="stripeToken" value="tok_visa"> 
        <button type="submit">Pay Now</button>
    </form>

<p>Best regards,</p>
<p>Your Care</p>
</body>
</html>