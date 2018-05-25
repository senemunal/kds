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
    <title>KDS</title>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">KDS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Grafikler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="ozelgrafik.php">Özel Grafikler</a>
                </li>
            </ul>
        <form class="form-inline my-2 my-lg-0">
            <a href="cikis.php" class="btn btn-outline-danger my-2 my-sm-0">Çıkış</a>
        </form>
        </div>
    </nav>

    <!-- İçerikler -->
    <div class="container icerik">

        <div class="container liste-ayir">
            <div class="row">
                <div class="col-6">
                    <select class="form-control" id="sec-maliyet">
                        <option value="none">Maliyet Seç</option>
                    </select>
                </div>
                <div class="col">
                    <button class="btn btn-success btn-block" id="buton-mal">Maliyete Göre Listele</button>
                </div>
            </div>
        </div>

        <div class="container liste-ayir">
            <div class="row">
                <div class="col-6">
                    <select class="form-control" id="sec-kar">
                        <option value="none">Kârlılık Seç</option>
                    </select>
                </div>
                <div class="col">
                    <button class="btn btn-success btn-block" id="buton-kar">Kârlılığa Göre Listele</button>
                </div>
            </div>
        </div>

        <div class="container liste-ayir">
            <div class="row">
                <div class="col-6">
                    <select class="form-control" id="sec-ulas">
                        <option value="none">Ulaşılabilirlik Seç</option>
                    </select>
                </div>
                <div class="col">
                    <button class="btn btn-success btn-block" id="buton-ul">Ulaşılabilirliğe Göre Listele</button>
                </div>
            </div>
        </div>

        <div class="container liste-ayir">
            <div class="row">
                <div class="col-6">
                    <select class="form-control" id="sec-tamam">
                        <option value="none">Tamamlanma Süresi Seç</option>
                    </select>
                </div>
                <div class="col">
                    <button class="btn btn-success btn-block" id="buton-ts">Tamamlanma Süresine Göre Listele</button>
                </div>
            </div>
        </div>

        <div class="container liste-ayir">
            <div class="row">
                <div class="col-6">
                    <select class="form-control" id="sec-geri">
                        <option value="none">Geri Dönüş Süresi Seç</option>
                    </select>
                </div>
                <div class="col">
                    <button class="btn btn btn-success btn-block" id="buton-gds">Geri Dönüş Süresine Göre Listele</button>
                </div>
            </div>
        </div>

        <div class="container listele-icerik" id="icerikler">
            <div class="alert alert-danger" role="alert" id="uyari-mesaji"></div>

            <div id="grafik-icerigi">
                <div id="proje_maliyet" class="d-flex justify-content-center"></div>
                <div id="proje_kararlilik" class="d-flex justify-content-center"></div>
                <div id="proje_ulasilabilirlik" class="d-flex justify-content-center"></div>
                <div id="proje_tamamlanma_suresi" class="d-flex justify-content-center"></div>
                <div id="proje_geri_donus_suresi" class="d-flex justify-content-center"></div>
            </div>

        </div>


    </div>

    <!-- JS -->
    <script src="css-js/js/jquery.js"></script>
    <script src="css-js/js/charts.js"></script>
    <script src="css-js/js/bootstrap.min.js"></script>
    <script>
        google.charts.load('current', {'packages':['corechart']});
        
        $.ajax({
            url     : 'islemler.php',
            type    : 'POST',
            dataType: 'JSON',
            data    : {'islem' : 'secenekleriGetir'},
            success: function(data) {
                for(i=0;i<data.maliyet.length;i++){
                    $('#sec-maliyet').append('<option value="'+data.maliyet[i]+'">'+data.maliyet[i]+'</option>');
                }
                for(i=0;i<data.karlilik.length;i++){
                    $('#sec-kar').append('<option value="'+data.karlilik[i]+'">'+data.karlilik[i]+'</option>');
                }
                for(i=0;i<data.ulasilabilirlik.length;i++){
                    $('#sec-ulas').append('<option value="'+data.ulasilabilirlik[i]+'">'+data.ulasilabilirlik[i]+'</option>');
                }
                for(i=0;i<data.tamamlanma.length;i++){
                    $('#sec-tamam').append('<option value="'+data.tamamlanma[i]+'">'+data.tamamlanma[i]+'</option>');
                }
                for(i=0;i<data.donus.length;i++){
                    $('#sec-geri').append('<option value="'+data.donus[i]+'">'+data.donus[i]+'</option>');
                }
            }
        });

        
        $(document).on('click', '#buton-mal', function() {
                if($('#sec-maliyet').val() != 'none'){
                    $.ajax({
                    url     : 'islemler.php',
                    type    : 'POST',
                    dataType: 'JSON',
                    data    : {'islem' : 'ozelGrafik', 'deger':$('#sec-maliyet').val(), 'anahtar':'maliyet'},
                    success: function(data) {
                        if(data.length == 0){
                            hataAc('Kriterlerinize uygun sonuç bulunamadı!');
                            kayitlariTemizle();
                        }
                        else{
                                
                                kayitlariTemizle();
                                var projeler = {
                                  'maliyet'     : [['Proje', 'Ortalama Maliyet(TL)']],
                                  'karlilik'    : [['Proje', 'Ortalama Kârlılık(TL)']],
                                  'ulasilabilir': [['Proje', 'Ortalama Ulaşılabilirlik (KM)']],
                                  'tamamlanma'  : [['Proje', 'Ortalama Tamamlanma(YIL)']],
                                  'geridonus'   : [['Proje', 'Ortalama Geri Dönüş(YIL)']]
                                };

                                for(i=0;i<data.length;i++){
                                    projeler.maliyet.push([data[i].proje_grubu, ortalamaHesapla(data[i].maliyet_grup)[2]]); //BURAYA GEL
                                    projeler.karlilik.push([data[i].proje_grubu, ortalamaHesapla(data[i].karlilik_grup)[2]]);
                                    projeler.ulasilabilir.push([data[i].proje_grubu, ortalamaHesapla(data[i].ulasim_grup)[2]]);
                                    projeler.tamamlanma.push([data[i].proje_grubu, ortalamaHesapla(data[i].ts_grup)[2]]);
                                    projeler.geridonus.push([data[i].proje_grubu, ortalamaHesapla(data[i].gds_grup)[2]]);
                                }
                                drawChart1();
                                google.charts.setOnLoadCallback(drawChart1);
                                drawChart2();
                                google.charts.setOnLoadCallback(drawChart2);
                                drawChart3();
                                google.charts.setOnLoadCallback(drawChart3);
                                drawChart4();
                                google.charts.setOnLoadCallback(drawChart4);

                                function drawChart1() {
                                    //console.log(projeler.karlilik);
                                    var data = google.visualization.arrayToDataTable(
                                        projeler.karlilik
                                    );
                                
                                  var options = {
                                    title: 'Ortalama Kârlılık(TL)'
                                  };
                              
                                  var chart = new google.visualization.PieChart(document.getElementById('proje_kararlilik'));
                              
                                  chart.draw(data, options);
                                }
                                function drawChart2() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.ulasilabilir
                                );
                              
                                var options = {
                                  title: 'Ortalama Ulaşılabilirlik (KM)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_ulasilabilirlik'));
                            
                                chart.draw(data, options);
                              }
                              function drawChart3() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.tamamlanma
                                );
                              
                                var options = {
                                  title: 'Ortalama Tamamlanma(YIL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_tamamlanma_suresi'));
                            
                                chart.draw(data, options);
                              }
                              function drawChart4() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.geridonus
                                );
                              
                                var options = {
                                  title: 'Ortalama Geri Dönüş(YIL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_geri_donus_suresi'));
                            
                                chart.draw(data, options);
                              }
                            }
                        }
                    });
                }
            else{
                hataAc('Herhangi bir veri seçmeden listeleme yapamazsın!');
            }
        });
        $(document).on('click', '#buton-kar', function() {
                if($('#sec-kar').val() != 'none'){
                    $.ajax({
                    url     : 'islemler.php',
                    type    : 'POST',
                    dataType: 'JSON',
                    data    : {'islem' : 'ozelGrafik', 'deger':$('#sec-kar').val(), 'anahtar':'kar'},
                    success: function(data) {
                        if(data.length == 0){
                            hataAc('Kriterlerinize uygun sonuç bulunamadı!');
                            kayitlariTemizle();
                        }
                        else{
                            
                            kayitlariTemizle();
                            var projeler = {
                                  'maliyet'     : [['Proje', 'Ortalama Maliyet(TL)']],
                                  'karlilik'    : [['Proje', 'Ortalama Kârlılık(TL)']],
                                  'ulasilabilir': [['Proje', 'Ortalama Ulaşılabilirlik (KM)']],
                                  'tamamlanma'  : [['Proje', 'Ortalama Tamamlanma(YIL)']],
                                  'geridonus'   : [['Proje', 'Ortalama Geri Dönüş(YIL)']]
                                };

                                for(i=0;i<data.length;i++){
                                    projeler.maliyet.push([data[i].proje_grubu, ortalamaHesapla(data[i].maliyet_grup)[2]]); //BURAYA GEL
                                    projeler.karlilik.push([data[i].proje_grubu, ortalamaHesapla(data[i].karlilik_grup)[2]]);
                                    projeler.ulasilabilir.push([data[i].proje_grubu, ortalamaHesapla(data[i].ulasim_grup)[2]]);
                                    projeler.tamamlanma.push([data[i].proje_grubu, ortalamaHesapla(data[i].ts_grup)[2]]);
                                    projeler.geridonus.push([data[i].proje_grubu, ortalamaHesapla(data[i].gds_grup)[2]]);
                                }
                                drawChart1();
                                google.charts.setOnLoadCallback(drawChart1);
                                drawChart2();
                                google.charts.setOnLoadCallback(drawChart2);
                                drawChart3();
                                google.charts.setOnLoadCallback(drawChart3);
                                drawChart4();
                                google.charts.setOnLoadCallback(drawChart4);

                                function drawChart1() {
                                    //console.log(projeler.karlilik);
                                    var data = google.visualization.arrayToDataTable(
                                        projeler.maliyet
                                    );
                                
                                  var options = {
                                    title: 'Ortalama Maliyet(TL)'
                                  };
                              
                                  var chart = new google.visualization.PieChart(document.getElementById('proje_kararlilik'));
                              
                                  chart.draw(data, options);
                                }
                                function drawChart2() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.ulasilabilir
                                );
                              
                                var options = {
                                  title: 'Ortalama Ulaşılabilirlik (KM)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_ulasilabilirlik'));
                            
                                chart.draw(data, options);
                              }
                              function drawChart3() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.tamamlanma
                                );
                              
                                var options = {
                                  title: 'Ortalama Tamamlanma(YIL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_tamamlanma_suresi'));
                            
                                chart.draw(data, options);
                              }
                              function drawChart4() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.geridonus
                                );
                              
                                var options = {
                                  title: 'Ortalama Geri Dönüş(YIL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_geri_donus_suresi'));
                            
                                chart.draw(data, options);
                              }
                        }
                    }
                });
            }
            else{
                hataAc('Herhangi bir veri seçmeden listeleme yapamazsın!');
            }
        });
        $(document).on('click', '#buton-ul', function() {
                if($('#sec-ulas').val() != 'none'){
                    $.ajax({
                    url     : 'islemler.php',
                    type    : 'POST',
                    dataType: 'JSON',
                    data    : {'islem' : 'ozelGrafik', 'deger':$('#sec-ulas').val(), 'anahtar':'ulas'},
                    success: function(data) {
                        if(data.length == 0){
                            hataAc('Kriterlerinize uygun sonuç bulunamadı!');
                            kayitlariTemizle();
                        }
                        else{
                            //console.log(data);
                            kayitlariTemizle();
                            var projeler = {
                                  'maliyet'     : [['Proje', 'Ortalama Maliyet(TL)']],
                                  'karlilik'    : [['Proje', 'Ortalama Kârlılık(TL)']],
                                  'ulasilabilir': [['Proje', 'Ortalama Ulaşılabilirlik (KM)']],
                                  'tamamlanma'  : [['Proje', 'Ortalama Tamamlanma(YIL)']],
                                  'geridonus'   : [['Proje', 'Ortalama Geri Dönüş(YIL)']]
                                };

                                for(i=0;i<data.length;i++){
                                    projeler.maliyet.push([data[i].proje_grubu, ortalamaHesapla(data[i].maliyet_grup)[2]]); //BURAYA GEL
                                    projeler.karlilik.push([data[i].proje_grubu, ortalamaHesapla(data[i].karlilik_grup)[2]]);
                                    projeler.ulasilabilir.push([data[i].proje_grubu, ortalamaHesapla(data[i].ulasim_grup)[2]]);
                                    projeler.tamamlanma.push([data[i].proje_grubu, ortalamaHesapla(data[i].ts_grup)[2]]);
                                    projeler.geridonus.push([data[i].proje_grubu, ortalamaHesapla(data[i].gds_grup)[2]]);
                                }
                                drawChart1();
                                google.charts.setOnLoadCallback(drawChart1);
                                drawChart2();
                                google.charts.setOnLoadCallback(drawChart2);
                                drawChart3();
                                google.charts.setOnLoadCallback(drawChart3);
                                drawChart4();
                                google.charts.setOnLoadCallback(drawChart4);

                                function drawChart1() {
                                    //console.log(projeler.karlilik);
                                    var data = google.visualization.arrayToDataTable(
                                        projeler.maliyet
                                    );
                                
                                  var options = {
                                    title: 'Ortalama Maliyet(TL)'
                                  };
                              
                                  var chart = new google.visualization.PieChart(document.getElementById('proje_kararlilik'));
                              
                                  chart.draw(data, options);
                                }
                                function drawChart2() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.karlilik
                                );
                              
                                var options = {
                                  title: 'Ortalama Kârlılık(TL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_ulasilabilirlik'));
                            
                                chart.draw(data, options);
                              }
                              function drawChart3() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.tamamlanma
                                );
                              
                                var options = {
                                  title: 'Ortalama Tamamlanma(YIL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_tamamlanma_suresi'));
                            
                                chart.draw(data, options);
                              }
                              function drawChart4() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.geridonus
                                );
                              
                                var options = {
                                  title: 'Ortalama Geri Dönüş(YIL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_geri_donus_suresi'));
                            
                                chart.draw(data, options);
                              }
                        }
                    }
                });
            }
            else{
                hataAc('Herhangi bir veri seçmeden listeleme yapamazsın!');
            }

        });
        $(document).on('click', '#buton-ts', function() {
                if($('#sec-tamam').val() != 'none'){
                    $.ajax({
                    url     : 'islemler.php',
                    type    : 'POST',
                    dataType: 'JSON',
                    data    : {'islem' : 'ozelGrafik', 'deger':$('#sec-tamam').val(), 'anahtar':'ts'},
                    success: function(data) {
                        if(data.length == 0){
                            hataAc('Kriterlerinize uygun sonuç bulunamadı!');
                            kayitlariTemizle();
                        }
                        else{
                            //console.log(data);
                            kayitlariTemizle();
                            var projeler = {
                                  'maliyet'     : [['Proje', 'Ortalama Maliyet(TL)']],
                                  'karlilik'    : [['Proje', 'Ortalama Kârlılık(TL)']],
                                  'ulasilabilir': [['Proje', 'Ortalama Ulaşılabilirlik (KM)']],
                                  'tamamlanma'  : [['Proje', 'Ortalama Tamamlanma(YIL)']],
                                  'geridonus'   : [['Proje', 'Ortalama Geri Dönüş(YIL)']]
                                };

                                for(i=0;i<data.length;i++){
                                    projeler.maliyet.push([data[i].proje_grubu, ortalamaHesapla(data[i].maliyet_grup)[2]]); //BURAYA GEL
                                    projeler.karlilik.push([data[i].proje_grubu, ortalamaHesapla(data[i].karlilik_grup)[2]]);
                                    projeler.ulasilabilir.push([data[i].proje_grubu, ortalamaHesapla(data[i].ulasim_grup)[2]]);
                                    projeler.tamamlanma.push([data[i].proje_grubu, ortalamaHesapla(data[i].ts_grup)[2]]);
                                    projeler.geridonus.push([data[i].proje_grubu, ortalamaHesapla(data[i].gds_grup)[2]]);
                                }
                                drawChart1();
                                google.charts.setOnLoadCallback(drawChart1);
                                drawChart2();
                                google.charts.setOnLoadCallback(drawChart2);
                                drawChart3();
                                google.charts.setOnLoadCallback(drawChart3);
                                drawChart4();
                                google.charts.setOnLoadCallback(drawChart4);

                                function drawChart1() {
                                    //console.log(projeler.karlilik);
                                    var data = google.visualization.arrayToDataTable(
                                        projeler.maliyet
                                    );
                                
                                  var options = {
                                    title: 'Ortalama Maliyet(TL)'
                                  };
                              
                                  var chart = new google.visualization.PieChart(document.getElementById('proje_kararlilik'));
                              
                                  chart.draw(data, options);
                                }
                                function drawChart2() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.karlilik
                                );
                              
                                var options = {
                                  title: 'Ortalama Kârlılık(TL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_ulasilabilirlik'));
                            
                                chart.draw(data, options);
                              }
                              function drawChart3() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.ulasilabilir
                                );
                              
                                var options = {
                                  title: 'Ortalama Ulaşılabilirlik (KM)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_tamamlanma_suresi'));
                            
                                chart.draw(data, options);
                              }
                              function drawChart4() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.geridonus
                                );
                              
                                var options = {
                                  title: 'Ortalama Geri Dönüş(YIL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_geri_donus_suresi'));
                            
                                chart.draw(data, options);
                              }
                            
                        }
                    }
                });
            }
            else{
                hataAc('Herhangi bir veri seçmeden listeleme yapamazsın!');
            }
        });
        $(document).on('click', '#buton-gds', function() {
                if($('#sec-geri').val() != 'none'){
                    $.ajax({
                    url     : 'islemler.php',
                    type    : 'POST',
                    dataType: 'JSON',
                    data    : {'islem' : 'ozelGrafik', 'deger':$('#sec-geri').val(), 'anahtar':'geri'},
                    success: function(data) {
                        if(data.length == 0){
                            hataAc('Kriterlerinize uygun sonuç bulunamadı!');
                            kayitlariTemizle();
                        }
                        else{
                            //console.log(data);
                            kayitlariTemizle();
                            var projeler = {
                                  'maliyet'     : [['Proje', 'Ortalama Maliyet(TL)']],
                                  'karlilik'    : [['Proje', 'Ortalama Kârlılık(TL)']],
                                  'ulasilabilir': [['Proje', 'Ortalama Ulaşılabilirlik (KM)']],
                                  'tamamlanma'  : [['Proje', 'Ortalama Tamamlanma(YIL)']],
                                  'geridonus'   : [['Proje', 'Ortalama Geri Dönüş(YIL)']]
                                };

                                for(i=0;i<data.length;i++){
                                    projeler.maliyet.push([data[i].proje_grubu, ortalamaHesapla(data[i].maliyet_grup)[2]]); //BURAYA GEL
                                    projeler.karlilik.push([data[i].proje_grubu, ortalamaHesapla(data[i].karlilik_grup)[2]]);
                                    projeler.ulasilabilir.push([data[i].proje_grubu, ortalamaHesapla(data[i].ulasim_grup)[2]]);
                                    projeler.tamamlanma.push([data[i].proje_grubu, ortalamaHesapla(data[i].ts_grup)[2]]);
                                    projeler.geridonus.push([data[i].proje_grubu, ortalamaHesapla(data[i].gds_grup)[2]]);
                                }
                                drawChart1();
                                google.charts.setOnLoadCallback(drawChart1);
                                drawChart2();
                                google.charts.setOnLoadCallback(drawChart2);
                                drawChart3();
                                google.charts.setOnLoadCallback(drawChart3);
                                drawChart4();
                                google.charts.setOnLoadCallback(drawChart4);

                                function drawChart1() {
                                    //console.log(projeler.karlilik);
                                    var data = google.visualization.arrayToDataTable(
                                        projeler.maliyet
                                    );
                                
                                  var options = {
                                    title: 'Ortalama Maliyet(TL)'
                                  };
                              
                                  var chart = new google.visualization.PieChart(document.getElementById('proje_kararlilik'));
                              
                                  chart.draw(data, options);
                                }
                                function drawChart2() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.karlilik
                                );
                              
                                var options = {
                                  title: 'Ortalama Kârlılık(TL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_ulasilabilirlik'));
                            
                                chart.draw(data, options);
                              }
                              function drawChart3() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.ulasilabilir
                                );
                              
                                var options = {
                                  title: 'Ortalama Ulaşılabilirlik (KM)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_tamamlanma_suresi'));
                            
                                chart.draw(data, options);
                              }
                              function drawChart4() {
                                
                                var data = google.visualization.arrayToDataTable(
                                    projeler.tamamlanma
                                );
                              
                                var options = {
                                  title: 'Ortalama Tamamlanma(YIL)'
                                };
                            
                                var chart = new google.visualization.PieChart(document.getElementById('proje_geri_donus_suresi'));
                            
                                chart.draw(data, options);
                              }
                        }
                    }
                });
            }
            else{
                hataAc('Herhangi bir veri seçmeden listeleme yapamazsın!');
            }

        });

        function hatayiKapat(){
            document.getElementById('uyari-mesaji').innerHTML = '';
            document.getElementById('uyari-mesaji').style.display = "none";
        }

        function hataAc(mesaj){
            document.getElementById('uyari-mesaji').innerHTML = mesaj;
            document.getElementById('uyari-mesaji').style.display = "block";
            setTimeout(hatayiKapat, 3000);
        }

        function kayitlariTemizle(){
            $('#proje_maliyet').html('');
            $('#proje_kararlilik').html('');
            $('#proje_ulasilabilirlik').html('');
            $('#proje_tamamlanma_suresi').html('');
            $('#proje_geri_donus_suresi').html('');

        }

        function ortalamaHesapla(veri){
            var cikti = veri.replace('.', '');
            cikti = cikti.replace('.', '');
            cikti = cikti.replace('.', '');
            cikti = cikti.replace('TL', '');
            cikti = cikti.replace('yıl', '');
            cikti = cikti.replace('km', '');
            cikti = cikti.split('-');
            cikti[0] = parseInt(cikti[0]);
            cikti[1] = parseInt(cikti[1]);
            cikti[2] = Math.round(((cikti[0]+cikti[1])/2));
            return cikti;
        }
    </script>
</body>
</html>