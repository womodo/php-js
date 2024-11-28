var ctx = document.getElementById('chart').getContext('2d');
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ["Project 1", "Project 2"],
    datasets: [{
        label: 'Task 1',
        data: [
          null,
          [new Date('2021-09-11T00:00:00'), new Date('2021-09-13T00:00:00')]
        ],
        backgroundColor: "red",
      },
      {
        label: 'Task 2',
        data: [
          [new Date('2021-09-12T00:00:00'), new Date('2021-09-14T00:00:00')],
          [new Date('2021-09-14T00:00:00'), new Date('2021-09-15T00:00:00')]
        ],
        backgroundColor: "blue",
      },
      {
        label: 'Task 3',
        data: [
          null,
          [new Date('2021-09-16T00:00:00'), new Date('2021-09-18T00:00:00')]
        ],
        backgroundColor: "orange",
      },
    ]
  },
  options: {
    responsive: false,
    indexAxis: 'y',
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Chart.js Floating Horizontal Bar Chart'
      }
    },
    scales: {
      y: {
        stacked: true
      },
      x: {
        type: 'time',
        time: {
          // Luxon format string
          tooltipFormat: 'DD'
        },
        min: new Date('2021-09-11T00:00:00'),
        max: new Date('2021-09-18T00:00:00')
      }
    }
  }
});
