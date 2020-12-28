
//Morris Donut Chart
var dont = Morris.Donut({
element: 'morris-chart-donut',
data: [
 {label: "Referrals", value: 42.7},
 {label: "Direct", value: 8.3},
 {label: "Social", value: 12.8},
 {label: "Organic", value: 36.2}
],
resize: true,
colors: ['#16a085','#2980b9', '#f39c12', '#e74c3c'],
formatter: function (y) { return y + "%" ;}
});


var area = Morris.Area({
  element: 'morris-chart-area',
  data: [],
  parseTime: false,
  xkey: 'y',
  ykeys: ['a', 'b'],
  labels: ['Customers', 'Total']
});

$.ajax({
    type: "GET",
    url:  "admin_takki/area",
    data: {from_date:$("#date_from").val(), to_date:$("#date_to").val()},
    success: function(html)                    
    {   
        if(html == "error"){
        alert('error');
        }
        else
        {
            area.setData(JSON.parse(html));
            bar.setData(JSON.parse(html));

        }
    }
});

//Morris Bar Chart
var bar = Morris.Bar({
element: 'morris-chart-bar',
data: [],
xkey: 'y',
 parseTime: false,
ykeys: ['a', 'b'],
labels: ['Customers', 'Total'],
barColors: ['#8e44ad','#e74c3c'],
resize: true
});

$.ajax({
    type: "GET",
    url:  "admin_takki/donut",
    data: {from_date:$("#date_from").val(), to_date:$("#date_to").val()},
    success: function(html)                    
    {   
        if(html == "error"){
        alert('error');
        }
        else
        {
            dont.setData(JSON.parse(html));

        }
    }
});
