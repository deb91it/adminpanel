<div class="m-section">
    <div class="m-section__content table-responsive">
        <table id="Deliverys-datatable" class="table table-sm m-table m-table--head-bg-brand table-responsive table-striped table-bordered table-hover">
            <thead class="">
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Notes</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
            @forelse($tracker as $tra)
                <tr>
                    <td>{{$tra->id}}</td>
                    <td>{{$tra->description}}</td>
                    <td>{{$tra->notes}}</td>
                    <td>{{date('F j', strtotime($tra->created_at))}}</td>
                </tr>
                @empty
            @endforelse
            </tbody>
        </table>
    </div>
</div>