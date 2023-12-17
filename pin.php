<!DOCTYPE HTML>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
              integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu"
              crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
                integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
        <link href="css/bootstrap-pincode-input.css" rel="stylesheet">
        <script type="text/javascript" src="js/bootstrap-pincode-input.js"></script>
        <script>
            $(document).ready(function () {
                $('#pincode-input4').pincodeInput({hidedigits: false, inputs: 4});              
            });
        </script>
    </head>
    <body>
        <input type="number" name="password" id="pincode-input4" required>             
    </body>
</html>