<div class="table-responsive">
    <table class="table" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <th>{{ __('app.city') }} :</th>
            <td>{{ $post->citi->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>{{ __('app.blood-group') }} :</th>
            <td>{{ $post->blood_group ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>{{ __('app.hospital') }} :</th>
            <td>{{ $post->hospital }}</td>
        </tr>
        <tr>
            <th>{{ __('app.address') }} :</th>
            <td>{{ $post->address }}</td>
        </tr>
        <tr>
            <th>{{ __('app.blood-required-for') }} :</th>
            <td class="">{{ ($post->blood_for == 0)? __('app.your-self') : __('app.someone-else') }} </td>
        </tr>
        </tbody>
    </table>
</div>
