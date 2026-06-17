const ctx =
document.getElementById(
'salesChart'
);

if(ctx){

new Chart(ctx, {

type:'line',

data:{

labels:[
'Sen',
'Sel',
'Rab',
'Kam',
'Jum',
'Sab',
'Min'
],

datasets:[{

label:'Penjualan',

data:[
120000,
150000,
180000,
250000,
200000,
300000,
350000
],

fill:false,

tension:0.4

}]

},

options:{

responsive:true

}

});

}