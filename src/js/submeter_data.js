function fetchSubmeterData() {
    const chartContainer = document.getElementById("chartContainer");
    if (!chartContainer) return;

    const roomSubmeterId = chartContainer.dataset.roomsubmeterId;

    fetch('controller/receive_data.php')
        .then(response => response.json())
        .then(data => {
            const submeter = data.find(item => item.submeterId === roomSubmeterId);

            const voltageEl = document.getElementById('voltage');
            const currentEl = document.getElementById('current');
            const powerEl = document.getElementById('power');
            const energyEl = document.getElementById('energyKWh');
            const freqEl = document.getElementById('frequency');
            const pfEl = document.getElementById('powerFactor');

            if (submeter) {
                if (voltageEl) voltageEl.innerText = submeter.voltage + " V";
                if (currentEl) currentEl.innerText = submeter.current + " A";
                if (powerEl) powerEl.innerText = submeter.power + " W";
                if (energyEl) energyEl.innerText = submeter.energyKWh + " kWh";
                if (freqEl) freqEl.innerText = submeter.frequency + " Hz";
                if (pfEl) pfEl.innerText = submeter.powerFactor;
            } else {
                if (voltageEl) voltageEl.innerText = "0 V";
                if (currentEl) currentEl.innerText = "0 A";
                if (powerEl) powerEl.innerText = "0 W";
                if (energyEl) energyEl.innerText = "0 kWh";
                if (freqEl) freqEl.innerText = "0 Hz";
                if (pfEl) pfEl.innerText = "0";
            }
        })
        .catch(error => console.error('Error fetching submeter data:', error));
}

// Refresh every 1 second
setInterval(fetchSubmeterData, 1000);
fetchSubmeterData();