<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Heartbeat Auto Insert</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f2f2f2;
        }
        h1 {
            color: #333;
        }
        #status {
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
            font-size: 18px;
        }
    </style>
</head>

<body>
    <h1>Heartbeat Data Auto Insert</h1>
    <div id="status">Waiting for first update...</div>

    <script>
        function fetchAndInsert() {
$.ajax({
    url: 'get-data.php',
    method: 'GET',
    dataType: 'json',
    cache: false,
    success: function(response) {
        if (response.status === 'success') {
            let now = new Date().toLocaleTimeString();
            let html = '✅ <b>' + response.message + '</b><br>⏱️ Last Update: ' + now;

            // Optional: show live BPM per device
            if(response.data){
                html += '<br><ul>';
                for(let dev in response.data){
                    html += '<li>' + dev + ': ' + response.data[dev].BPM + ' BPM</li>';
                }
                html += '</ul>';
            }

            $('#status').html(html);
        } else {
            $('#status').html('❌ Error: ' + response.message);
        }
    },
    error: function(xhr, status, error) {
        $('#status').html('❌ AJAX Error: ' + error);
        console.log(xhr.responseText); // Debug
    }
});
        }

        // First run
        fetchAndInsert();

        // Auto run every 5 seconds
        setInterval(fetchAndInsert, 5000);
    </script>

</body>
</html>