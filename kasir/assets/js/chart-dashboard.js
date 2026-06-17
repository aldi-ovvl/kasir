document.addEventListener("DOMContentLoaded", function(){

const ctx = document.getElementById('salesChart');

if(ctx){

new Chart(ctx, {

type: 'bar',

data: {

labels: [
'Senin',
'Selasa',
'Rabu',
'Kamis',
'Jumat',
'Sabtu',
'Minggu'
],

datasets: [{

label: 'Total Penjualan',

data: [
120000,
150000,
175000,
250000,
220000,
300000,
350000
],

borderWidth: 1

}]

},

options: {

responsive: true,

plugins: {

legend: {
display: true
}

},

scales: {

y: {
beginAtZero: true
}

}

}

});

}

});