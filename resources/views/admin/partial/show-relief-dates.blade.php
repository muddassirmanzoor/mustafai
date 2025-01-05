<div>
    <h4><b>Relief Dates</b></h4> <br>
    <form action="" id="relief-form">
        <input type="hidden" name="application_id" value="{{$application_id}}" id="rapplication_id">
        <div class="row">
            <div class="col-sm-6">
                <label for="">Start Date <span class="text-red">*</span></label>
            <input type="date" name="start_date" id="rstart_date" required>
            </div>
            <div class="col-sm-6">
                <label for="">End Date <span class="text-red">*</span></label>
                <input type="date" name="end_date" id="rend_date" required>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-2 mb-3">
            @if($reliefs->count() == 0)
                <button type="button" class="btn btn-primary pull-right" onclick="saveRelief()">Add</button>
                @else
                <button type="button" class="btn btn-primary pull-right" onclick="saveRelief('update',{{ $reliefs[0]->id }})">Add</button>

            @endif
        </div>

    </form>
    <table class="table">
        <thead>
        <tr>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
        </thead>
        <tbody>
        @forelse($reliefs as $relief)
            <tr>
                <td>{{ date("d-m-Y", $relief->start_date) }}</td>
                <td>{{ date("d-m-Y", $relief->end_date) }}</td>
            </tr>
        @empty
            {{-- <h3><span>No relief yet!</span></h3> --}}
        @endforelse
        </tbody>
    </table>
</div>
