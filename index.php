<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Yatırım Değerlendirme | Giriş</title>
    <link rel="stylesheet" href="css-js/css/style.css">
    <link rel="stylesheet" href="css-js/css/bootstrap.min.css">
</head>
<body class="text-center">


    <div class="form-signin">
        <h1 class="h3 mb-3 font-weight-normal">Giriş Yap</h1>
        <label for="kullanici_adi" class="sr-only">Kullanıcı Adı:</label>
        <input type="text" id="kullanici_adi" class="form-control" placeholder="Kullanıcı Adı" required autofocus>
        <label for="sifre" class="sr-only">Şifre:</label>
        <input type="password" id="sifre" class="form-control" placeholder="Şifre" required>
        <button class="btn btn-lg btn-primary btn-block" type="button" id="giris_buton">Giriş</button>
        <div class="bosluk"></div>
        <div class="alert alert-danger" role="alert" id="hata_goster"></div>
    </div>



    <!-- JS -->
    <script src="css-js/js/jquery.js"></script>
    <script src="css-js/js/bootstrap.min.js"></script>
    <script>
       $(document).ready(function() {
           $(document).on('click', '#giris_buton', function(){
                
                var formDolu = false;
                if($('#kullanici_adi').val().length > 0 && $('#sifre').val().length > 0){formDolu = true;}
                else {formDolu = false;} 
                if(formDolu === false){
                    
                    document.getElementById("hata_goster").style.display = 'block';                          
                    document.getElementById("hata_goster").innerHTML ='Lütfen yukarıdaki alanları doldurunuz.';  
                }
                else{
                    
                    var kullaniciAdi   = $('#kullanici_adi').val(); 
                    var kullaniciSifre = $('#sifre').val();
                    $.ajax({
                        url     : 'islemler.php',
                        type    : 'POST',
                        dataType: 'JSON',
                        data    : {'islem' : 'giris', 'k_adi': kullaniciAdi, 'sifre': kullaniciSifre}, 
                        success : function(donen) {
                            if(donen === false){
                                
                                document.getElementById("hata_goster").style.display = 'block';                       
                                document.getElementById("hata_goster").innerHTML ='Kullanıcı Adı veya Şifre Yanlış'; 
                            }
                            else{
                                
                                window.location.href = "home.php"; 
                            }
                        }
                        
                    });
                }

           });
       });
    </script>
</body>
</html>