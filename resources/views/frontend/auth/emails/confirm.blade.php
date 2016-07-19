{{ trans('strings.frontend.email.membership_no'). $membership_no }}
<br>
{{ trans('strings.frontend.email.confirm_account') }}

<a href="{{ url('account/confirm/' . $token) }}">Confirm Account</a>