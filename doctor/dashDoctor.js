document.addEventListener("DOMContentLoaded", function() {

    // Gọi API để lấy thống kê lịch hẹn
    fetch('doctor/fetch-monthly-appo.php') // Thay 'path_to_your_php_file.php' bằng đường dẫn thực tế đến file PHP ở trên
    .then(response => response.json())
    .then(data => {
        // Cập nhật số lượng lịch hẹn trong tháng và hôm nay
        document.getElementById('totalAppointments').textContent = data.totalMonthly;
        document.getElementById('appointmentsToday').textContent = data.totalToday;
    })
    .catch(error => console.error('Error fetching appointment statistics:', error));

    // Gọi API để lấy dữ liệu lịch hẹn hàng tháng
    fetch('doctor/fetch-monthly-appo.php')
    .then(response => response.json())
    .then(data => {
        // Vẽ biểu đồ với dữ liệu lấy từ API        
        const ctx1 = document.getElementById('monthlyAppointmentsChart').getContext('2d');
        const monthlyAppointmentsChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: data.months, // Sử dụng tháng từ API
                datasets: [{
                    label: 'Appointments',
                    data: data.counts, // Sử dụng số lượng từ API
                    borderColor: '#007bff',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Chart of appointments within 6 months' // Tên biểu đồ
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });
    })
    .catch(error => console.error('Error fetching monthly appointments data:', error));


    fetch('doctor/fetch-monthly-appo.php')
    .then(response => response.json())
    .then(data => {
      document.getElementById('totalPatients').innerText = data.totalPatients;
      document.getElementById('newPatientsThisMonth').innerText = data.newPatientsThisMonth;
    })
    .catch(error => console.error('Error fetching patient stats:', error));
});
