<p>Dear {{ $emailData['user_name'] }},</p>

<p>Your appointment has been successfully confirmed!</p>


<ul>
    <li>Date: {{ $emailData['date'] }}</li>
    <li>Time: {{ $emailData['time'] }}</li>
    <li>Location: {{ $emailData['location'] }}</li>
</ul>

<p>Thank you for your payment. We look forward to seeing you.</p>
<p>Thank you for choosing our service.</p>

<p>Best regards,</p>
<p>Your Care</p>