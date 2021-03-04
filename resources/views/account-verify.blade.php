<p>Hola {{ $user->username  }},</p>
<p>Recibes este correo ya que te has registrado en la aplicación {{ config('app.name') }}.</p>
<p>Por favor, verifica tu dirección de correo electrónico haciendo click en
    <a href="{{env('FRONTEND_URL')}}/verify-email/{{$emailtoken->id}}">este enlace</a>.
</p>
<p>Tambien puedes copiar y pegar este enlace en tu navegador: {{env('FRONTEND_URL')}}/verify-email/{{$emailtoken->id}}</p>
<p>Gracias,</p>
<p>{{ config('app.name') }}</p>