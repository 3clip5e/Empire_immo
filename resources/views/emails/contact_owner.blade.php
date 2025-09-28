<p>Bonjour {{ $property->owner->name }},</p>
<p>Vous avez reçu un message concernant votre annonce « {{ $property->title }} » :</p>
<p><strong>Nom :</strong> {{ $data['name'] }}<br>
<strong>Email :</strong> {{ $data['email'] }}</p>
<p><strong>Message :</strong><br> {!! nl2br(e($data['message'])) !!}</p>
<p>— Empire-Immo</p>
