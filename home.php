<?php
require_once('config.php');

if(@$_SESSION['kullanici_adi'] == null){die('<center>Oturum açmadınız lütfen <a href="index.php">buraya</a> tıklayarak oturum açınız!</center>');}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css-js/css/home.css">
    <link rel="stylesheet" href="css-js/css/bootstrap.min.css">
    <title>Yatırım Değerlendirme</title>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Yatırım Değerlendirme</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="home.php">Grafikler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ozelgrafik.php">Özel Grafikler</a>
                </li>
            </ul>
        <form class="form-inline my-2 my-lg-0">
            <a href="cikis.php" class="btn btn-outline-danger my-2 my-sm-0">Çıkış</a>
        </form>
        </div>
    </nav>

    <!-- İçerikler -->
    <div class="container icerik">
        <div id="proje_maliyet" class="d-flex justify-content-center"></div>
        <div id="proje_kararlilik" class="d-flex justify-content-center"></div>
        <div id="proje_ulasilabilirlik" class="d-flex justify-content-center"></div>
        <div id="proje_tamamlanma_suresi" class="d-flex justify-content-center"></div>
        <div id="proje_geri_donus_suresi" class="d-flex justify-content-center"></div>
    </div>

    <!-- JS -->
    <script src="css-js/js/jquery.js"></script>
    <script src="css-js/js/charts.js"></script>
    <script src="css-js/js/bootstrap.min.js"></script>
    <script>
    var projeler = {
      'maliyet'     : [["Projeler", "Ortalama Maliyet(TL)", { role: "style" }]],
      'karlilik'    : [["Projeler", "Ortalama Kârlılık(TL)", { role: "style" } ]],
      'ulasilabilir': [["Projeler", "Ortalama Ulaşılabilirlik (KM)", { role: "style" } ]],
      'tamamlanma'  : [["Projeler", "Ortalama Tamamlanma(YIL)", { role: "style" } ]],
      'geridonus'   : [["Projeler", "Ortalama Geri Dönüş(YIL)", { role: "style" } ]]
    };

    $.ajax({
      url: 'islemler.php',
      type    : 'POST',
      dataType: 'JSON',
      data    : {'islem' : 'projeleriGetir'},
      success: function(data) {
          for(i=0;i<data.length;i++){
            projeler.maliyet.push([data[i].projeAdi, data[i].maliyet[2], '#399bad']);
            projeler.karlilik.push([data[i].projeAdi, data[i].karlilik[2], '#415ac6']);
            projeler.ulasilabilir.push([data[i].projeAdi, data[i].ulasim[2], '#39ad86']);
            projeler.tamamlanma.push([data[i].projeAdi, data[i].tamamlanmaSuresi[2], '#9bad39']);
            projeler.geridonus.push([data[i].projeAdi, data[i].geriDonus[2], '#ad3939']);
          }
      }
    });

    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(drawChart2);
    google.charts.setOnLoadCallback(drawChart3);
    google.charts.setOnLoadCallback(drawChart4);
    google.charts.setOnLoadCallback(drawChart5);

    function drawChart() {
      var data = google.visualization.arrayToDataTable(
        projeler.maliyet
      );

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Proje / Maliyet Grafiği (TL)",
        width: 1000,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("proje_maliyet"));
      chart.draw(view, options);
  }
  function drawChart2() {
      var data = google.visualization.arrayToDataTable(
        projeler.karlilik
      );

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Proje / Kârlılık Grafiği(TL)",
        width: 1000,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("proje_kararlilik"));
      chart.draw(view, options);
  }
  function drawChart3() {
      var data = google.visualization.arrayToDataTable(
        projeler.ulasilabilir
      );

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Proje / Ulaşılabilirlik(KM) Grafiği",
        width: 1000,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("proje_ulasilabilirlik"));
      chart.draw(view, options);
  }
  function drawChart4() {
      var data = google.visualization.arrayToDataTable(
        projeler.tamamlanma
      );

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Proje / Tamamlanma Süresi Grafiği (YIL)",
        width: 1000,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("proje_tamamlanma_suresi"));
      chart.draw(view, options);
  }
  function drawChart5() {
      var data = google.visualization.arrayToDataTable(
        projeler.geridonus
      );

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Proje / Geri Dönüş Grafiği (YIL)",
        width: 1000,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("proje_geri_donus_suresi"));
      chart.draw(view, options);
  }
    </script>
</body>
</html>