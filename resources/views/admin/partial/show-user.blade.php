<div>
    <img class="profile-user-img img-fluid img-circle mx-auto d-block mb-3" src="{{ getS3File($user->profile_image) }}" alt="User profile picture"  id="sample_image">
    <table class="table" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th>Username:</th>
                <td>{{ !empty( $user->user_name)? $user->user_name:'N/A' }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ !empty( $user->email)? $user->email:'N/A' }}</td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td>{{ !empty($user->phone_number)?$user->phone_number:'N/A' }}</td>
            </tr>
            <tr>
                <th>Address:</th>
                <td>{{ !empty($user->address_english)?$user->address_english:'N/A' }}</td>
            </tr>
            <tr>
                <th>Blood Group:</th>
                <td>{{ !empty($user->blood_group_english)?$user->blood_group_english:'N/A' }}</td>
            </tr>
            <tr>
                <th>Country:</th>
                <td>{{ !empty($user->country->name_english)?$user->country->name_english:'N/A' }}</td>
            </tr>
            <tr>
                <th>Profession:</th>
                {{-- <td>{{ !empty($user->occupation->title_english)?$user->occupation->title_english:'N/A' }}</td> --}}
                <th><a href="javascript:void(0)" onclick="showUserOccupations({{$user->id}})">See Professions</a></th>
            </tr>
            <tr>
                <th>About:</th>
                <td>{!! !empty($user->about_english)?$user->about_english:'N/A' !!}</td>
            </tr>
            <tr>
                <th>Is Public:</th>
                <td>{!! $user->is_public == 1 ? 'Yes': 'No' !!}</td>
            </tr>
        </tbody>
    </table>

</div>
