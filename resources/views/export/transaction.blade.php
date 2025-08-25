<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div >
        <table cellspacing="0" width="100%">
            <thead>
                <tr style="background: gray">
                    <th style="font-weight: 900;">Transaction ID</th>
                    <th style="font-weight: 900;">Crée le</th>
                    <th style="font-weight: 900;">Montant</th>
                    <th style="font-weight: 900;">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($withdraws as $withdraw)
                    <tr>
                        <td>{{ $withdraw->invoice_code }}</td>
                        <td>{{ $withdraw->created_at }}</td>
                        <td>{{ $withdraw->ammount }} Euro</td>
                        <td>{{ $withdraw->payed ? 'Validé' : 'En attente' }}</td>
                    </tr>
                @endforeach

            </tbody>
            
            <tfoot>
                
            <tr>
                    <th style="font-weight: 900;">Transaction ID</th>
                    <th style="font-weight: 900;">Crée le</th>
                    <th style="font-weight: 900;">Montant</th>
                    <th style="font-weight: 900;">Statut</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>