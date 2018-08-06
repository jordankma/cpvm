<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="user_delete_confirm_title">{{$model}} Activity Log</h4>
</div>
<div class="modal-body">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Activity</th>
        </tr>
        </thead>
        <tbody>
        @foreach($logs as $key=>$log)
            <tr>
                <th style="width: 100px"><strong> {{ $log->created_at }} </strong></th>
                <td>  {{ $log->description }} <td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>