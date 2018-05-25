<?php
    require_once('config.php');
    $islem = $_POST['islem'];

    switch($islem){
        case 'giris':
            $giris_basarili = $vt->query('SELECT * FROM kullanicilar WHERE kullanici_adi="'.$_POST['k_adi'].'" AND sifre="'.md5($_POST['sifre']).'"');
            $sonuc = $giris_basarili->fetch();
            if($sonuc){
                 
                $_SESSION['kullanici_adi'] = $sonuc['kullanici_adi'];
                echo json_encode(true);
            }
            else{echo json_encode(false);}
        break;
        case 'projeleriGetir':
            $projeSorgu = $vt->query('SELECT 
            projeler.proje_grubu,
            karlilik.karlilik_grup,
            geri_donus_suresi.gds_grup,
            maliyet.maliyet_grup,
            ulasilabilirlik.ulasim_grup,
            tamamlanma_suresi.ts_grup
            FROM
            ara_tablo
            INNER JOIN geri_donus_suresi ON ara_tablo.gds_id = geri_donus_suresi.gds_id
            INNER JOIN karlilik ON ara_tablo.karlilik_id = karlilik.karlilik_id
            INNER JOIN maliyet ON ara_tablo.maliyet_id = maliyet.maliyet_id
            INNER JOIN projeler ON ara_tablo.proje_id = projeler.proje_id
            INNER JOIN tamamlanma_suresi ON ara_tablo.ts_id = tamamlanma_suresi.ts_id
            INNER JOIN ulasilabilirlik ON ara_tablo.ulasim_id = ulasilabilirlik.ulasim_id');

            $projeler = $projeSorgu->fetchAll(PDO::FETCH_OBJ);

            foreach($projeler as $key=>$proje){
                $cikti[$key]['projeAdi'] = $proje->proje_grubu;
                $cikti[$key]['karlilik'] = veriTemizle($proje->karlilik_grup);
                $cikti[$key]['geriDonus'] = veriTemizle($proje->gds_grup);
                $cikti[$key]['maliyet'] = veriTemizle($proje->maliyet_grup);
                $cikti[$key]['ulasim'] = veriTemizle($proje->ulasim_grup);
                $cikti[$key]['tamamlanmaSuresi'] = veriTemizle($proje->ts_grup);
            }

            echo json_encode($cikti);
        break;
        case 'secenekleriGetir':
            $karSorgu = $vt->query('SELECT * FROM karlilik');
            $donusSorgu = $vt->query('SELECT * FROM geri_donus_suresi');
            $maliyetSorgu = $vt->query('SELECT * FROM maliyet');
            $tamamlanmaSorgu = $vt->query('SELECT * FROM tamamlanma_suresi');
            $ulasilabilirSorgu = $vt->query('SELECT * FROM ulasilabilirlik');

            $karlilik = $karSorgu->fetchAll(PDO::FETCH_OBJ);
            $donusSuresi = $donusSorgu->fetchAll(PDO::FETCH_OBJ);
            $maliyet = $maliyetSorgu->fetchAll(PDO::FETCH_OBJ);
            $tamamlanmaSuresi = $tamamlanmaSorgu->fetchAll(PDO::FETCH_OBJ);
            $ulasilabilirlik = $ulasilabilirSorgu->fetchAll(PDO::FETCH_OBJ);

            $cikti = [
                'karlilik'=> [],
                'donus'=> [],
                'maliyet'=> [],
                'tamamlanma'=> [],
                'ulasilabilirlik'=> [],
            ];

            foreach($karlilik as $key=>$kar){
                $cikti['karlilik'][$key] = $kar->karlilik_grup;
            }
            foreach($donusSuresi as $key=>$don){
                $cikti['donus'][$key] = $don->gds_grup;
            }
            foreach($maliyet as $key=>$mal){
                $cikti['maliyet'][$key] = $mal->maliyet_grup;
            }
            foreach($tamamlanmaSuresi as $key=>$tam){
                $cikti['tamamlanma'][$key] = $tam->ts_grup;
            }
            foreach($ulasilabilirlik as $key=>$ula){
                $cikti['ulasilabilirlik'][$key] = $ula->ulasim_grup;
            }

            echo json_encode($cikti);
        break;
        case 'ozelGrafik':
            switch($_POST['anahtar']){
                case 'maliyet':
                    $sorgu = $vt->query('SELECT
                    maliyet.maliyet_grup,
                    karlilik.karlilik_grup,
                    tamamlanma_suresi.ts_grup,
                    ulasilabilirlik.ulasim_grup,
                    projeler.proje_grubu,
                    geri_donus_suresi.gds_grup
                    FROM
                    ara_tablo
                    INNER JOIN geri_donus_suresi ON ara_tablo.gds_id = geri_donus_suresi.gds_id
                    INNER JOIN karlilik ON ara_tablo.karlilik_id = karlilik.karlilik_id
                    INNER JOIN maliyet ON ara_tablo.maliyet_id = maliyet.maliyet_id
                    INNER JOIN projeler ON ara_tablo.proje_id = projeler.proje_id
                    INNER JOIN ulasilabilirlik ON ara_tablo.ulasim_id = ulasilabilirlik.ulasim_id
                    INNER JOIN tamamlanma_suresi ON ara_tablo.ts_id = tamamlanma_suresi.ts_id
                    WHERE
                    maliyet.maliyet_grup ="'.$_POST['deger'].'"');

                    echo json_encode($sorgu->fetchAll(PDO::FETCH_OBJ));
                break;
                case 'kar':
                    $sorgu = $vt->query('SELECT
                    maliyet.maliyet_grup,
                    karlilik.karlilik_grup,
                    tamamlanma_suresi.ts_grup,
                    ulasilabilirlik.ulasim_grup,
                    projeler.proje_grubu,
                    geri_donus_suresi.gds_grup
                    FROM
                    ara_tablo
                    INNER JOIN geri_donus_suresi ON ara_tablo.gds_id = geri_donus_suresi.gds_id
                    INNER JOIN karlilik ON ara_tablo.karlilik_id = karlilik.karlilik_id
                    INNER JOIN maliyet ON ara_tablo.maliyet_id = maliyet.maliyet_id
                    INNER JOIN projeler ON ara_tablo.proje_id = projeler.proje_id
                    INNER JOIN ulasilabilirlik ON ara_tablo.ulasim_id = ulasilabilirlik.ulasim_id
                    INNER JOIN tamamlanma_suresi ON ara_tablo.ts_id = tamamlanma_suresi.ts_id
                    WHERE
                    karlilik.karlilik_grup = "'.$_POST['deger'].'"');

                    echo json_encode($sorgu->fetchAll(PDO::FETCH_OBJ));
                break;
                case 'ulas':
                    $sorgu = $vt->query('SELECT
                    maliyet.maliyet_grup,
                    karlilik.karlilik_grup,
                    tamamlanma_suresi.ts_grup,
                    ulasilabilirlik.ulasim_grup,
                    projeler.proje_grubu,
                    geri_donus_suresi.gds_grup
                    FROM
                    ara_tablo
                    INNER JOIN geri_donus_suresi ON ara_tablo.gds_id = geri_donus_suresi.gds_id
                    INNER JOIN karlilik ON ara_tablo.karlilik_id = karlilik.karlilik_id
                    INNER JOIN maliyet ON ara_tablo.maliyet_id = maliyet.maliyet_id
                    INNER JOIN projeler ON ara_tablo.proje_id = projeler.proje_id
                    INNER JOIN ulasilabilirlik ON ara_tablo.ulasim_id = ulasilabilirlik.ulasim_id
                    INNER JOIN tamamlanma_suresi ON ara_tablo.ts_id = tamamlanma_suresi.ts_id
                    WHERE
                    ulasilabilirlik.ulasim_grup ="'.$_POST['deger'].'"');

                    echo json_encode($sorgu->fetchAll(PDO::FETCH_OBJ));
                break;
                case 'ts':
                    $sorgu = $vt->query('SELECT
                    maliyet.maliyet_grup,
                    karlilik.karlilik_grup,
                    tamamlanma_suresi.ts_grup,
                    ulasilabilirlik.ulasim_grup,
                    projeler.proje_grubu,
                    geri_donus_suresi.gds_grup
                    FROM
                    ara_tablo
                    INNER JOIN geri_donus_suresi ON ara_tablo.gds_id = geri_donus_suresi.gds_id
                    INNER JOIN karlilik ON ara_tablo.karlilik_id = karlilik.karlilik_id
                    INNER JOIN maliyet ON ara_tablo.maliyet_id = maliyet.maliyet_id
                    INNER JOIN projeler ON ara_tablo.proje_id = projeler.proje_id
                    INNER JOIN ulasilabilirlik ON ara_tablo.ulasim_id = ulasilabilirlik.ulasim_id
                    INNER JOIN tamamlanma_suresi ON ara_tablo.ts_id = tamamlanma_suresi.ts_id
                    WHERE
                    tamamlanma_suresi.ts_grup ="'.$_POST['deger'].'"');

                    echo json_encode($sorgu->fetchAll(PDO::FETCH_OBJ));
                break;
                case 'geri':
                    $sorgu = $vt->query('SELECT
                    maliyet.maliyet_grup,
                    karlilik.karlilik_grup,
                    tamamlanma_suresi.ts_grup,
                    ulasilabilirlik.ulasim_grup,
                    projeler.proje_grubu,
                    geri_donus_suresi.gds_grup
                    FROM
                    ara_tablo
                    INNER JOIN geri_donus_suresi ON ara_tablo.gds_id = geri_donus_suresi.gds_id
                    INNER JOIN karlilik ON ara_tablo.karlilik_id = karlilik.karlilik_id
                    INNER JOIN maliyet ON ara_tablo.maliyet_id = maliyet.maliyet_id
                    INNER JOIN projeler ON ara_tablo.proje_id = projeler.proje_id
                    INNER JOIN ulasilabilirlik ON ara_tablo.ulasim_id = ulasilabilirlik.ulasim_id
                    INNER JOIN tamamlanma_suresi ON ara_tablo.ts_id = tamamlanma_suresi.ts_id
                    WHERE
                    geri_donus_suresi.gds_grup ="'.$_POST['deger'].'"');

                    echo json_encode($sorgu->fetchAll(PDO::FETCH_OBJ));
                break;
            }
        break;
    }

    function veriTemizle($veri){
        $veri = str_replace('TL', '', $veri);
        $veri = str_replace('.', '', $veri);
        $veri = str_replace('yıl', '', $veri);
        $veri = str_replace('km', '', $veri);

        $duzenle = explode('-', $veri);
        $ortalama = ((int)$duzenle[0]+(int)$duzenle[1])/2;
        return array((int)$duzenle[0], (int)$duzenle[1], round($ortalama)); //0: Min, 1:Max, 2:Ortalama
    }