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
                <th data-field="name" data-filter-control="input" data-sortable="true">Name</th>
                <th data-field="url" data-filter-control="input" data-sortable="true">URL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pools as $pool)
                <tr>
                    <td class="col-md-1">{{ $pool->id }}</td>
                    <td class="col-md-2">{{ $pool->name }}</td>
                    <td class="col-md-5"><a href="{{ $pool->url }}" target="_blank">{{ $pool->url }}</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
