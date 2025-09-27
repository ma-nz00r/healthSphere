document.addEventListener("DOMContentLoaded", function () {
    // Patients Chart
    const patientCtx = document.getElementById('patientChart').getContext('2d');
    new Chart(patientCtx, {
        type: 'line',
        data: {
            labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
            datasets: [{
                label: 'New Patients',
                data: [12, 19, 15, 23, 17, 28, 30],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true }
    });

    // Appointments Chart
    const appointmentCtx = document.getElementById('appointmentChart').getContext('2d');
    new Chart(appointmentCtx, {
        type: 'bar',
        data: {
            labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
            datasets: [{
                label: 'Appointments',
                data: [10, 15, 12, 18, 14, 20, 25],
                backgroundColor: '#198754'
            }]
        },
        options: { responsive: true }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'doughnut',
        data: {
            labels: ['Consultations', 'Lab Tests', 'Pharmacy', 'Other'],
            datasets: [{
                data: [12000, 8000, 5000, 2000],
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545']
            }]
        },
        options: { responsive: true }
    });
});
