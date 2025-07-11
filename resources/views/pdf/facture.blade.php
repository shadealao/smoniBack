<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Facture</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
      background: #fff;
      color: #000;
    }

    .line-yellow{
        width: 100%;
        height: 5px;
        background-color: #f7c600;
        position: absolute;
        top: 0;
        left: 0;

    }
    .header {
      display: flex;
      justify-content: space-between; 
      margin-bottom: 30px;
    }
    img.logo {
      max-width: 150px;
      height: auto;
    }
    .invoice {
      max-width: 800px;
      margin: auto;
    }

    .top-info {
      justify-content: space-between;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .company-info {
      display: flex;
      gap: 100px;
    }

    .top-info p {
      margin: 0 0 10px;
      font-size: 14px;
    }

    .amount-due {
      font-size: 20px;
      font-weight: bold;
      margin: 20px 0 10px;
    }

    .pay-link {
      color: blue;
      text-decoration: underline;
      font-size: 14px;
      display: inline-block;
      margin-bottom: 20px;
    }

    .invoice-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
      font-size: 14px;
    }

    .invoice-table th {
      border-bottom: 1px solid #000;
      padding-bottom: 5px;
      text-align: left;
      vertical-align: top;
    }

    .invoice-table .highlight {
      background-color: #cce5ff;
      font-weight: bold;
    }

    .invoice-table small {
      font-size: 12px;
      color: #555;
    }

    .summary {
      width: fit-content;
      margin-left: auto;
      border-collapse: collapse;
      font-size: 14px;
    }

    .summary td {
      padding: 0 12px;
      text-align: right;
    }

    .summary td:first-child {
      text-align: left;
    }


    .summary tr {
      border-top: 0.2px dashed #958b8b;
    }


    .summary tr:first-child {
      border-top: none;
    }

    .summary .bold td {
      font-weight: bold;
    }

  </style>
</head>
<body>

    <div class="line-yellow"></div>

    </div>
  <div class="invoice">

    <div class="header">
      <h1>Facture</h1>
      <img src="{{asset('image.png')}}" alt="Logo de SMONI AUTO-MOTO ÉCOLE" class="logo">
    </div>

    <div class="top-info">
      <div>
        <p><strong>Numéro de facture</strong>{{$withdraw->invoice_code}}</p>
        <p><strong>Date d’émission</strong>{{date('d M Y',strtotime($withdraw->created_at))}}</p>
        <p><strong>Date d’échéance</strong>{{date('d M Y',strtotime($withdraw->created_at))}}</p>
      </div>

      <div class="company-info">
          <div>
            <p><strong>SMONI AUTO-MOTO ÉCOLE</strong><br>
            62 Rue De La Jarry<br>
            94300 Vincennes<br>
            France<br>
            +33 7 49 46 49 78</p>
          </div>
      
            <div>
                <p><strong>Facturer à</strong><br>
                {{$user->lastname.' '.$user->firstname}}<br>
                {{$user->instructorProfile->adress}}<br>
                {{$user->instructorProfile->city}}<br>
                {{$user->instructorProfile->postal_code}}<br>
                France<br>
                {{$user->email}}</p>
            </div>
    </div>
    </div>

    <h2 class="amount-due">{{number_format($withdraw->ammount)}} € dus le {{date('d M Y',strtotime($withdraw->created_at))}}</h2>
    {{--<a href="#" class="pay-link">Payer en ligne</a>--}}

    <table class="invoice-table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Qté</th>
          <th>Prix unitaire</th>
          <th>Taxe</th>
          <th>Montant</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>Prestation : Cours</td>
          <td>{{$withdraw->duration}}</td>
          <td>{{number_format($user->instructorProfile->hourPrice)}} €</td>
          <td>{{$user->instructorProfile->tva}} %<br><small>incl. (sur {{number_format($amount - $tva)}} €)</small></td>
          <td class="highlight">{{number_format($amount)}} €</td>
        </tr>
      </tbody>
    </table>

    <table class="summary">
      <tr>
        
        <td>Sous-total</td>
        <td>{{number_format($amount)}} €</td>
      </tr>
      <tr>
        <td>Total hors taxes</td>
        <td>{{number_format($amount - $tva)}} €</td>
      </tr>
      <tr>
        <td>TVA - France ({{$user->instructorProfile->tva}} % de {{number_format($amount - $tva)}} € compris)</td>
        <td>{{number_format($tva)}} €</td>
      </tr>
      <tr class="bold">
        <td>Total</td>
        <td>{{number_format($amount)}} €</td>
      </tr>
      <tr class="bold">
        <td>Montant dû</td>
        <td>{{number_format($amount)}} €</td>
      </tr>
    </table>
</body>
</html>
