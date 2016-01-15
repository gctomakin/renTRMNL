$(document).ready(function() {
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getReport();
  });
});

function getReport() {
	$.post(reportUrl, {startDate: startDate, endDate: endDate}, function(data) {
		if (data['result']) {
      if (typeof window.reportChat != "undefined") {
        window.reportChat.destroy();
      }
      setChart(data['labels'], data['lessor']['intervals'], data['lessee']['intervals']);
      $('#interval').text(data['interval']);
      $('#total-range').text(data['difference'] + 1);
      $('#details').html(data['details']);
      $('#type-range').text(data['type']);
    }
		else { errorMessage('Wala koi nakuha'); }
	}, 'JSON');
}

function setChart(labels, lessor, lessee) {
  var lineChartData = {
    labels: labels,
    datasets: [{
        label: "Lessors",
        fillColor: "rgba(217, 83, 79, 0.2)", //rgba(220,220,220,0.2)
        strokeColor: "rgba(217, 83, 79, 0.7)", //rgba(220,220,220,1)
        pointColor: "rgba(217, 83, 79, 0.8)", //rgba(220,220,220,1)
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(217, 83, 79, 1)",
        data: lessor
      }, {
      	label: "Lessees",
        fillColor: "rgba(92, 184, 92, 0.2)", //rgba(220,220,220,0.2) rgba(92, 184, 92, 1)
        strokeColor: "rgba(92, 184, 92, 0.7)", //rgba(220,220,220,1)
        pointColor: "rgba(92, 184, 92, 0.8)", //rgba(220,220,220,1)
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(92, 184, 92, 1)",
        data: lessee	
      }
    ]
  }

  window.reportChat = new Chart(document.getElementById("canvas-line-graph").getContext("2d")).Line(lineChartData, {
      responsive: true,
      multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
  });
}