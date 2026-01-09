document.addEventListener('DOMContentLoaded', () => {
    const latInput = document.querySelector('#latitude');
    const lngInput = document.querySelector('#longitude');
    const mapDiv = document.querySelector('#map');
    if (mapDiv && latInput && lngInput) {
        const start = [28.6139, 77.2090]; // Default to New Delhi; update for your city
        const map = L.map('map').setView(start, 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        let marker = null;
        map.on('click', (e) => {
            const { lat, lng } = e.latlng;
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map);
        });
    }

    if (window.dashboardData) {
        const statusCtx = document.getElementById('statusChart');
        const typeCtx = document.getElementById('typeChart');
        if (statusCtx) {
            const labels = window.dashboardData.statusCounts.map(s => s.status);
            const counts = window.dashboardData.statusCounts.map(s => s.total);
            new Chart(statusCtx, {
                type: 'doughnut',
                data: { labels, datasets: [{ data: counts, backgroundColor: ['#ffc107','#0d6efd','#198754','#6c757d'] }] },
                options: { plugins: { legend: { position: 'bottom' } } }
            });
        }
        if (typeCtx) {
            const labels = window.dashboardData.byType.map(t => t.issue_type);
            const counts = window.dashboardData.byType.map(t => t.total);
            new Chart(typeCtx, {
                type: 'bar',
                data: { labels, datasets: [{ data: counts, backgroundColor: '#0d6efd' }] },
                options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        }
        const heatDiv = document.getElementById('heatmap');
        if (heatDiv) {
            const map = L.map('heatmap').setView([28.6139, 77.2090], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            window.dashboardData.heatmap.forEach(point => {
                const color = point.priority_score > 15 ? 'red' : point.priority_score > 8 ? 'orange' : 'green';
                const radius = Math.min(25, 5 + point.priority_score);
                L.circle([point.latitude, point.longitude], {
                    color,
                    fillColor: color,
                    fillOpacity: 0.4,
                    radius: radius
                }).addTo(map);
            });
        }
    }
});

