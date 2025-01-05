<!DOCTYPE html>

<html lang="en">

    <head>

        <meta charset="UTF-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <meta http-equiv="X-UA-Compatible" content="ie=edge" />

        <title>Mustafai resume</title>

    </head>

    <body style="font-family: sans-serif;">

        <div class="report-parent" style="width: 1170px; margin: 0 auto; background: #fff; margin-top: 15px; border-top: 5px solid #000">

            <table cellspacing="0" cellpadding="0" style="width: 100%;background-color:#eaedf5;padding:10px">

                <tbody>

                    <tr>

                        <td style="height: 20px"></td>

                    </tr>

                    <tr>

                        <td style="width: 50%">

                            <span style="display: inline-block; vertical-align: top; margin:0 auto;"><img src="{{getS3File(auth()->user()->profile_image)}}" style="height: 150px;width:150px;object-fit:contain; border-radius: 10px;" alt="image not found"></span>

                        </td>

                        <td style="text-align: right; width: 50%;margin-left:auto;">

                            <h2 style="display: inline-block; vertical-align: top"><span>{{auth()->user()->full_name}}</span><br /><span style="font-weight: 400; font-size: 14px;">{{ auth()->user()->address_english ?? __('app.your-tagline-here') }}</span></h2>

                            <br>

                            <h2 style="display: inline-block; vertical-align: top; font-weight: bold; font-size: 14px; line-height: 17px; text-align: right">{{auth()->user()->email}}<br />{{auth()->user()->phone_number}}</h2>

                        </td>

                    </tr>

                    <tr>

                        <td style="height: 20px"></td>

                    </tr>

                </tbody>

            </table>

            <table cellspacing="0" cellpadding="0" style="width: 100%">

                <tbody>

                    <tr>

                        <td style="height: 30px"></td>

                    </tr>

                </tbody>

            </table>

            <table cellspacing="0" cellpadding="0" style="width: 100%;border-bottom:2px solid #000;">

                <tbody>

                    <tr>

                        <td style="width: 30%; vertical-align: top">

                            <h2 style="display: inline-block; vertical-align: top;font-size: 15px; margin: 0">Personal Information</h2>

                        </td>

                        <td style="width: 70%; vertical-align: top">

                            <table cellspacing="0" cellpadding="0" style="width: 100%">

                                <tbody>

                                    <tr>

                                        <td>

                                            <span style="width: 100%;display: inline-block;font-size: 15px; vertical-align: top">

                                                {!! auth()->user()->about_english ?? 'yout about here' !!}

                                            </span>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </td>

                    </tr>

                    <tr>

                        <td style="height: 20px"></td>

                    </tr>

                </tbody>

            </table>

            <table cellspacing="0" cellpadding="0" style="width: 100%">

                <tbody>

                    <tr>

                        <td style="height: 20px"></td>

                    </tr>

                </tbody>

            </table>

            <table cellspacing="0" cellpadding="0" style="width: 100%;border-bottom:2px solid #000;">

                <tbody>

                    <tr>

                        <td style="width: 30%; vertical-align: top">

                            <h2 style="display: inline-block; vertical-align: top;font-size: 15px; margin: 0">Work Experience</h2>

                        </td>

                        <td style="width: 70%;">

                            <table cellspacing="0" cellpadding="0" style="width: 100%">

                                <tbody>

                                    @forelse(auth()->user()->experience as $experience)

                                    <tr>

                                        <td style="width: 100%; vertical-align: top;line-height:25px">

                                            <table cellspacing="0" cellpadding="0" style="width: 100%">

                                                <tbody>

                                                    <tr>

                                                        <td style="width: 25%; vertical-align: top">{{$experience->experience_company_english}}</td>

                                                        <td style="width: 5%; vertical-align: top"></td>

                                                        <td style="width: 70%; vertical-align: top">{{$experience->experience_start_date}} to {{!empty($experience->experience_end_date)?$experience->experience_end_date:"Present"}}</td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </td>

                                    </tr>

                                <hr>

                                    @empty



                                    <tr><td>No experience added yet!</td></tr>



                                    @endforelse

                                </tbody>

                            </table>

                        </td>

                    </tr>



                    <tr>

                        <td style="height: 15px"></td>

                    </tr>

                </tbody>

            </table>

            <table cellspacing="0" cellpadding="0" style="width: 100%">

                <tbody>

                    <tr>

                        <td style="height: 20px"></td>

                    </tr>

                </tbody>

            </table>

            <table cellspacing="0" cellpadding="0" style="width: 100%;border-bottom:2px solid #000;">

                <tbody>

                    <tr>

                        <td style="width: 30%; vertical-align: top">

                            <h2 style="display: inline-block; vertical-align: top;font-size: 15px; margin: 0">Education</h2>

                        </td>

                        <td style="width: 70%;">

                            <table cellspacing="0" cellpadding="0" style="width: 100%">

                                <tbody>

                                    @forelse(auth()->user()->education as $education)

                                    <tr>

                                        <td style="width: 100%; vertical-align: top">

                                            <table cellspacing="0" cellpadding="0" style="width: 100%">

                                                <tbody>

                                                    <tr>

                                                        <td style="width: 30%; vertical-align: top">{{$education->institute_english}}</td>

                                                        <td style="width: 5%; vertical-align: top"></td>

                                                        <td style="width: 30%; vertical-align: top">{{$education->degree_name_english}}</td>

                                                        <td style="width: 5%; vertical-align: top"></td>

                                                        <td style="width: 30%; vertical-align: top">{{$education->start_date}} to {{$education->end_date}}</td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </td>

                                    </tr>

                                    <hr>

                                    @empty



                                    <tr><td>No experience added yet!</td></tr>



                                    @endforelse

                                </tbody>

                            </table>

                        </td>

                    </tr>

                    <tr>

                        <td style="height: 15px"></td>

                    </tr>

                </tbody>

            </table>

            <table cellspacing="0" cellpadding="0" style="width: 100%">

                <tbody>

                    <tr>

                        <td style="height: 20px"></td>

                    </tr>

                </tbody>

            </table>

            <table cellspacing="0" cellpadding="0" style="width: 100%;border-bottom:2px solid #000;">

                <tbody>

                    @php

                        if(!empty(auth()->user()->skills_english)){

                            $skills=explode(',',auth()->user()->skills_english);

                        }else{

                            $skills =[];

                        }

                    @endphp

                    <tr>

                        <td style="width: 30%; vertical-align: top">

                            <h2 style="display: inline-block; vertical-align: top;font-size: 15px; margin: 0">Key skills</h2>

                        </td>

                        <td style="width: 70%;">

                            <table cellspacing="0" cellpadding="0" style="width: 100%">

                                <tbody>

                                    @forelse(array_chunk($skills, 2) as $row)

                                    <tr>

                                        @foreach($row as $item)

                                            <td style="width: 100%; vertical-align: top">

                                                <table cellspacing="0" cellpadding="0" style="width: 100%">

                                                    <tbody>

                                                        <tr>

                                                            <td style="width: 100%; vertical-align: top">{{ $item }}</td>

                                                        </tr>

                                                    </tbody>

                                                </table>

                                            </td>

                                        @endforeach

                                    </tr>

                                    @empty

                                    <tr><td>No experience added yet!</td></tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </td>

                    </tr>



                    <tr>

                        <td style="height: 15px"></td>

                    </tr>

                </tbody>

            </table>



        </div>

    </body>

</html>

