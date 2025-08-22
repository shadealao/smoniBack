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
                    <th style="font-weight: 900;">N°</th>
                    <th style="font-weight: 900;">Amorc</th>
                    <th style="font-weight: 900;">Nom & Email</th>
                    <th style="font-weight: 900;">Pays</th>
                    <th style="font-weight: 900;">Ticket</th>
                    <th style="font-weight: 900;">Montant</th>
                    <th style="font-weight: 900;">Frais</th>
                    <th style="font-weight: 900;">Disponible</th>
                    <th style="font-weight: 900;">Date</th>
                    <th style="font-weight: 900;">ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paid as $pay)
                    <tr>
                        <td>{{ $pay->order }}</td>
                        <td style="width:100px">{{ $pay->data['info']['num'] }}</td>
                        <td style="width:300px">
                                {{ $pay->data['info']['name'].' '.$pay->data['info']['firstname'] }} {{ isset($pay->data['info']['email']) ? $pay->data['info']['email'] : '' }} 
                        </td>
                        <td>{{ isset($pay->data['info']['country']) ? $pay->data['info']['country'] : '' }}</td>
                        <td style="width:400px">
                            {{ $event->info[$pay->data['info']['grade']-1]['name']}} <br>
                            @if(isset($pay->data['others']))

                                @foreach($pay->data['others'] as $other)
                                        <b>{{ $event->others[intval($other['sectionid'])-1]['name']}} : {{$event->others[intval($other['sectionid'])-1]['data'][intval($other['subsectionid'])-1]['name']}}</b> &nbsp; &nbsp; &nbsp; &nbsp;
                                @endforeach

                            @endif
                        </td>
                        <td style="width:100px">
                            {{ $pay->transaction->amount }} francs
                        </td>
                        <td style="width:100px">{{ $pay->transaction->amount-$pay->transaction->amounavail }} francs</td>
                        <td style="width:100px">{{ $pay->transaction->amounavail }} francs</td>
                        <td style="width:150px">{{ $pay->transaction->created_at }}</td>
                        <td style="width:150px">{{ $pay->transaction->transaction_id }}</td>
                    </tr>
                @endforeach

            </tbody>
            
            <tfoot>
                
            <tr>
                    <th style="font-weight: 900;">N°</th>
                    <th style="font-weight: 900;">Amorc</th>
                    <th style="font-weight: 900;">Nom & Email</th>
                    <th style="font-weight: 900;">Pays</th>
                    <th style="font-weight: 900;">Ticket</th>
                    <th style="font-weight: 900;">Montant</th>
                    <th style="font-weight: 900;">Frais</th>
                    <th style="font-weight: 900;">Disponible</th>
                    <th style="font-weight: 900;">Date</th>
                    <th style="font-weight: 900;">ID</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>