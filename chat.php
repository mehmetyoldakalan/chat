<?php 
include "fonksiyon.php";
$chat=new chat();
$chat->oturum_kontrol($db,false);
$chat->renklerebak($db);
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Başlıksız Belge</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="jscolor.js"></script>
        

    <script>
        $(document).ready(function() {
            $("#kapsayici").hide();
            $("#konusmalar").load("fonksiyon.php?chat=oku");
            setInterval(function(){
                $("#konusmalar").load("fonksiyon.php?chat=oku");
            },2000);
            setInterval(function(){
                $("#yaziyor").load("fonksiyon.php?chat=dosyaoku");
            },2000);

            $("#gonder").keyup(function(e){
                var text=$("#gonder").val();
                var karakter=$("#gonder").attr("maxlength");
                var uzunluk=text.length;
                if (e.keyCode==13) {
                    if (uzunluk>1&&uzunluk<karakter) {
                        $.ajax({
                            type:"POST",
                            url:"fonksiyon.php?chat=ekle",
                            data:$("#mesajgonder").serialize(),
                            success:function(donen_bilgi){
                                $("#gonder").val("");
                                $("#konusmalar").load("fonksiyon.php?chat=oku");
                                $("#konusmalar").scrollTop($("#konusmalar")[0].scrollHeight);
                            }
                        });
                    }
                    else{
                        $("#gonder").val("");
                    }
                }
            });

            $("#sohbetayar a").click(function(){
                var gelendeger=$(this).attr("sectionId");
                $.post("fonksiyon.php?chat=sohbetayar",{"secenek":gelendeger},function(gelen_veri){
                    $("#sohbetayar").html(gelen_veri).fadeIn();
                    setInterval(function(){
                        window.location.reload();
                    },2000);
                });

            });

            $("#arkabuton").click(function(){
                $.ajax({
                            type:"POST",
                            url:"fonksiyon.php?chat=arkarenk",
                            data:$("#arkaplan").serialize(),
                            success:function(gelen_veri){
                                $("#arkaplandegistir").html(gelen_veri).fadeIn();
                                setInterval(function(){
                                    window.location.reload();
                                },2000);
                               
                            }
                        });
            });
            $("#yazibuton").click(function(){
                $.ajax({
                            type:"POST",
                            url:"fonksiyon.php?chat=yazirenk",
                            data:$("#yazirenk").serialize(),
                            success:function(gelen_veri){
                                $("#yazidegistir").html(gelen_veri).fadeIn();
                                setInterval(function(){
                                    window.location.reload();
                                },2000);
                               
                            }
                        });
            });


            $("#ozellikackapat").click(function(){
                $("#kapsayici").slideToggle();
            });
            var sayac=0;
            $('body').delegate('#gonder',"keyup change",function(){
                var text=$("#gonder").val();
                var uzunluk=text.length;
                var uyead=$("#gonder").attr("sectionId");
                if(uzunluk>0&&sayac==0)
                {
                    $.get("fonksiyon.php?chat=ortak",{"uyead":uyead},function(){
                        setInterval(function(){
                            $("#yaziyor").load("fonksiyon.php?chat=dosyaoku");
                        },2000);
                        sayac=1;
                    });
                }
                else if(uzunluk==0)
                {
                    
						$.get("fonksiyon.php?chat=ortak",{"temizle":uyead},function(){
						
						
						
                        setInterval(function() {
                        $("#yaziyor").load("fonksiyon.php?chat=dosyaoku");},2000);
                    
                    sayac=0;
                    });
                }
            });

                
            
    });
    </script>
 
 
 <style>
 body {
	 background-color:#F3F3F3;
	 
 }
 
 #kivir {
	 border-radius:10px;
	 border:1px solid #E0E0E0;
	min-height:400px; 
 }
 
  #renk {
	 border-radius:10px;
	 border:1px solid #E0E0E0;
	min-height:50px; 
 }
 
 
 </style>
</head>

<body >

<div class="container text-center " >

            <div class="row">
            
            
            
            		<div class="col-md-6 bg-white mx-auto mt-5" id="kivir" >
                    
                    		<div class="row">
    <div class="col-md-12"><h3 class="text-secondary border-bottom p-2">PHP JQUERY CHAT UYGULAMASI</h3></div>
     <div class="col-md-3 border-right text-left " style="min-height:350px;">
                
                <div class="row">
      <div class="col-md-12"><h5 class="text-white bg-info border-bottom text-center">KİŞİLER</h5> </div>
      
       <div class="col-md-12" style="min-height:290px;"><?php $chat->kisi_getir($db) ?></div>
       
        <div class="col-md-12 bg-light text-center"> <a href="fonksiyon.php?chat=cikis" class="btn btn-warning btn-sm p-0 m-1">Çıkış Yap</a></div>
      
                </div>
     		
     
     
     
     
    
     </div>
     
     
     <div class="col-md-9">
     
     
     				<div class="row">
     <div class="col-md-12 bg-white text-left" id="konusmalar" style="overflow-y: scroll; height:250px; width: auto;" >
                   <!-- YAZIŞMALAR -->    
                         
                         </div>
                         
                         
                         
                         
                    
     					<div class="col-md-12"><form id="mesajgonder">
           
            <textarea id="gonder" name="mesaj" maxlength = '100' cols="10" rows="3" class="form-control mt-2 " sectionId="<?php echo $_COOKIE['kisiad']; ?>"></textarea>
        </form> </div>
    				 </div>	
     </div>	
        
                            
                    		 </div>
                    
                                
                    
                    </div>
                    
					
                   
            
            </div>
			
			
			
			 <div class="row"> 
    			 <div class="col-md-6 bg-white mx-auto mt-2" id="renk">
                    <div class="row text-center">
                        <div class="col-md-12">
                            <div class="row text-left">
                                <div class="col-md-9 border-right bg-light text-danger p-1" id="yaziyor"></div>
                                <div class="col-md-3 text-right" ><a class="btn btn-danger btn-sm p-1 m-1 text-white" id="ozellikackapat">özellik aç / kapat</a></div>
                            </div>
                        </div>
                        <div class="row" id="kapsayici">
                            <div class="col-md-4 border-right"id="arkaplandegistir">
                                <form id="arkaplan">      
                                arkaplan değiştir <br>     
                                    <input type="text" name="arkaplankod" class="form-control mt-1 jscolor" value="<?php echo $chat->arkaplan; ?>">
                                    <input type="button" id="arkabuton" value="değiştir" class="btn btn-success btn-sm mt-1 mb-1 ">
                                </form>
                            </div>
                            <div class="col-md-4 border-right" id="yazidegistir">
                                <form id="yazirenk">      
                                yazı renk değiştir <br>     
                                    <input type="text" name="yazirenkkod" class="form-control mt-1 jscolor" value="<?php echo $chat->yazirenk ?>">
                                    <input type="button" id="yazibuton" value="değiştir" class="btn btn-success btn-sm mt-1 mb-1 ">
                                </form>
                            </div>
                            <div class="col-md-4" id="sohbetayar">
                                ayarlar <br>
                                <a class="btn btn-dark btn-sm mt-1 mb-1 text-white" sectionId="temizle">sohbeti temizle</a>
                                <a class="btn btn-dark btn-sm mt-1 mb-1 text-white" sectionId="kaydet">sohbeti kaydet</a>

                            </div>
                        </div>
                    </div>

                 </div> 
			 </div>

</div>




</body>
</html>