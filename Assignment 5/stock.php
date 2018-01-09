
<!DOCTYPE html>

<html>
<head>
<style type="text/css">

.error {color: #FF0000;}

#box{
  width: 350px;
  height: 150px;
  display: block;
  background-color: #f5f5f5;
  margin-left: auto;
  margin-right: auto;
  margin-top: 0px;
  border-style: solid;
  border-width: 1px;
  border-color: #C0C0C0;
}

#stockhead{
  position: relative;
  display: inline-block;
  margin-top: -10px;
  margin-left: 110px;

}
#hrtag{
  border-color: #E9E7E7;
  margin-top: -10px;
  width: 300px;
}

#my_table {
  margin-top: 20px;
  position: relative;
  margin-left: auto;
  margin-right: auto;
  background-color: #f5f5f5;
  border-collapse: collapse;
  width: 1200px;
  height: 250px;
}

#pub_text{
  margin-left: 20px;
}

#container{
  position: relative;
  width: 1200px;
  height: 400px;
  /*display: block;*/
  margin-left: auto;
  margin-right: auto;
  margin-top: 20px;
}

#my_button{
  margin-left: auto;
  margin-right: auto;
  height: 30px;
  padding: 0;
  border: none;
  background-color: #f5f5f5;
  margin-top: 200px;

}

td,th{
    border: 1px solid #E9E7E7;
}

#my_table1 {
  margin-top: 20px;
  margin-bottom: 20px;
  margin-left: auto;
  margin-right: auto;
  background-color: #f5f5f5;
  border-collapse: collapse;
  width:1200px;
  height: 150px;
  display: none;
}

#my_tbody{
  margin-top: 20px;
  margin-bottom: 20px;
  margin-left: auto;
  margin-right: auto;
  background-color: #f5f5f5;
  border-collapse: collapse;
  width:1000px;
  height: 150px;

}
#my_image {
  width: 10px;
  height: 10px;
}

#newstext{
  margin-left: auto;
  margin-right: auto;
  color: #C0C0C0;

}

#gray_down{
  display:block;
  width: 20px;
  height: 15px;
  margin-top: 10px;
  margin-left: auto;
  margin-right: auto;

}

#centerDisDiv{
  margin-top: 10px;
  margin-left: auto;
  margin-right: auto;
  width: 1200px;
  height: 150px;
  margin-bottom: 10px;
}



#search_id{
  margin-top: -150px;
  margin-left: 190px;
  padding-top: 0px;
}

#inside_table{
  padding-top: 0px;
  margin-top: 5px;
  margin-bottom: 5px;
}

#error_table{
  border-collapse: collapse;
  width: 1200px;
  margin-left: auto;
  margin-right: auto;
  background-color: #f5f5f5;
  margin-top: 10px;
}

.tableclass{
  color: blue;
}

#newslink{
  width: 200px;
  height: 50px;
  margin-left: auto;
  margin-right: auto;
  margin-top: 10px;
}

#insideLink{
  text-decoration: none;
}


</style>
<!-- <script type="text/javascript">

//   else{
// var x = document.getElementById("newstext");
// var y = document.getElementById("gray_down");
// x.style.display="block";
// y.style.display="block";
// }
}
</script> -->
</head>

