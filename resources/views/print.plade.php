<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logbook</title>
</head>
<body>
<div class="container-fluid">
        <div class="header text-center pt-2 pb-1">
            <h3>{{ month_name }}</h3>
        </div>
        <div class="table-block t-wrapper">
            <table class="table table-sm table-hover text-light text-right">
                <thead class=" bg-secondary">
                    <th class="col-1">Дт</th>
                    <th class="col-1.5">Ост</th>
                    <th class="col-3 text-center">Км</th>
                    <th class="col-1 text-center">Зап</th>
                    <th class="col-3 text-center">Км</th>
                    <th class="col-1.5">Ост</th>
                    <th class="col-1"></th>
                </thead>
                <tbody class="text-dark bg-light">
                    @foreach ($day in $current_month)
                    <tr>
                        <th class="border-right-bold" scope="row">{{ day.date  }}</th>
                        <td class="border-right">{{ day.remains_pre }}</td>
                        <td class="border-right-bold pr-3">{{ day.odo_pre }}</td>
                        <td class="border-right-bold text-center">{{ day.fuel }}</td>
                        <td class="border-right pr-3">{{ day.odo_post }}</td>
                        <td class="border-right">{{ day.remains_post }}</td>                                         
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>