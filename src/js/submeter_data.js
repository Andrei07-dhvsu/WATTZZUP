        // Example: this comes from your PHP room details page
        const chartContainer = document.getElementById("chartContainer");
        const roomSubmeterId = chartContainer.dataset.roomsubmeterId;

        function fetchSubmeterData() {
            fetch('controller/receive_data.php')
                .then(response => response.json())
                .then(data => {
                    // Find the submeter data that matches the room's submeter_id
                    const submeter = data.find(item => item.submeterId === roomSubmeterId);

                    if (submeter) {
                        document.getElementById('voltage').innerText = submeter.voltage + " V";
                        document.getElementById('current').innerText = submeter.current + " A";
                        document.getElementById('power').innerText = submeter.power + " W";
                        document.getElementById('energyKWh').innerText = submeter.energyKWh + " kWh";
                        document.getElementById('frequency').innerText = submeter.frequency + " Hz";
                        document.getElementById('powerFactor').innerText = submeter.powerFactor;
                    } else {
                        // Handle offline / missing submeter
                        document.getElementById('voltage').innerText = "0 V";
                        document.getElementById('current').innerText = "0 A";
                        document.getElementById('power').innerText = "0 W";
                        document.getElementById('energyKWh').innerText = "0 kWh";
                        document.getElementById('frequency').innerText = "0 Hz";
                        document.getElementById('powerFactor').innerText = "0";
                    }
                })
                .catch(error => console.error('Error fetching submeter data:', error));
        }

        // Refresh every 5 seconds
        setInterval(fetchSubmeterData, 1000);
        fetchSubmeterData();