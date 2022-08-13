
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Imprimir cartão {{strtoupper($clienteGalaxPay->nome_cliente)}}</title>
  <style>
    body{
      margin:0 auto !important;
      padding: 0 !important;
    }
    .card{
      background-image:url(../cartoes_img/6306605127VERS0.jpg?345196845);
      background-repeat:no-repeat;
      background-size: 100% 100%;
      margin:0 auto;
      width:85.60mm;
      height:53.98mm;
      display:inline-block;
      border:1px solid #999;
  
        
    }
  
    .email{
      margin:5px 0 0 10px;
      font-size:10px;
      text-align:left;
      float:left;
    }
    .contato{
      margin:5px 10px 0 0;
      font-size:10px;
      text-align:right;
      float:right;
    }
    .conteudo{
      margin-top:25px;
      width:310px;
      height:80px;
      text-align:center;
      line-height:50px;
      font-size:13px;
    }
    .cod_bar{
      margin-top:-15px;
      height:40px;
    }
    .cod_num{
      margin-top:-25px;
      position:relative;
    }
    #bar_desde{
      margin:0 10px 0 10px;
      margin-top:-16px;
      width: 100%;
    }
    .desde_l{
      display:inline-block;
      font-size:10px;
      width: 30%;
      text-align: left;
    }
    .desde_c{
      display:inline-block;
      font-size:10px;
      width: 30%;
      text-align: center;
    }
    .desde_r{
      display:inline-block;
      font-size:10px;
      width: 30%;
      text-align: right;
    }
    .cod_barra{
      margin-top: -15px;
    }
    .num_cartao{
      text-transform:uppercase;
      font-size:10px;
      position: relative;
      margin-top: -16px; 
    }
    #aki{
      width:400px;
      height:400px;
      display: block;
      border:1px solid #000;
    }
    canvas{
      max-width: 100%;
      max-height: 100%;
    }
  
  </style>
  
  <script> 
    window.print(); 
  </script>

</head>

<body>
  <div id="card" class="card">
    	<style type="text/css">
    		#box_cartao{
    			width: 85.60mm;
    			height: 53.98mm;
    		}
    	</style>


    	<div id="box_cartao">
    		<div style="width: 42mm; height: 53.98mm; display: inline-block; vertical-align: top">
    			<div style="height: 12.5mm; vertical-align: middle;"></div>
    			<div style="height: 14.5mm; padding-left: 2mm; vertical-align: middle;">{{strtoupper($clienteGalaxPay->nome_cliente)}}</div>
    			<div style="height: 12.5mm; padding-left: 2mm; vertical-align: middle;"> {{strtoupper($clienteGalaxPay->codigo_cliente_galaxpay)}} </div>
    			<div style="height: 10.5mm; padding-left: 2mm; vertical-align: middle;"> {{date('d/m/Y', strtotime($clienteGalaxPay->createdAt))}} </div>
    		</div>

    		<div style="width: 30mm; height: 8mm; display: inline-block; margin-left: 10px;">
    			<div style="font-size: 11px; height: 11mm;"> </div>
    			<div style="font-size: 11px; height: 10mm;"> {{date('d/m/Y', strtotime($clienteGalaxPay->updateAt))}} </div>
    		</div>
    	</div>     
    </div>
</body>
</html>