<body>

  <?php
  // define variables and set to empty values
  ini_set('memory_limit', '1024M');

  $nameErr = "";
  $name = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
      $nameErr = "Name is required";
    } else {
      $name = test_input($_POST["name"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
        $nameErr = "Only letters and white space allowed";
      }
    }

  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  ?>


  <div id="box">
  <span id="stockhead"><i><h2>Stock Search</i></h2></span></h2>
  <hr id="hrtag">
  <!-- <p><span class="error">* required field.</span></p> -->
  <form id="myForm" method="post" action="<?php echo $_SERVER['$PHP_SELF'];?>">
 Enter Stock Ticker Symbol:* <input type="text" name="name" id="name_id" value="<?php echo $name;?>">


    <input id="search_id" type="submit" name="submit" value="Search" onclick="news_function();"/>
    <input type="button" name="reset" value="Clear" id="reset123" onclick="customReset();"/>
    <br>
    <div>*- <i>Mandatory fields.</i></div>
  </form>
  </div>

  <script src="http://code.highcharts.com/highcharts.js"></script>



<script>
function news_function(){
  if(document.getElementById("name_id").value.length == 0)
  {
    alert("Please enter a symbol");
  }
}
function customReset()
{
    document.getElementById("name_id").value = "";
    if(document.getElementById("error_table")){
      document.getElementById("error_table").style.display = "none";
    }
    if(!document.getElementById("error_table")){
    document.getElementById("container").style.display = "none";
    document.getElementById("my_table").style.display = "none";
    document.getElementById("newslink").style.display = "none";
  }


}


function smadata(){

  var xmlhttp = new XMLHttpRequest();
  var url = "https://www.alphavantage.co/query?function=SMA&symbol=<?php echo $name?>&interval=daily&time_period=10&series_type=open&apikey=US5US3OS067EER9S";

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var myArr = JSON.parse(this.responseText);
        myFunction(myArr);
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

function myFunction(arr) {

  var values = arr['Technical Analysis: SMA'];
  var tick= "<?php echo $name ?>";
  var dates = [];
  var smaplot = [];
  var currentDate = new Date(arr['Meta Data']['3: Last Refreshed']);
  currentDate.setMonth(currentDate.getMonth() - 6);

  for(var key in values){
    var previousDate = new Date(key);
    if(previousDate > currentDate){
    dates.push(Date.parse(key));
    var key1 = values[key];
    if(typeof key1 === 'object'){
      for(var key2 in values[key]){
      var result = parseFloat((parseFloat(values[key][key2])).toFixed(2));
                smaplot.push(result);
      }
    }
  }
  }


  // alert(finalarr1.length);

var chart = new Highcharts.Chart({
   chart: {
       renderTo: 'container',
       type: 'spline',
       borderColor: '#f5f5f5',
       borderWidth: 2,
   },
   title: {
       text: 'Simple Moving Average(SMA)'
   },
   subtitle: {
     text: 'Source: <a href="https://www.alphavantage.co/"> Alpha Vantage</a>',
     style: {
      color: '#3333FF'
  }
  },

   xAxis: {
 categories: dates,
 tickInterval:5,
 reversed: true,
 labels: {
     format: '{value:%m/%d}',
 }
 },

   yAxis: {
       title: {
           text: 'SMA'
       }
   },
   legend: {
       layout: 'vertical',
       align: 'right',
       verticalAlign: 'middle'
   },
   tooltip: {
            headerFormat: '<span style="font-size: 10px">{point.key:%m/%d}</span><br/>'
          },

   series: [{
     name: tick,
     data: smaplot,
     color: '#FF0000',
     marker: {
         symbol: 'square',
         lineColor: null,

     }
   }],

   plotOptions:{
     spline:{
       marker:{
         radius: 2,
         lineWidth:1
       }
     }
   }


 }
);
 }
}

function emadata(){

  var xmlhttp = new XMLHttpRequest();
  var url = "https://www.alphavantage.co/query?function=EMA&symbol=<?php echo $name?>&interval=daily&time_period=10&series_type=open&apikey=US5US3OS067EER9S";

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var myArr = JSON.parse(this.responseText);
        myFunction(myArr);
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

function myFunction(arr) {

  var values = arr['Technical Analysis: EMA'];
  var tick= "<?php echo $name ?>";
  var dates = [];
  var emaplot = [];
  var currentDate = new Date(arr['Meta Data']['3: Last Refreshed']);
  currentDate.setMonth(currentDate.getMonth() - 6);

  for(var key in values){
    var previousDate = new Date(key);

    if(previousDate > currentDate){
    dates.push(Date.parse(key));
    var key1 = values[key];
    if(typeof key1 === 'object'){
      for(var key2 in values[key]){
        var result = parseFloat((parseFloat(values[key][key2])).toFixed(2));
        emaplot.push(result);
      }
    }
  }
  }


  // alert(finalarr);
  var chart = new Highcharts.Chart({
     chart: {
         renderTo: 'container',
         type: 'spline',
         borderColor: '#f5f5f5',
         borderWidth: 2,
     },
     title: {
         text: 'Exponential Moving Average(EMA)'
     },
     subtitle: {
       text: 'Source: <a href="https://www.alphavantage.co/"> Alpha Vantage</a>',
       style: {
        color: '#3333FF'
    }
    },

     xAxis: {
   categories: dates,
   tickInterval:5,
   reversed: true,
   labels: {
       format: '{value:%m/%d}',
   }
   },

     yAxis: {
         title: {
             text: 'EMA'
         }
     },
     legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'middle'
     },
     tooltip: {
              headerFormat: '<span style="font-size: 10px">{point.key:%m/%d}</span><br/>'
            },

     series: [{
       name: tick,
       data: emaplot,
       color: '#FF0000',
       marker: {
           symbol: 'square',
           lineColor: null,

       }
     }],

     plotOptions:{
       spline:{
         marker:{
           radius: 2,
           lineWidth:1
         }
       }
     }


   }
  );






 }
}

