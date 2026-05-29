<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body onload="document.form.submit()">

<form name="form"
      method="POST"
      action="https://sandbox.jazzcash.com.pk/ApplicationAPI/API/Payment/DoTransaction">

    @foreach($data as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

</form>

<p>Redirecting to JazzCash...</p>

</body>
</html>