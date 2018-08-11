<div class="col-md-12">
    <table  id="table"
            class="table table-striped"
            data-toggle="table"
            data-filter-control="true"
            data-show-export="true"
            data-click-to-select="true"
            data-pagination="true">
        <thead>
            <tr>
                <th data-field="id" data-filter-control="input" data-sortable="true">#</th>
                <th data-field="abbreviation" data-filter-control="input" data-sortable="true">Abbreviation</th>
                <th data-field="name" data-filter-control="input" data-sortable="true">Name</th>
                <th data-field="url" data-filter-control="input" data-sortable="true"> </th>>URL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cryptos as $crypto)
                <tr>
                    <td class="col-md-1">{{ $crypto->id }}</td>
                    <td class="col-md-2">{{ $crypto->abbreviation }}</td>
                    <td class="col-md-4">{{ $crypto->name }}</td>
                    <td class="col-md-5"><a href="{{ $crypto->url }}" target="_blank">{{ $crypto->url }}</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