function stochdata(){

  var xmlhttp = new XMLHttpRequest();
  var url = "https://www.alphavantage.co/query?function=STOCH&symbol=<?php echo $name?>&interval=daily&time_period=10&series_type=open&apikey=US5US3OS067EER9S";

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var myArr = JSON.parse(this.responseText);
        myFunction(myArr);
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

function myFunction(arr) {

  var values = arr['Technical Analysis: STOCH'];
  var slowD = [];
  var slowK = [];
  var dates = [];
  var currentDate = new Date(arr['Meta Data']['3: Last Refreshed']);
  currentDate.setMonth(currentDate.getMonth() - 6);

  for(var key in values){
    var previousDate = new Date(key);

    if(previousDate > currentDate){
    dates.push(Date.parse(key));
    var resultK = parseFloat((parseFloat(values[key]["SlowK"])).toFixed(2));
    slowK.push(resultK);
    var resultD = parseFloat((parseFloat(values[key]["SlowD"])).toFixed(2));
    slowD.push(resultD);


  }
  }


  // alert(finalarr);
  var chart = new Highcharts.Chart({
     chart: {
         renderTo: 'container',
         type: 'spline',
         borderColor: '#f5f5f5',
         borderWidth: 2,
     },
     title: {
         text: 'Stochastic Oscillator (STOCH)'
     },
     subtitle: {
       text: 'Source: <a href="https://www.alphavantage.co/"> Alpha Vantage</a>',
       style: {
        color: '#3333FF'
    }
    },

     xAxis: {
   categories: dates,
   tickInterval:5,
   reversed: true,
   labels: {
       format: '{value:%m/%d}',
   }
   },

     yAxis: {
         title: {
             text: 'STOCH'
         },
         tickInterval: 10,
         min: 10,
         startOnTick: false
     },
     legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'middle'
     },
     tooltip: {
              headerFormat: '<span style="font-size: 10px">{point.key:%m/%d}</span><br/>'
            },

     series: [{
       name: tick+' SlowK',
       data: slowK,
       color: '#FF0000',
       marker: {
           symbol: 'square',
           lineColor: null,

       }
     },
   {
     name: tick+' SlowD',
     data: slowD,
     color: '#0FC5E6',
     marker: {
         symbol: 'square',
         lineColor: null,

     }

   }],

     plotOptions:{
       spline:{
         marker:{
           radius: 2,
           lineWidth:1
         }
       }
     }


   }
  );

 }
}

function rsidata(){

  var xmlhttp = new XMLHttpRequest();
  var url = "https://www.alphavantage.co/query?function=RSI&symbol=<?php echo $name?>&interval=daily&time_period=10&series_type=open&apikey=US5US3OS067EER9S";

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var myArr = JSON.parse(this.responseText);
        myFunction(myArr);
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

function myFunction(arr) {

  var values = arr['Technical Analysis: RSI'];
  var tick= "<?php echo $name ?>";
  var dates = [];
  var rsiplot = [];
  var currentDate = new Date(arr['Meta Data']['3: Last Refreshed']);
  currentDate.setMonth(currentDate.getMonth() - 6);

  for(var key in values){
    var previousDate = new Date(key);

    if(previousDate > currentDate){
    dates.push(Date.parse(key));
    var key1 = values[key];
    if(typeof key1 === 'object'){
      for(var key2 in values[key]){

        var result = parseFloat((parseFloat(values[key][key2])).toFixed(2));
        rsiplot.push(result);

      }
    }
  }
  }


  // alert(finalarr);
  var chart = new Highcharts.Chart({
     chart: {
         renderTo: 'container',
         type: 'spline',
         borderColor: '#f5f5f5',
         borderWidth: 2,
     },
     title: {
         text: 'Relative Strength Index (RSI)'
     },
     subtitle: {
       text: 'Source: <a href="https://www.alphavantage.co/"> Alpha Vantage</a>',
       style: {
        color: '#3333FF'
    }
    },

     xAxis: {
   categories: dates,
   tickInterval:5,
   reversed: true,
   labels: {
       format: '{value:%m/%d}',
   }
   },

     yAxis: {
         title: {
             text: 'RSI'
         }
     },
     legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'middle'
     },
     tooltip: {
              headerFormat: '<span style="font-size: 10px">{point.key:%m/%d}</span><br/>'
            },

     series: [{
       name: tick,
       data: rsiplot,
       color: '#FF0000',
       marker: {
           symbol: 'square',
           lineColor: null,

       }
     }],

     plotOptions:{
       spline:{
         marker:{
           radius: 2,
           lineWidth:1
         }
       }
     }


   }
  );

 }
}

function adxdata(){

  var xmlhttp = new XMLHttpRequest();
  var url = "https://www.alphavantage.co/query?function=ADX&symbol=<?php echo $name?>&interval=daily&time_period=10&series_type=open&apikey=US5US3OS067EER9S";

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var myArr = JSON.parse(this.responseText);
        myFunction(myArr);
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

function myFunction(arr) {

  var values = arr['Technical Analysis: ADX'];
  var tick= "<?php echo $name ?>";
  var dates = [];
  var adxplot = [];
  var currentDate = new Date(arr['Meta Data']['3: Last Refreshed']);
  currentDate.setMonth(currentDate.getMonth() - 6);

  for(var key in values){
    var previousDate = new Date(key);

    if(previousDate > currentDate){
    dates.push(Date.parse(key));
    var key1 = values[key];
    if(typeof key1 === 'object'){
      for(var key2 in values[key]){

        var result = parseFloat((parseFloat(values[key][key2])).toFixed(2));
        adxplot.push(result);
      }
    }
  }
  }


  // alert(finalarr);


  var chart = new Highcharts.Chart({
     chart: {
         renderTo: 'container',
         type: 'spline',
         borderColor: '#f5f5f5',
         borderWidth: 2,
     },
     title: {
         text: 'Average Directional Movement indeX (ADX)'
     },
     subtitle: {
       text: 'Source: <a href="https://www.alphavantage.co/"> Alpha Vantage</a>',
       style: {
        color: '#3333FF'
    }
    },

     xAxis: {
   categories: dates,
   tickInterval:5,
   reversed: true,
   labels: {
       format: '{value:%m/%d}',
   }
   },

     yAxis: {
         title: {
             text: 'ADX'
         }
     },
     legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'middle'
     },
     tooltip: {
              headerFormat: '<span style="font-size: 10px">{point.key:%m/%d}</span><br/>'
            },

     series: [{
       name: tick,
       data: adxplot,
       color: '#FF0000',
       marker: {
           symbol: 'square',
           lineColor: null,

       }
     }],

     plotOptions:{
       spline:{
         marker:{
           radius: 2,
           lineWidth:1
         }
       }
     }


   }
  );
 }
}

