<!DOCTYPE html>
<html>
<head>
    <title>Processing JazzCash Payment...</title>
</head>
<body onload="document.jazzcashForm.submit();">
<form name="jazzcashForm" method="POST"
      action="https://sandbox.jazzcash.com.pk/ApplicationAPI/API/Payment/DoTransaction">

    @foreach($data as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <noscript>
        <button type="submit">Click here to continue</button>
    </noscript>
</form>

<p>Redirecting to JazzCash...</p>
</body>
</html>
