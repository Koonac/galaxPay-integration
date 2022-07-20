
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
<title>Imprimir cartão {{strtoupper($clienteGalaxPay->nome_cliente)}}</title>
<style>
  .cardImage{
    background-image:url("{{public_path('assets/layoutCards/cartaoSoliVerso.jpg')}}");
    background-repeat:no-repeat;
    background-size: 100% 100%;
    width:85.60mm;
    height:53.98mm;
    display:inline-block;
    /* border:1px solid #999; */
  }

  .cardImage2{
    margin:0 auto;
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
  table {
    /* margin-bottom: 2em; */
  }
  
  thead {
    /* background-color: #eeeeee; */
  }
  
  tbody {
  /* background-color: #ffffee; */
  }

  .dataCard{
    font-size: 35px;
    padding: 0;
  }

  .fontTextCard{
    font-size: 45px;
  }

  table.frenteVerso{
    width: 171.20mm;
    height: 53.98mm;
    /* border: 1pt solid black;   */
  }

  table.card {
    width: 85.60mm;
    height: 53.98mm;
    border: 1pt solid black;  
  }

  table.card td {
    /* border: 1pt solid black; */
  }
</style>
<script> 
  window.print(); 
</script>

</head>

<body>
  <table class="frenteVerso">
    <thead>
      <tr>
        <th width='50%'></th>
        <th width='50%'></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <table class="card">
            <thead>
              <tr>
                <th width='50%'></th>
                <th width='50%'></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td height='10mm'></td>
                <td height='10mm'></td>
              </tr>
              <tr>
                <td style="padding-left: 2mm; max-height: 7mm; display:inline-block;"><span class='fontTextCard'></span></td>
                <td><span class='dataCard' style="padding: 0;"></span></td>
              </tr>
              <tr>
                <td height='4mm'></td>
                <td height='4mm'></td>
              </tr>
              <tr>
                <td height='7mm' style="padding-left: 2mm;"><span class='fontTextCard'></span></td>
                <td height='7mm'></td>
              </tr>
              <tr>
                <td height='4mm'></td>
                <td height='4mm'></td>
              </tr>
              <tr>
                <td height='7mm' style="padding-left: 2mm;"><span class='fontTextCard'></span></td>
                <td height='7mm'></td>
              </tr>
            </tbody>
          </table>
        </td>
        <td>
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
                  <td height='10mm'></td>
                  <td height='10mm'></td>
                </tr>
                <tr>
                  <td style="padding-left: 2mm; height: 7mm; display:inline-block;"><span class='fontTextCard'>{{strtoupper($clienteGalaxPay->nome_cliente)}}</span></td>
                  <td><span class='dataCard' style="padding: 0;">{{date('d/m/Y', strtotime($clienteGalaxPay->createdAt))}}</span></td>
                </tr>
                <tr>
                  <td height='4mm'></td>
                  <td height='4mm'></td>
                </tr>
                <tr>
                  <td height='7mm' style="padding-left: 2mm;"><span class='fontTextCard'>{{strtoupper($clienteGalaxPay->codigo_cliente_galaxpay)}}</span></td>
                  <td height='7mm'></td>
                </tr>
                <tr>
                  <td height='4mm'></td>
                  <td height='4mm'></td>
                </tr>
                <tr>
                  <td height='7mm' style="padding-left: 2mm;"><span class='fontTextCard'>{{date('d/m/Y', strtotime($clienteGalaxPay->updateAt))}}</span></td>
                  <td height='7mm'></td>
                </tr>
              </tbody>
            </table>
          {{-- </div> --}}
        </td>
      </tr>
    </tbody>
  </table>

</body> 
</html>