function ccidata(){

  var xmlhttp = new XMLHttpRequest();
  var url = "https://www.alphavantage.co/query?function=CCI&symbol=<?php echo $name?>&interval=daily&time_period=10&series_type=open&apikey=US5US3OS067EER9S";

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var myArr = JSON.parse(this.responseText);
        myFunction(myArr);
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

function myFunction(arr) {

  var values = arr['Technical Analysis: CCI'];
  var tick= "<?php echo $name ?>";
  var dates = [];
  var cciplot = [];
  var currentDate = new Date(arr['Meta Data']['3: Last Refreshed']);
  currentDate.setMonth(currentDate.getMonth() - 6);

  for(var key in values){
    var previousDate = new Date(key);

    if(previousDate > currentDate){
    dates.push(Date.parse(key));
    var key1 = values[key];
    if(typeof key1 === 'object'){
      for(var key2 in values[key]){

        var result = parseFloat((parseFloat(values[key][key2])).toFixed(2));
        cciplot.push(result);
      }
    }
  }
  }


  // alert(finalarr);
  var chart = new Highcharts.Chart({
     chart: {
         renderTo: 'container',
         type: 'spline',
         borderColor: '#f5f5f5',
         borderWidth: 2,
     },
     title: {
         text: 'Commodity Channel Index (CCI)'
     },
     subtitle: {
       text: 'Source: <a href="https://www.alphavantage.co/"> Alpha Vantage</a>',
       style: {
        color: '#3333FF'
    }
    },

     xAxis: {
   categories: dates,
   tickInterval:5,
   reversed: true,
   labels: {
       format: '{value:%m/%d}',
   }
   },

     yAxis: {
         title: {
             text: 'CCI'
         }
     },
     legend: {
         layout: 'vertical',
         align: 'right',
         verticalAlign: 'middle'
     },
     tooltip: {
              headerFormat: '<span style="font-size: 10px">{point.key:%m/%d}</span><br/>'
            },

     series: [{
       name: tick,
       data: cciplot,
       color: '#FF0000',
       marker: {
           symbol: 'square',
           lineColor: null,

       }
     }],

     plotOptions:{
       spline:{
         marker:{
           radius: 2,
           lineWidth:1
         }
       }
     }


   }
  );




 }
}






function bbandsdata(){

  var xmlhttp = new XMLHttpRequest();
  var url = "https://www.alphavantage.co/query?function=BBANDS&symbol=<?php echo $name?>&interval=daily&time_period=10&series_type=open&apikey=US5US3OS067EER9S";

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var myArr = JSON.parse(this.responseText);
        myFunction(myArr);
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

function myFunction(arr) {

  var values = arr['Technical Analysis: BBANDS'];
  var tick= "<?php echo $name ?>";
  var realmiddle = [];
  var realupper = [];
  var reallower = [];
  var dates = [];
  var currentDate = new Date(arr['Meta Data']['3: Last Refreshed']);
  currentDate.setMonth(currentDate.getMonth() - 6);

  for(var key in values){
    var previousDate = new Date(key);

    if(previousDate > currentDate){
    dates.push(Date.parse(key));
    var resultupper = parseFloat((parseFloat(values[key]["Real Upper Band"])).toFixed(2));
    realupper.push(resultupper);
    var resultlower = parseFloat((parseFloat(values[key]["Real Lower Band"])).toFixed(2));
    reallower.push(resultlower);
    var resultmiddle = parseFloat((parseFloat(values[key]["Real Middle Band"])).toFixed(2));
    realmiddle.push(resultmiddle);

  }
  }


  // alert(finalarr);

var chart = new Highcharts.Chart({

  chart: {
      renderTo: 'container',
      type: 'spline',
      borderColor: '#f5f5f5',
      borderWidth: 2,
  },
  title: {
      text: 'Bollinger Bands (BBANDS)'
  },
  subtitle: {
    text: 'Source: <a href="https://www.alphavantage.co/"> Alpha Vantage</a>',
    style: {
     color: '#3333FF'
 }
 },

  xAxis: {
categories: dates,
tickInterval:5,
reversed: true,
labels: {
    format: '{value:%m/%d}',
}
},

   yAxis: {
       title: {
           text: 'BBANDS'
       }
   },
   legend: {
       layout: 'vertical',
       align: 'right',
       verticalAlign: 'middle'
   },


   series: [{
     name: tick+' Real Middle Band',
     data: realmiddle,
     color: '#000000',
     marker: {
         symbol: 'square',
         lineColor: null,

     },
     lineWidth: 1
   },
 {
   name: tick+' Real Upper Band',
   data: realupper,
   color: '#FF0000',
   marker: {
       symbol: 'square',
       lineColor: null,

   },
   lineWidth: 1
 },
 {
   name: tick+' Real Lower Band',
   data: reallower,
   color: '#66ff66',
   marker: {
       symbol: 'square',
       lineColor: null,

   },
   lineWidth: 1
 }
],

   plotOptions:{
     spline:{
       marker:{
         radius: 2,
         lineWidth:1
       }
     }
   }


 }
);
 }
}

