<div>
    <div class="d-flex justify-content-between">
        <h4><b><u>Business Application</u></b></h4>
        <p>
        <form action="{{ route('admin.business.application.status', $application->id) }}" method="post">
            @csrf
            @if(have_right('Approve-Business-Plan-Applications'))
                <button class="btn btn-sm btn-success" name="status" value="1">Approve</button>
            @endif
            @if(have_right('Reject-Business-Plan-Applications'))
                <button class="btn btn-sm btn-danger" name="status" value="2">Decline</button>
            @endif
        </form>
        </p>
    </div>
    <br>

    <div class="row">
        <div class="col-12">
            <b>image </b>
            <img style="width: 70px; height: 70px" src="{{ getS3File($application->form_image) }}" alt="image">
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <b>Selected Date: </b> <span>{{ gmdate("d-m-Y", $application->selected_date) }}</span>
        </div>
        <div class="col-6">
            <b>Form Serial Number: </b> <span>{{ $application->form_serial_number }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <b>Contact Number: </b> <span>{{ $application->form_contact_number }}</span>
        </div>
        <div class="col-6">
            <b>CNIC: </b> <span>{{ $application->form_nic_number }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <b>Full Name: </b> <span>{{ $application->form_full_name }}</span>
        </div>
        <div class="col-6">
            <b>Guardian Name: </b> <span>{{ $application->form_guardian_name }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <b>Business Coessentiality: </b> <span>{{ $application->form_business_coessentiality }}</span>
        </div>
        <div class="col-6">
            <b>Plan Reason: </b> <span>{{ $application->form_plan_reason }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <b>Temporary Address: </b> <span>{{ $application->form_temp_address }}</span>
        </div>
        <div class="col-6">
            <b>Permanent Address: </b> <span>{{ $application->form_permanent_address }}</span>
        </div>
    </div>

    <h4><b><u>Business Witnesses</u></b></h4>
    <br>

    @forelse($application->applicationWitnesses as $witness)
        @if($loop->first)
            <h5><b>witness 1</b></h5> <br>
            <div class="row">
                <div class="col-6">
                    <b>Type: </b>
                    <span>
                    @switch($witness->type)
                            @case(1) <span>Family</span> @break;
                            @case(2) <span>Outsider</span> @break;
                        @endswitch
                    </span>
                </div>
                <div class="col-6">
                    <b>Name: </b>: <span>{{ $witness->name }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Guardian Name: </b>: <span>{{ $witness->guardian_name }}</span>
                </div>
                <div class="col-6">
                    <b>CNIC: </b>: <span>{{ $witness->nic }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Relation: </b>: <span>{{ $witness->relation }}</span>
                </div>
                <div class="col-6">
                    <b>Business: </b>: <span>{{ $witness->business }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Contact: </b>: <span>{{ $witness->contact_number }}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <b>CNIC Front: </b>: <span><img style="height: 75px; height: 75px" src="{{ getS3File($witness->nic_front) }}" alt="front image of cnic"></span>
                </div>
                <div class="col-6">
                    <b>CNIC Back: </b>: <span><img style="height: 75px; height: 75px" src="{{ getS3File($witness->nic_back) }}" alt="front image of cnic"></span>
                </div>
            </div>

        @else
            {{--second witness--}}
            <br>
            <h5><b>witness 2</b></h5> <br>
            <div class="row">
                <div class="col-6">
                    <b>Type: </b>
                    <span>
                    @switch($witness->type)
                            @case(1) <span>Family</span> @break;
                            @case(2) <span>Outsider</span> @break;
                        @endswitch
                    </span>
                </div>
                <div class="col-6">
                    <b>Name: </b>: <span>{{ $witness->name }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Guardian Name: </b>: <span>{{ $witness->guardian_name }}</span>
                </div>
                <div class="col-6">
                    <b>CNIC: </b>: <span>{{ $witness->nic }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Relation: </b>: <span>{{ $witness->relation }}</span>
                </div>
                <div class="col-6">
                    <b>Business: </b>: <span>{{ $witness->business }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Contact: </b>: <span>{{ $witness->contact_number }}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <b>CNIC Front: </b>: <span><img style="height: 75px; height: 75px" src="{{ getS3File($witness->nic_front) }}" alt="front image of cnic"></span>
                </div>
                <div class="col-6">
                    <b>CNIC Back: </b>: <span><img style="height: 75px; height: 75px" src="{{ getS3File($witness->nic_back) }}" alt="front image of cnic"></span>
                </div>
            </div>

        @endif
    @empty
    <span>no witnesses yet!</span>
    @endforelse

    <br>
    <h4><b><u>Pronotes</u></b></h4><br>

    @forelse($application->applicationPronotes as $pronote)
        @if($pronote->type == 1)
            <h5><b>Pronote</b></h5> <br>
            <div class="row">
                <div class="col-6">
                    <b>Name: </b> <span>{{ $pronote->name ?? 'N/A' }}</span>
                </div>
                <div class="col-6">
                    <b>Guardian Name: </b>: <span>{{ $pronote->guardian_name ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>CNIC: </b>: <span>{{ $pronote->nic ?? 'N/A' }}</span>
                </div>
                <div class="col-6">
                    <b>Address: </b>: <span>{{ $pronote->address ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Tehsil: </b>: <span>{{ $pronote->tehcil ?? 'N/A' }}</span>
                </div>
                <div class="col-6">
                    <b>District: </b>: <span>{{ $pronote->district ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Amount: </b>: <span>{{ $pronote->amount ?? 'N/A' }}</span>
                </div>
                <div class="col-6">
                    <b>Amount Half: </b>: <span>{{ $pronote->amount_half ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Service Charges: </b>: <span>{{ $pronote->service_charges ?? 'N/A' }}</span>
                </div>
                {{-- <div class="col-6">
                    <b>Check Number: </b>: <span>{{ $pronote->check_number ?? 'N/A' }}</span>
                </div> --}}
            </div>
            <div class="row">
                {{-- <div class="col-6">
                    <b>Owner: </b>: <span>{{ $pronote->owner ?? 'N/A' }}</span>
                </div> --}}
                {{-- <div class="col-6">
                    <b>Bank: </b>: <span>{{ $pronote->bank ?? 'N/A' }}</span>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Alabd: </b>: <span>{{ $pronote->alabd ?? 'N/A' }}</span>
                </div>
            </div>
        @else
            {{--second witness--}}
            <br>
            <h5><b>Raseed Pronote</b></h5> <br>

            {{-- <div class="row">
                <div class="col-6">
                    <b>Name: </b> <span>{{ $pronote->name ?? 'N/A' }}</span>
                </div>
                <div class="col-6">
                    <b>Guardian Name: </b>: <span>{{ $pronote->guardian_name ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>CNIC: </b>: <span>{{ $pronote->nic ?? 'N/A' }}</span>
                </div>
                <div class="col-6">
                    <b>Address: </b>: <span>{{ $pronote->address ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Tehsil: </b>: <span>{{ $pronote->tehcil ?? 'N/A' }}</span>
                </div>
                <div class="col-6">
                    <b>District: </b>: <span>{{ $pronote->district ?? 'N/A' }}</span>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-6">
                    <b>Amount: </b>: <span>{{ $pronote->amount ?? 'N/A' }}</span>
                </div>
                <div class="col-6">
                    <b>Amount Half: </b>: <span>{{ $pronote->amount_half ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row">
                {{-- <div class="col-6">
                    <b>Service Charges: </b>: <span>{{ $pronote->service_charges ?? 'N/A' }}</span>
                </div> --}}
                <div class="col-6">
                    <b>Check Number: </b>: <span>{{ $pronote->check_number ?? 'N/A' }}</span>
                </div>
                <div class="col-6">
                    <b>Owner: </b>: <span>{{ $pronote->owner ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Bank: </b>: <span>{{ $pronote->bank ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Alabd: </b>: <span>{{ $pronote->alabd ?? 'N/A' }}</span>
                </div>
            </div>
        @endif
    @empty
        <span>no witnesses yet!</span>
    @endforelse

    <h4><b><u>Accounts</u></b></h4><br>
    @php
        $typeCheck = [];
        $paymentmethodArray = [];
    @endphp
    @forelse($application->applicationAccounts as $account)
        @if(!in_array($account->type, $typeCheck)  )
             @php  array_push($typeCheck,$account->type); @endphp
             <hr />
             <br />
            <b>Type: </b>
            <span>
                @switch($account->type)
                    @case(1) <span>Sending</span> @break;
                    @case(2) <span>Receiving</span> @break;
                @endswitch
            </span>
            <br />
            <b class="">Payment Method </b><span>{{ $account->paymentMethod->method_name_english }}</span>
            <br>
        @endif
           <div>
               <b>{{$account->paymentMethodDetail->method_fields_english}}  : </b> <span>{{ $account->payment_method_detail_value }}     </span>
           </div>
    @empty
    <span>No accounts yet!</span>
    @endforelse

</div>
