@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3>Appel Jitsi</h3>
  <div id="jitsi-container" style="height:600px"></div>
</div>

<script src="https://meet.jit.si/external_api.js"></script>
<script>
  const domain = "meet.jit.si";
  const options = {
      roomName: "empire-immo-{{ \Illuminate\Support\Str::random(12) }}",
      width: "100%",
      height: 600,
      parentNode: document.querySelector('#jitsi-container'),
      interfaceConfigOverwrite: { TOOLBAR_BUTTONS: [] },
  };
  const api = new JitsiMeetExternalAPI(domain, options);
</script>
@endsection
