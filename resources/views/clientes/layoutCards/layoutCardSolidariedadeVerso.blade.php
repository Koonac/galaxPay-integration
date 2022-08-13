
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Imprimir cartão {{strtoupper($clienteGalaxPay->nome_cliente)}}</title>
<style>
   @page {
          margin: 0px 0px 0px 0px !important;
          padding: 0px 0px 0px 0px !important;
        }
  .cardImage{
    background-image:url("{{public_path('assets/layoutCards/cartaoSoliVerso.jpg')}}");
    background-repeat:no-repeat;
    background-size: 100% 100%;
    /* width:85.60mm;
    height:53.98mm; */
    width: 34.24cm;
    height: 21.58cm;
    display:inline-block;
    /* border:1px solid #999; */
  }

  .cardImage2{
    margin:0 auto;
    width:85.60mm;
    height:53.98mm;
    width:85.60mm;
    height:53.98mm;
    float:left;
    border:1px solid #666666;
    display:inline-block;
    background-image:url("{{public_path('assets/layoutCards/cartaoSoliFrente.jpg')}}");
    background-repeat:no-repeat;
    background-size:100% 100%;
  }
</style>
<style>  
  thead {
    /* background-color: #eeeeee; */
  }
  
  tbody {
  /* background-color: #ffffee; */
  }

  .dataCard{
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    font-size: 45px;
    font-weight: bold;
    padding: 0;
  }
  
  .fontTextCard{
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    font-size: 55px;
    font-weight: bold;
  }

  table.card {
    /* width: 85.60mm;
    height: 53.98mm; */
    width: 34.24cm;
    height: 21.58cm;
    /* border: 1pt solid black;   */
  }

  table.card td {
    /*border: 1pt solid black; */
  }
</style>
<script> 
  window.print(); 
</script>

</head>

<body>
  {{-- <div class="cardImage"> --}}
    <table class="card">
      <thead>
        <tr>
          <th width='50%'></th>
          <th width='50%'></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td height='40mm'></td>
          <td height='40mm'></td>
        </tr>
        <tr>
          <td style="padding-left: 4mm; padding-top: 10mm; height: 28mm; display:inline-block;"><span class='fontTextCard' name='nomeCliente'>{{strtoupper($clienteGalaxPay->nome_cliente)}}</span></td>
          <td><span class='dataCard' name='dataEmissao' style="padding: 0;">{{date('d/m/Y', strtotime($clienteGalaxPay->createdAt))}}</span></td>
        </tr>
        <tr>
          <td height='16mm'></td>
          <td height='16mm'></td>
        </tr>
        <tr>
          <td height='28mm' style="padding-left: 4mm; padding-top: 10mm;"><span class='fontTextCard' name='matricula'>{{strtoupper($matriculaClienteGalaxpay)}}</span></td>
          <td height='28mm'></td>
        </tr>
        <tr>
          <td height='16mm'></td>
          <td height='16mm'></td>
        </tr>
        <tr>
          <td height='28mm' style="padding-left: 4mm; padding-top: 5mm;"><span class='fontTextCard' name='dataNascimento'>{{date('d/m/Y', strtotime($dataNascimentoClienteGalaxpay))}}</span></td>
          <td height='28mm'></td>
        </tr>
      </tbody>
    </table>
  {{-- </div> --}}
      
  <script>
    window.print();
  </script>
</body> 
</html>