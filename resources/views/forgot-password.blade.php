<p>Hola {{ $user->username  }},</p>
<p>Recibes este correo ya que alguien ha solicitado recuperar tu contraseña de {{ config('app.name') }}.</p>
<p>Si has sidu tú, puedes modificar tu contraseña haciendo click en
    <a href="{{env('FRONTEND_URL')}}/forgot-password/{{$token->id}}">este enlace</a>.
</p>
<p>Si no has sido tú, puedes ignorar este mensaje.</p>
<p>Tambien puedes copiar y pegar este enlace en tu navegador: {{env('FRONTEND_URL')}}/verify-email/{{$token->id}}</p>
<p>Gracias,</p>
<p>{{ config('app.name') }}</p>