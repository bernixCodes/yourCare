<p>Dear {{ $emailData['user_name'] }},</p>

<p>You have successfully reserved an appointment with the following details:</p>

<ul>
    <li>Date: {{ $emailData['date'] }}</li>
    <li>Time: {{ $emailData['time'] }}</li>
    <li>Location: {{ $emailData['location'] }}</li>
</ul>

<p>Thank you for choosing our service.</p>

<p>Best regards,</p>
<p>Your Care</p>