function macddata(){

  var xmlhttp = new XMLHttpRequest();
  var url = "https://www.alphavantage.co/query?function=MACD&symbol=<?php echo $name?>&interval=daily&time_period=10&series_type=open&apikey=US5US3OS067EER9S";

  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var myArr = JSON.parse(this.responseText);
        myFunction(myArr);
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

function myFunction(arr) {

  var values = arr['Technical Analysis: MACD'];
  var tick= "<?php echo $name ?>";
  var macd = [];
  var macdhist = [];
  var macdsignal = [];
  var dates = [];
  var currentDate = new Date(arr['Meta Data']['3: Last Refreshed']);
  currentDate.setMonth(currentDate.getMonth() - 6);

  for(var key in values){
    var previousDate = new Date(key);

    if(previousDate > currentDate){
    dates.push(Date.parse(key));
    var resultmacd = parseFloat((parseFloat(values[key]["MACD"])).toFixed(2));
    macd.push(resultmacd);

    var resulthist = parseFloat((parseFloat(values[key]["MACD_Hist"])).toFixed(2));
    macdhist.push(resulthist);

    var resultsignal = parseFloat((parseFloat(values[key]["MACD_Signal"])).toFixed(2));
    macdsignal.push(resultsignal);

  }
  }


  // alert(finalarr);

var chart = new Highcharts.Chart({

  chart: {
      renderTo: 'container',
      type: 'spline',
      borderColor: '#f5f5f5',
      borderWidth: 2,
  },
  title: {
      text: 'MACD'
  },
  subtitle: {
    text: 'Source: <a href="https://www.alphavantage.co/"> Alpha Vantage</a>',
    style: {
     color: '#3333FF'
 }
 },

  xAxis: {
categories: dates,
tickInterval:5,
reversed: true,
labels: {
    format: '{value:%m/%d}',
}
},

   yAxis: {
       title: {
           text: 'MACD'
       }
   },
   tooltip: {
            headerFormat: '<span style="font-size: 10px">{point.key:%m/%d}</span><br/>'
          },
   legend: {
       layout: 'vertical',
       align: 'right',
       verticalAlign: 'middle'
   },


   series: [{
     name: tick+' MACD',
     data: macd,
     color: '#000000',
     marker: {
         symbol: 'square',
         lineColor: null,

     },
     lineWidth: 1
   },
 {
   name: tick+' MACD_Hist',
   data: macdhist,
   color: '#FF0000',
   marker: {
       symbol: 'square',
       lineColor: null,

   },
   lineWidth: 1
 },
 {
   name: tick+' MACD_Signal',
   data: macdsignal,
   color: '#66ff66',
   marker: {
       symbol: 'square',
       lineColor: null,

   },
   lineWidth: 1
 }
],

   plotOptions:{
     spline:{
       marker:{
         radius: 2,
         lineWidth:1
       }
     }
   }


 }
);
 }


  // alert(finalarr);



}
</script>

<?php
// name of text  box

//if text box != null -> enclose isset in brackets

