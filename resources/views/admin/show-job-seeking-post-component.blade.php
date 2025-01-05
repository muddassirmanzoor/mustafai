<table class="table" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <th>{{ __('app.occupation') }} :</th>
        <td>{{ $post->occupation }}</td>
    </tr>
    <tr>
        <th>{{ __('app.experience') }} :</th>
        <td>{{ $post->experience }}</td>
    </tr>
    <tr>
        <th>{{ __('app.skills') }} :</th>
        <td>{{ $post->skills }}</td>
    </tr>
    <tr>
        <th>{{ __('app.resume') }} :</th>
        <td><a href="{{ getS3File($post->resume) }}" target="_blank">{{ __('app.download-resume') }}</a></td>
    </tr>
    <tr>
        <th>{{ __('app.description') }} :</th>
        <td>{{ $post->description_english }}</td>
    </tr>
    </tbody>
</table>
