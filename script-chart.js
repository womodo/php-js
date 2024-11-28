const ctx = document.getElementById('combinedChart').getContext('2d');
const combinedChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May'],
        datasets: [
            {
                label: 'Stacked Dataset 1',
                data: [10, 20, 30, 40, 50],
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                stack: 'stacked'
            },
            {
                label: 'Stacked Dataset 2',
                data: [20, 30, 40, 50, 60],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                stack: 'stacked'
            },
            {
                label: 'Bar Dataset 1',
                data: [30, 20, 10, 50, 40],
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                stack: 'normal'
            },
        ]
    },
    options: {
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: false
            }
        }
    }
});