if(!empty($_POST['name'])){

date_default_timezone_set('America/Los_Angeles');
$name = $_POST['name'];
// echo $name;
// &outputsize=full

$url= "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=".$name."&apikey=US5US3OS067EER9S&outputsize=full";
//$urlindicator= "https://www.alphavantage.co/query?function=SMA&symbol=".$name."&interval=daily&time_period=10&series_type=open&apikey=US5US3OS067EER9S";

$arrContextOptions=array(
      "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );

$json = file_get_contents($url, false, stream_context_create($arrContextOptions));
//$json_Indi = file_get_contents($urlindicator, false, stream_context_create($arrContextOptions));



$data = json_decode($json);

if(isset( $data->{'Error Message'})){
    echo "<table id=\"error_table\">";
    echo '<tr><td><b>Error</b></td><td align="center">Error:NO record has been found, please enter a valid symbol<td></tr></table>';
}

else{
$arrpart1 = $data->{"Time Series (Daily)"};
$arrpart2 = json_encode($arrpart1);
$arrpart3 = json_decode($arrpart2);
$count1 = 0;
$count2 = 0;
$max_loop1=1;
$max_loop2=2;

foreach($arrpart3 as $x => $x_value) {
    $key_val1 = $x;
    $count1++;
    if($count1==$max_loop1) break;
}

foreach($arrpart3 as $x => $x_value) {
    $key_val2 = $x;
    $count2++;
    if($count2==$max_loop2) break;
}

$currclose = $data->{"Time Series (Daily)"}->{$key_val1}->{"4. close"};
$curropen = $data->{"Time Series (Daily)"}->{$key_val1}->{"1. open"};
$prevclose = $data->{"Time Series (Daily)"}->{$key_val2}->{"4. close"};
$change = number_format($currclose - $prevclose,2);
$percent = (($currclose - $prevclose)/($prevclose))*100;
$percentchange = number_format($percent,2);
$low = $data->{"Time Series (Daily)"}->{$key_val1}->{"3. low"};
$high = $data->{"Time Series (Daily)"}->{$key_val1}->{"2. high"};
$volume = $data->{"Time Series (Daily)"}->{$key_val1}->{"5. volume"};
$intvolume = number_format($volume);
$timestamp = $data->{"Meta Data"}->{"3. Last Refreshed"};



echo "<table id=\"my_table\">";
echo '<tr>';
echo "<td style='border=1px solid black;'>";
echo "<b>Stock Ticker Symbol</b>";
echo "</td>";
echo "<td align='center';style='border=1px solid black;'>";
echo ($data->{"Meta Data"}->{"2. Symbol"});
echo "</td>";
echo "</tr>";
echo '<tr>';
echo "<td style='border=1px solid black;'>";
echo "<b>Close</b>";
echo "</td>";
echo "<td align='center';style='border=1px solid black;'>";
echo $currclose;
echo "</td>";
echo "</tr>";
echo '<tr>';
echo "<td>";
echo "<b>Open</b>";
echo "</td>";
echo "<td align=\"center\";style='border=1px solid black;'>";
echo $curropen;
echo "</td>";
echo "</tr>";
echo '<tr>';
echo "<td>";
echo "<b>Previous Close</b>";
echo "</td>";
echo "<td align=\"center\";style='border=1px solid black;'>";
echo $prevclose;
echo "</td>";
echo "</tr>";
echo '<tr>';
echo "<td>";
echo "<b>Change</b>";
echo "</td>";
echo "<td align=\"center\">";
echo  $change;
if ($change > 0)
echo ' <img src = "http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png" id="my_image">';
if ($change < 0)
echo ' <img src = "http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png" id="my_image">';
echo "</td>";
echo "</tr>";
echo '<tr>';
echo "<td>";
echo "<b>Change Percent</b>";
echo "</td>";
echo "<td align=\"center\">";
echo  $percentchange."%";
if ($change > 0)
echo ' <img src = "http://cs-server.usc.edu:45678/hw/hw6/images/Green_Arrow_Up.png" id="my_image">';
if ($change < 0)
echo ' <img src = "http://cs-server.usc.edu:45678/hw/hw6/images/Red_Arrow_Down.png" id="my_image">';
echo "</td>";
echo "</tr>";
echo '<tr>';
echo "<td>";
echo "<b>Day's Range</b>";
echo "</td><td align=\"center\">$low-$high</td>";
echo "</tr>";
echo '<tr>';
echo "<td>";
echo "<b>Volume</b>";
echo "</td><td align=\"center\">$intvolume</td>";
echo "</tr>";
echo '<tr>';
echo "<td>";
echo "<b>Timestamp</b>";
echo "</td><td align=\"center\">$timestamp</td>";
echo "</tr>";
echo '<tr>';
echo "<td>";
echo "<b>Indicators</b>";
echo "</td>";
echo "<td align=\"center\">";
echo "<p id=\"inside_table\"> <span class=\"tableclass\" onclick=\"pricedata()\">Price &nbsp;&nbsp;</span> <span class=\"tableclass\" onclick=\"smadata()\">SMA &nbsp;&nbsp;</span> <span class=\"tableclass\" onclick=\"emadata()\">EMA &nbsp;&nbsp;</span>
 <span class=\"tableclass\" onclick=\"stochdata()\">STOCH &nbsp;&nbsp;</span> <span class=\"tableclass\" onclick=\"rsidata()\">RSI &nbsp;&nbsp;</span> <span class=\"tableclass\" onclick=\"adxdata()\">ADX &nbsp;&nbsp;</span>
  <span class=\"tableclass\" onclick=\"ccidata()\">CCI &nbsp;&nbsp;</span> <span class=\"tableclass\" onclick=\"bbandsdata()\">BBANDS &nbsp;&nbsp;</span> <span class=\"tableclass\" onclick=\"macddata()\">MACD &nbsp;&nbsp;</span></p>";
echo "</td>";
echo "</tr>";
echo "</table>";


echo '<div id="container"></div>';
echo '<div id="newslink">
<a onclick="myFunc()"><span id="newstext">click to show stock news</span><img src="http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png" id="gray_down"></a>
</div>';
}

}
?>



<script>

var arr= "<?php echo $json ?>";
var tick= "<?php echo $name ?>";
var values = arr['Time Series (Daily)'];
var dates = [];
var price = [];
var volume = [];
var currentDate = new Date(arr['Meta Data']['3. Last Refreshed']);
var fixedcurrentdate = new Date(currentDate);

// alert(currentDate);
currentDate.setMonth(currentDate.getMonth() - 6);

for(var key in values){

  var previousDate = new Date(key);

  if(previousDate > currentDate){
  dates.push(Date.parse(key));
  var key1 = values[key];
  price.push(parseFloat(values[key]['4. close']));
  volume.push(parseFloat(values[key]['5. volume']));
}
}
// alert(finalarr1);
// alert(finalarr);
maxVolume = Number.NEGATIVE_INFINITY;
minPrice = Number.POSITIVE_INFINITY;
// alert(dates.length);
for(var i = 0; i < price.length; i++ ){
  if(price[i] < minPrice){
    minPrice = price[i];
  }
}

for(var i = 0; i < volume.length; i++ ){
  if(volume[i] > maxVolume){
    maxVolume = volume[i];
  }
}

var dd = fixedcurrentdate.getDate();
var mm = fixedcurrentdate.getMonth() + 1;
var yyyy =fixedcurrentdate.getFullYear();
if(dd < 10){
  dd = '0'+dd;
}
if(mm < 10){
  mm = '0'+mm;
}
var fixedcurrentdate = mm+'/'+dd+'/'+yyyy;

Highcharts.chart('container', {
  chart: {
  borderColor: '#E9E7E7',
  borderWidth: 2

},
        title: {
            text: 'Stock Price ('+fixedcurrentdate+')'
        },

    subtitle: {
         text: 'Source: <a href="https://www.alphavantage.co/"> Alpha Vantage</a>',
         style: {
          color: '#3333FF'
      }
    },

        xAxis: {
      categories: dates,
      tickInterval:5,
      reversed: true,
      labels: {
          format: '{value:%m/%d}',
      }
        },
        yAxis: [{
      min:minPrice-5,
      tickInterval:5,
            title:
      {
                text: 'Stock Price'
            }
  },
  {
    //vol
    title: {
              text: 'Volume'
              },
              max: maxVolume * 3,
              allowDecimals: false,
              opposite: true,
              type: 'linear',
              startOnTick: true,
              endOnTick: true,

  }
  ],
  tooltip: {
           headerFormat: '<span style="font-size: 10px">{point.key:%m/%d}</span><br/>'
         },
  legend: {
    layout: 'vertical',
    align: 'right',
    verticalAlign: 'middle',
    floating: false
  },
  plotOptions: {
    area: {
        marker: {
            enabled: false
        }
    }
},
        series: [{
      name: tick,
      zIndex:2,
      color:'rgb(231,58,44,0.50)',
      type:'area',
    data: price
  },
    {
      name: tick+' Volume',
      type:'column',
      yAxis:1,
      color:'#FFFFFF',
      zIndex:8,
      data: volume
      }]
  });



function pricedata(){

  var arr= "<?php echo $json ?>";
  var tick= "<?php echo $name ?>";
  var values = arr['Time Series (Daily)'];
  var dates = [];
  var price = [];
  var volume = [];
  var currentDate = new Date(arr['Meta Data']['3. Last Refreshed']);
  var fixedcurrentdate = new Date(currentDate);

  // alert(currentDate);
  currentDate.setMonth(currentDate.getMonth() - 6);

  for(var key in values){

    var previousDate = new Date(key);

    if(previousDate > currentDate){
    dates.push(Date.parse(key));
    var key1 = values[key];
    price.push(parseFloat(values[key]['4. close']));
    volume.push(parseFloat(values[key]['5. volume']));
  }
  }
  // alert(finalarr1);
  // alert(finalarr);
  maxVolume = Number.NEGATIVE_INFINITY;
  minPrice = Number.POSITIVE_INFINITY;
  // alert(dates.length);
  for(var i = 0; i < price.length; i++ ){
    if(price[i] < minPrice){
      minPrice = price[i];
    }
  }
  for(var i = 0; i < volume.length; i++ ){
    if(volume[i] > maxVolume){
      maxVolume = volume[i];
    }
  }

  var dd = fixedcurrentdate.getDate();
  var mm = fixedcurrentdate.getMonth() + 1;
  var yyyy =fixedcurrentdate.getFullYear();
  if(dd < 10){
    dd = '0'+dd;
  }
  if(mm < 10){
    mm = '0'+mm;
  }
  var fixedcurrentdate = mm+'/'+dd+'/'+yyyy;

  Highcharts.chart('container', {
    chart: {
    borderColor: '#E9E7E7',
    borderWidth: 2

},
          title: {
              text: 'Stock Price ('+fixedcurrentdate+')'
          },

  		subtitle: {
           text: 'Source: <a href="https://www.alphavantage.co/"> Alpha Vantage</a>',
           style: {
            color: '#3333FF'
        }
  		},

          xAxis: {
        categories: dates,
  			tickInterval:5,
        reversed: true,
        labels: {
            format: '{value:%m/%d}',
        }
          },
          yAxis: [{
  			min:minPrice-5,
  			tickInterval:5,
              title:
  			{
                  text: 'Stock Price'
              }
  	},
  	{
  		//vol
  		title: {
  			        text: 'Volume'
  			        },
                max: maxVolume * 3,
  			        // labels: {
  			        //     format: '{value} M'
  			        // },
  			        allowDecimals: false,
  			        opposite: true,
  			        type: 'linear',
  			        startOnTick: true,
  			        endOnTick: true,

  	}
  	],
    tooltip: {
             headerFormat: '<span style="font-size: 10px">{point.key:%m/%d}</span><br/>'
           },
    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'middle',
      floating: false
    },
    plotOptions: {
      area: {
          marker: {
              enabled: false
          }
      }
  },
          series: [{
        name: tick,
  			zIndex:2,
  			color:'rgb(231,58,44,0.50)',
  			type:'area',
  		data: price
    },
  		{
  			name: tick+' Volume',
  			type:'column',
  			yAxis:1,
  			color:'#FFFFFF',
  			zIndex:8,
  			data: volume
  			}]
  	});

}


