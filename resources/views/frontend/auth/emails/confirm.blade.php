{{ trans('strings.frontend.email.confirm_account') }}

<a href="{{ url('account/confirm/' . $token) }}">Confirm Account</a>