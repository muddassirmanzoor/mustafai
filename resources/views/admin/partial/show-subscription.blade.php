<div>
    @if (!empty($subscription))
    <h1>Mustafai Account Detail</h1>
    <table class="table" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th>Bank Name:</th>
                <td>{{ !empty( $subscription->mustafai_account->bank->name_english)? $subscription->mustafai_account->bank->name_english:'N/A' }}</td>
            </tr>
            <tr>
                <th>Account Title:</th>
                <td>{{ !empty( $subscription->mustafai_account->account_title_english)? $subscription->mustafai_account->account_title_english:'N/A' }}</td>
            </tr>
            <tr>
                <th>Account Number:</th>
                <td>{{ !empty( $subscription->mustafai_account->account_number)? $subscription->mustafai_account->account_number:'N/A' }}</td>
            </tr>
            <tr>
                <th>Branch Number:</th>
                <td>{{ !empty( $subscription->mustafai_account->branch_number)? $subscription->mustafai_account->branch_number:'N/A' }}</td>
            </tr>
            <tr>
                <th>IBAN Number:</th>
                <td>{{ !empty( $subscription->mustafai_account->iban_number)? $subscription->mustafai_account->iban_number:'N/A' }}</td>
            </tr>
        </tbody>
    </table>

    <h1>User Account Detail</h1>
    <table class="table" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th>Bank Name:</th>
                <td>{{ !empty( $subscription->user_account->bank_name)? $subscription->user_account->bank_name:'N/A' }}</td>
            </tr>
            <tr>
                <th>Account Title:</th>
                <td>{{ !empty( $subscription->user_account->account_title)? $subscription->user_account->account_title:'N/A' }}</td>
            </tr>
            <tr>
                <th>Account Number:</th>
                <td>{{ !empty( $subscription->user_account->account_number)? $subscription->user_account->account_number:'N/A' }}</td>
            </tr>
            <tr>
                <th>Branch Number:</th>
                <td>{{ !empty( $subscription->user_account->branch_code)? $subscription->user_account->branch_code:'N/A' }}</td>
            </tr>
            <tr>
                <th>IBAN Number:</th>
                <td>{{ !empty( $subscription->user_account->iban_number)? $subscription->user_account->iban_number:'N/A' }}</td>
            </tr>
        </tbody>
    </table>
    @endif
</div>