</script>

<?php
if(!empty($_POST['name']) && !isset( $data->{'Error Message'}))
{
  $name = $_POST['name'];
  $urlxml = "https://seekingalpha.com/api/sa/combined/".$name.".xml";

  $arr1ContextOptions=array(
        "ssl"=>array(
              "verify_peer"=>false,
              "verify_peer_name"=>false,
          ),
      );

  $xmlfile = file_get_contents($urlxml, false, stream_context_create($arr1ContextOptions));

  $xml = simplexml_load_string($xmlfile) or die("feed not loading");


$counter = count($xml->channel->item);

  $count = 0;
  $j=0;
  $arraytitle = array();
  $arraylink = array();
  $arraypub = array();

for($i =0; $i < $counter; $i++){
  if($count < 5){
  $haystack = $xml->channel->item[$i]->link;
  $needle = "https://seekingalpha.com/article/";
  $pos = strpos($haystack,$needle);


 if($pos !== false) {

   $title = $xml->channel->item[$i]->title;
   $link = $xml->channel->item[$i]->link;
   $pub = $xml->channel->item[$i]->pubDate;
   $search = '-0400';
   $trimmedpub = str_replace($search, '', $pub) ;

   $arraytitle[$j] = $title;
   $arraylink[$j] = $link;
   $arraypub[$j] = $trimmedpub;

   $j++;
   $count++;
 }
}

}

  $arrytest = array(array('title'=> array_values($arraytitle)[0], 'link'=> array_values($arraylink)[0] , 'pub'=> array_values($arraypub)[0]),array('title'=> array_values($arraytitle)[1], 'link'=> array_values($arraylink)[1], 'pub'=> array_values($arraypub)[1]),
  array('title'=> array_values($arraytitle)[2], 'link'=> array_values($arraylink)[2], 'pub'=> array_values($arraypub)[2]),array('title'=> array_values($arraytitle)[3], 'link'=> array_values($arraylink)[3], 'pub'=> array_values($arraypub)[3]),
  array('title'=> array_values($arraytitle)[4], 'link'=> array_values($arraylink)[4], 'pub'=> array_values($arraypub)[4]));
}
?>




<div id="centerDisDiv">
  <table name="displayTable" id="my_table1" style="width: auto !important; display:none">
    <tbody id ="my_tbody">
      <?php
        if(!empty($arrytest)){
            foreach($arrytest as $dd => $item){
              $title = $item['title'];
              $link = $item['link'];
              $pub = $item['pub'];
      ?>
      <tr>
      <td style="width:1200px !important">
        <a id="insideLink" href= "<?php echo $link ?>"><?php echo $title?></a> &nbsp; &nbsp; &nbsp;
        Publicated Time: <?php echo $pub ?>
      </td>
    </tr>
    <?php
  }
}
?>
</tbody>
  </table>
  </div>



<script>
// document.getElementById("my_button").style.visibility="hidden";

function myFunc() {
  var image = document.getElementById("gray_down").src;
  var text = document.getElementById("newstext").textContent;

  if(image == "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png"){
    document.getElementById("gray_down").src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Down.png";
    document.getElementById("newstext").textContent = "Click to show stock news";
  } else{
    document.getElementById("gray_down").src = "http://cs-server.usc.edu:45678/hw/hw6/images/Gray_Arrow_Up.png";
    document.getElementById("newstext").textContent = "Click to hide stock news";
  }

  var x = document.getElementById("my_table1");
  if (x.style.display == "none") {
      x.style.display = "block"
  } else {
      x.style.display = "none";
  }
}
</script>
</body>

</html>
